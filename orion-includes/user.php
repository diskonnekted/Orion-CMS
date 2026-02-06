<?php
/**
 * User API
 */

/**
 * Authenticate a user, confirming the login credentials are valid.
 *
 * @param string $username User's username or email address.
 * @param string $password User's password.
 * @return WP_User|false WP_User object if successful, false otherwise.
 */
function wp_authenticate($username, $password) {
    global $orion_db, $table_prefix;
    
    $username = $orion_db->real_escape_string($username);
    $users_table = $table_prefix . 'users';
    
    $sql = "SELECT * FROM $users_table WHERE user_login = '$username' OR user_email = '$username'";
    $result = $orion_db->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_object();
        if (password_verify($password, $user->user_pass)) {
            return new WP_User($user);
        }
    }
    
    return false;
}

/**
 * Log in a user.
 *
 * @param string $username User's username.
 * @param string $password User's password.
 * @param bool   $remember Whether to remember the user.
 * @return WP_User|false WP_User object if successful, false otherwise.
 */
function wp_signon($credentials, $secure_cookie = '') {
    $user = wp_authenticate($credentials['user_login'], $credentials['user_password']);
    
    if ($user) {
        wp_set_auth_cookie($user->ID, $credentials['remember']);
        return $user;
    }
    
    return false;
}

/**
 * Sets the authentication cookie.
 * 
 * @param int  $user_id  User ID.
 * @param bool $remember Whether to remember the user.
 */
function wp_set_auth_cookie($user_id, $remember = false) {
    $expiration = time() + (86400 * 2); // 2 days
    if ($remember) {
        $expiration = time() + (86400 * 14); // 14 days
    }
    
    // Simple cookie implementation for demo
    $cookie_value = $user_id . '|' . $expiration . '|' . hash_hmac('sha256', $user_id . $expiration, 'orion_secret_key');
    setcookie('orion_auth', $cookie_value, $expiration, '/');
}

/**
 * Clears the authentication cookie.
 */
function wp_logout() {
    setcookie('orion_auth', '', time() - 3600, '/');
}

/**
 * Determines whether the current visitor is a logged in user.
 *
 * @return bool True if user is logged in, false if not.
 */
function is_user_logged_in() {
    $user = wp_get_current_user();
    return (bool) $user->ID;
}

/**
 * Retrieves the current user object.
 *
 * @return WP_User Current WP_User instance.
 */
function wp_get_current_user() {
    global $current_user;
    
    if (isset($current_user) && ($current_user instanceof WP_User)) {
        return $current_user;
    }
    
    if (isset($_COOKIE['orion_auth'])) {
        list($user_id, $expiration, $hash) = explode('|', $_COOKIE['orion_auth']);
        
        if ($expiration > time() && hash_hmac('sha256', $user_id . $expiration, 'orion_secret_key') === $hash) {
            $user = get_user_by('id', $user_id);
            if ($user) {
                $current_user = $user;
                return $current_user;
            }
        }
    }
    
    // Return empty user object if not logged in
    $current_user = new WP_User();
    return $current_user;
}

/**
 * Retrieves user info by a given field.
 *
 * @param string $field The field to retrieve the user with. id | login | email.
 * @param mixed  $value A value for $field.
 * @return WP_User|false WP_User object on success, false on failure.
 */
function get_user_by($field, $value) {
    global $orion_db, $table_prefix;
    
    $users_table = $table_prefix . 'users';
    $value = $orion_db->real_escape_string($value);
    
    if ($field == 'id' || $field == 'ID') {
        $sql = "SELECT * FROM $users_table WHERE ID = '$value'";
    } elseif ($field == 'login' || $field == 'user_login') {
        $sql = "SELECT * FROM $users_table WHERE user_login = '$value'";
    } elseif ($field == 'email' || $field == 'user_email') {
        $sql = "SELECT * FROM $users_table WHERE user_email = '$value'";
    } else {
        return false;
    }
    
    $result = $orion_db->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return new WP_User($result->fetch_object());
    }
    
    return false;
}

/**
 * Create a new user.
 *
 * @param string $username User's username.
 * @param string $password User's password.
 * @param string $email    User's email.
 * @return int|WP_Error The newly created user's ID or a WP_Error object if the user could not be created.
 */
function wp_create_user($username, $password, $email = '') {
    global $orion_db, $table_prefix;
    
    // Basic validation
    if (username_exists($username)) {
        return new WP_Error('existing_user_login', 'Sorry, that username already exists!');
    }
    if ($email && email_exists($email)) {
        return new WP_Error('existing_user_email', 'Sorry, that email address is already used!');
    }
    
    $users_table = $table_prefix . 'users';
    $user_pass = password_hash($password, PASSWORD_DEFAULT);
    $user_nicename = strtolower(str_replace(' ', '-', $username));
    
    $sql = "INSERT INTO $users_table (user_login, user_pass, user_nicename, user_email, display_name) VALUES ('$username', '$user_pass', '$user_nicename', '$email', '$username')";
    
    if ($orion_db->query($sql)) {
        return $orion_db->insert_id;
    }
    
    return new WP_Error('db_error', 'Could not create user.');
}

function username_exists($username) {
    return get_user_by('login', $username);
}

function email_exists($email) {
    return get_user_by('email', $email);
}

/**
 * Update a user in the database.
 *
 * @param array $userdata The user data to update.
 * @return int|WP_Error The updated user's ID or a WP_Error object if the user could not be updated.
 */
function wp_update_user($userdata) {
    global $orion_db, $table_prefix;
    
    if (!isset($userdata['ID'])) {
        return new WP_Error('invalid_user_id', 'User ID must be supplied.');
    }
    
    $user_id = (int) $userdata['ID'];
    $users_table = $table_prefix . 'users';
    
    $updates = array();
    
    if (isset($userdata['user_pass']) && !empty($userdata['user_pass'])) {
        $updates[] = "user_pass = '" . password_hash($userdata['user_pass'], PASSWORD_DEFAULT) . "'";
    }
    
    if (isset($userdata['user_email'])) {
        $updates[] = "user_email = '" . $orion_db->real_escape_string($userdata['user_email']) . "'";
    }
    
    if (isset($userdata['display_name'])) {
        $updates[] = "display_name = '" . $orion_db->real_escape_string($userdata['display_name']) . "'";
    }
    
    if (empty($updates)) {
        return $user_id;
    }
    
    $sql = "UPDATE $users_table SET " . implode(', ', $updates) . " WHERE ID = $user_id";
    
    if ($orion_db->query($sql)) {
        return $user_id;
    }
    
    return new WP_Error('db_error', 'Could not update user.');
}

/**
 * WP_User class.
 */
class WP_User {
    public $ID = 0;
    public $data;
    public $caps = array();
    public $roles = array();
    
    public function __construct($id = 0, $name = '', $site_id = '') {
        if (is_object($id)) {
            $this->init($id);
        } elseif (!empty($id)) {
            $data = get_user_by('id', $id);
            if ($data) {
                $this->init($data->data);
            }
        }
    }
    
    public function init($data) {
        $this->data = $data;
        $this->ID = (int) $data->ID;
        foreach (get_object_vars($data) as $key => $value) {
            $this->$key = $value;
        }
        $this->get_role_caps();
    }
    
    public function get_role_caps() {
        $caps = get_user_meta($this->ID, 'orion_capabilities', true);
        if ($caps) {
            $this->caps = unserialize($caps);
            $this->roles = array_keys($this->caps);
        }
    }
    
    public function has_cap($cap) {
        // Simple role check for now
        if (in_array('administrator', $this->roles)) {
            return true;
        }
        
        if ($cap == 'edit_posts' && in_array('operator', $this->roles)) {
            return true;
        }
        
        return isset($this->caps[$cap]) && $this->caps[$cap];
    }
    
    public function exists() {
        return !empty($this->ID);
    }
}

/**
 * Checks if the current user has the given capability.
 *
 * @param string $capability Capability name.
 * @return bool Whether the current user has the given capability.
 */
function current_user_can($capability) {
    $user = wp_get_current_user();
    return $user->has_cap($capability);
}

/**
 * Retrieve user meta field for a user.
 */
function get_user_meta($user_id, $key = '', $single = false) {
    global $orion_db, $table_prefix;
    $usermeta_table = $table_prefix . 'usermeta';
    
    if ($key) {
        $sql = "SELECT meta_value FROM $usermeta_table WHERE user_id = $user_id AND meta_key = '$key'";
        $result = $orion_db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            if ($single) {
                $row = $result->fetch_object();
                return $row->meta_value;
            } else {
                $values = array();
                while ($row = $result->fetch_object()) {
                    $values[] = $row->meta_value;
                }
                return $values;
            }
        }
        return $single ? '' : array();
    }
    
    return array();
}

/**
 * Update user meta field based on user ID.
 */
function update_user_meta($user_id, $meta_key, $meta_value, $prev_value = '') {
    global $orion_db, $table_prefix;
    $usermeta_table = $table_prefix . 'usermeta';
    
    $existing = get_user_meta($user_id, $meta_key, true);
    
    if ($existing) {
        $sql = "UPDATE $usermeta_table SET meta_value = '$meta_value' WHERE user_id = $user_id AND meta_key = '$meta_key'";
    } else {
        $sql = "INSERT INTO $usermeta_table (user_id, meta_key, meta_value) VALUES ($user_id, '$meta_key', '$meta_value')";
    }
    
    return $orion_db->query($sql);
}

/**
 * Delete a user.
 */
function wp_delete_user($id) {
    global $orion_db, $table_prefix;
    $users_table = $table_prefix . 'users';
    $usermeta_table = $table_prefix . 'usermeta';
    
    $orion_db->query("DELETE FROM $users_table WHERE ID = $id");
    $orion_db->query("DELETE FROM $usermeta_table WHERE user_id = $id");
    
    return true;
}

