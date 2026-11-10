<?php

#[AllowDynamicProperties]
class OrionSQLiteResult {
    public $num_rows = 0;
    private $rows = array();
    private $idx = 0;

    public function __construct($rows) {
        $this->rows = is_array($rows) ? array_values($rows) : array();
        $this->num_rows = count($this->rows);
        $this->idx = 0;
    }

    public function fetch_assoc() {
        if ($this->idx >= $this->num_rows) return null;
        $row = $this->rows[$this->idx];
        $this->idx++;
        return is_array($row) ? $row : null;
    }

    public function fetch_row() {
        $row = $this->fetch_assoc();
        if ($row === null) return null;
        return array_values($row);
    }

    public function fetch_object() {
        $row = $this->fetch_assoc();
        if ($row === null) return null;
        return (object) $row;
    }
}

#[AllowDynamicProperties]
class OrionSQLiteDB {
    public $error = '';
    public $connect_error = '';
    public $connect_errno = 0;
    public $insert_id = 0;
    public $affected_rows = 0;

    private $pdo;

    public function __construct($db_file) {
        $this->error = '';
        $this->connect_error = '';
        $this->connect_errno = 0;
        $this->insert_id = 0;
        $this->affected_rows = 0;

        try {
            $dir = dirname($db_file);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $dsn = 'sqlite:' . $db_file;
            $this->pdo = new PDO($dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->exec('PRAGMA foreign_keys = ON');
            $this->pdo->exec('PRAGMA journal_mode = WAL');
        } catch (Throwable $e) {
            $this->connect_error = $e->getMessage();
            $this->connect_errno = 1;
        }
    }

    public function real_escape_string($value) {
        if (!isset($this->pdo)) return addslashes((string) $value);
        $q = $this->pdo->quote((string) $value);
        if (!is_string($q) || strlen($q) < 2) return addslashes((string) $value);
        return substr($q, 1, -1);
    }

    private function normalize_sql($sql) {
        $sql = (string) $sql;
        $sql = preg_replace('/\bNOW\(\)/i', 'CURRENT_TIMESTAMP', $sql);
        $sql = preg_replace('/DATE_SUB\s*\(\s*CURRENT_TIMESTAMP\s*,\s*INTERVAL\s+(\d+)\s+DAY\s*\)/i', "datetime('now','-$1 days')", $sql);
        $sql = preg_replace('/DATE_SUB\s*\(\s*NOW\(\)\s*,\s*INTERVAL\s+(\d+)\s+DAY\s*\)/i', "datetime('now','-$1 days')", $sql);
        $sql = preg_replace('/DATE_SUB\s*\(\s*`?([a-zA-Z_][a-zA-Z0-9_.]*)`?\s*,\s*INTERVAL\s+(\d+)\s+DAY\s*\)/i', "datetime($1,'-$2 days')", $sql);
        $sql = preg_replace('/\bRAND\(\s*\)/i', 'RANDOM()', $sql);
        $sql = preg_replace('/\bORDER\s+BY\s+RAND\(\s*\)/i', 'ORDER BY RANDOM()', $sql);
        $sql = preg_replace('/\bUNSIGNED\b/i', '', $sql);
        $sql = preg_replace('/\bAUTO_INCREMENT\b/i', 'AUTOINCREMENT', $sql);
        $sql = preg_replace('/ENGINE\s*=\s*\w+\s*/i', '', $sql);
        $sql = preg_replace('/DEFAULT\s+CHARSET\s*=\s*\w+\s*/i', '', $sql);
        $sql = preg_replace('/CHARSET\s*=\s*\w+\s*/i', '', $sql);
        $sql = preg_replace('/COLLATE\s*=\s*[\w_]+\s*/i', '', $sql);
        $sql = preg_replace('/ON\s+UPDATE\s+CURRENT_TIMESTAMP/i', '', $sql);

        $trim = ltrim($sql);
        if (preg_match('/^CREATE\s+TABLE\b/i', $trim) && stripos($sql, 'AUTOINCREMENT') !== false) {
            if (preg_match('/^\s*`?([a-zA-Z_][a-zA-Z0-9_]*)`?\s+[^,\n]*\bAUTOINCREMENT\b/im', $sql, $m)) {
                $col = (string) $m[1];
                if (preg_match('/PRIMARY\s+KEY\s*\(\s*`?' . preg_quote($col, '/') . '`?\s*\)/i', $sql)) {
                    $sql = preg_replace('/^\s*`?' . preg_quote($col, '/') . '`?\s+[^,\n]*\bAUTOINCREMENT\b[^,\n]*/im', $col . ' INTEGER PRIMARY KEY AUTOINCREMENT', $sql, 1);
                    $sql = preg_replace('/,\s*PRIMARY\s+KEY\s*\(\s*`?' . preg_quote($col, '/') . '`?\s*\)\s*/i', '', $sql, 1);
                    $sql = preg_replace('/PRIMARY\s+KEY\s*\(\s*`?' . preg_quote($col, '/') . '`?\s*\)\s*,/i', '', $sql, 1);
                } else {
                    $sql = preg_replace_callback(
                        '/(^\s*`?([a-zA-Z_][a-zA-Z0-9_]*)`?\s+)(?:bigint|int|integer)[^,\n]*\bAUTOINCREMENT\b[^,\n]*\bPRIMARY\s+KEY\b[^,\n]*/im',
                        function ($mm) {
                            return $mm[1] . 'INTEGER PRIMARY KEY AUTOINCREMENT';
                        },
                        $sql
                    );
                }
            }
        }
        return $sql;
    }

    public function query($sql) {
        $this->error = '';
        $this->affected_rows = 0;
        $this->insert_id = 0;

        if (!isset($this->pdo)) {
            $this->error = $this->connect_error ?: 'SQLite connection not available';
            return false;
        }

        $sql = $this->normalize_sql($sql);
        $trim = ltrim($sql);

        if (preg_match('/^SHOW\s+TABLES\s+LIKE\s+\'([^\']+)\'/i', $trim, $m)) {
            $name = (string) $m[1];
            $stmt = $this->pdo->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name = :name");
            $stmt->execute(array(':name' => $name));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return new OrionSQLiteResult($rows);
        }

        try {
            $is_select = preg_match('/^(SELECT|PRAGMA|WITH)\b/i', $trim) === 1;
            if ($is_select) {
                $stmt = $this->pdo->query($sql);
                $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
                return new OrionSQLiteResult($rows);
            }

            $res = $this->pdo->exec($sql);
            $this->affected_rows = is_int($res) ? $res : 0;
            $last = $this->pdo->lastInsertId();
            $this->insert_id = is_string($last) && $last !== '' ? (int) $last : 0;
            return true;
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function close() {
        $this->pdo = null;
    }
}
