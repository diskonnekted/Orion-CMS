<?php
/*
Plugin Name: Orion Form Plugin
Description: Plugin untuk membuat dan mengelola formulir serta data input.
Version: 1.0
Author: Orion Dev
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('ORION_FORM_PLUGIN_DIR', dirname(__FILE__) . '/');
define('ORION_FORM_PLUGIN_URL', 'orion-content/plugins/orion-form/');

// Initialize DB Tables
function orion_form_install() {
    global $orion_db, $table_prefix;
    
    $table_forms = $table_prefix . 'orion_forms';
    $table_entries = $table_prefix . 'orion_form_entries';
    
    $sql_forms = "CREATE TABLE IF NOT EXISTS $table_forms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        fields LONGTEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $sql_entries = "CREATE TABLE IF NOT EXISTS $table_entries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        form_id INT NOT NULL,
        data LONGTEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (form_id) REFERENCES $table_forms(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $orion_db->query($sql_forms);
    $orion_db->query($sql_entries);
}

// Run install on load (simple approach since no activation hook)
orion_form_install();

// Helper function to display form
function display_orion_form($form_id) {
    global $orion_db, $table_prefix;
    $table_forms = $table_prefix . 'orion_forms';
    
    $form = $orion_db->query("SELECT * FROM $table_forms WHERE id = " . intval($form_id))->fetch_assoc();
    
    if (!$form) return "Form not found.";
    
    // Handle Submission
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orion_form_id']) && $_POST['orion_form_id'] == $form_id) {
        // Simple sanitization and storage
        $data = [];
        foreach ($_POST as $key => $value) {
            if ($key != 'orion_form_id' && $key != 'submit_orion_form') {
                $data[$key] = htmlspecialchars($value);
            }
        }
        
        $table_entries = $table_prefix . 'orion_form_entries';
        $stmt = $orion_db->prepare("INSERT INTO $table_entries (form_id, data) VALUES (?, ?)");
        $json_data = json_encode($data);
        $stmt->bind_param("is", $form_id, $json_data);
        
        if ($stmt->execute()) {
            echo '<div class="bg-green-100 text-green-700 p-4 rounded mb-4">Data berhasil dikirim!</div>';
        } else {
            echo '<div class="bg-red-100 text-red-700 p-4 rounded mb-4">Gagal mengirim data.</div>';
        }
    }
    
    // Render Form
    $fields = json_decode($form['fields'], true);
    if (!$fields) return "No fields defined.";
    
    $output = '<div class="bg-white p-8 rounded-xl shadow-lg border border-slate-100 max-w-lg mx-auto">';
    $output .= '<h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">' . htmlspecialchars($form['title']) . '</h3>';
    $output .= '<form method="POST" class="space-y-5">';
    $output .= '<input type="hidden" name="orion_form_id" value="' . $form_id . '">';
    
    foreach ($fields as $field) {
        $label = htmlspecialchars($field['label']);
        $name = htmlspecialchars($field['name']);
        $type = htmlspecialchars($field['type']);
        $required = isset($field['required']) && $field['required'] ? 'required' : '';
        $req_star = isset($field['required']) && $field['required'] ? '<span class="text-red-500">*</span>' : '';
        
        $output .= '<div>';
        $output .= '<label class="block text-sm font-semibold text-slate-700 mb-2">' . $label . ' ' . $req_star . '</label>';
        
        if ($type === 'textarea') {
            $output .= '<textarea name="' . $name . '" ' . $required . ' rows="4" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:border-orion-500 focus:ring-2 focus:ring-orion-200 transition outline-none text-slate-700"></textarea>';
        } else {
            $output .= '<input type="' . $type . '" name="' . $name . '" ' . $required . ' class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:border-orion-500 focus:ring-2 focus:ring-orion-200 transition outline-none text-slate-700 h-11">';
        }
        
        $output .= '</div>';
    }
    
    $output .= '<div class="pt-2">';
    $output .= '<button type="submit" name="submit_orion_form" class="w-full px-6 py-3 bg-orion-600 text-white rounded-lg font-bold shadow-md hover:bg-orion-700 hover:shadow-lg transition transform hover:-translate-y-0.5">Kirim Formulir</button>';
    $output .= '</div>';
    $output .= '</form>';
    $output .= '</div>';
    
    return $output;
}

// Shortcode Parser
function orion_form_shortcode_parser($content) {
    // Regex to find [orion_form id="123"] or [orion_form id=123]
    $pattern = '/\[orion_form\s+id=["\']?(\d+)["\']?\]/i';
    
    return preg_replace_callback($pattern, function($matches) {
        $form_id = intval($matches[1]);
        return display_orion_form($form_id);
    }, $content);
}

// Hook into the_content
if (function_exists('add_filter')) {
    add_filter('the_content', 'orion_form_shortcode_parser');
}
