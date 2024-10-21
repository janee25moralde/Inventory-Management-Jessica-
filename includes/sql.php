<?php
require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function to find all rows in a database table by name */
/*--------------------------------------------------------------*/
function find_all($table) {
    global $db;
    if (tableExists($table)) {
        return find_by_sql("SELECT * FROM `" . $db->escape($table) . "`");
    }
}

/*--------------------------------------------------------------*/
/* Function to perform SQL queries */
/*--------------------------------------------------------------*/
function find_by_sql($sql) {
    global $db;
    $result = $db->query($sql);
    $result_set = [];
    while ($row = $result->fetch_assoc()) {
        $result_set[] = $row;
    }
    return $result_set;
}

/*--------------------------------------------------------------*/
/* Function to find data from a table by ID */
/*--------------------------------------------------------------*/
function find_by_id($table, $id) {
    global $db;
    $id = (int)$id;
    if (tableExists($table)) {
        $sql = $db->query("SELECT * FROM `" . $db->escape($table) . "` WHERE id = '{$db->escape($id)}' LIMIT 1");
        return $db->fetch_assoc($sql) ?? null;
    }
    return null;
}

/*--------------------------------------------------------------*/
/* Function to delete data from a table by ID */
/*--------------------------------------------------------------*/
function delete_by_id($table, $id) {
    global $db;
    if (tableExists($table)) {
        $sql = "DELETE FROM `" . $db->escape($table) . "` WHERE id = '" . $db->escape($id) . "' LIMIT 1";
        $db->query($sql);
        return $db->affected_rows() === 1;
    }
    return false;
}

/*--------------------------------------------------------------*/
/* Function to count records by table name */
/*--------------------------------------------------------------*/
function count_by_id($table) {
    global $db;
    if (tableExists($table)) {
        $sql = "SELECT COUNT(id) AS total FROM `" . $db->escape($table) . "`";
        $result = $db->query($sql);
        return $db->fetch_assoc($result);
    }
    return 0;
}

/*--------------------------------------------------------------*/
/* Function to check if a database table exists */
/*--------------------------------------------------------------*/
function tableExists($table) {
    global $db;
    $table_exit = $db->query("SHOW TABLES LIKE '" . $db->escape($table) . "'");
    return $db->num_rows($table_exit) > 0;
}

/*--------------------------------------------------------------*/
/* Function to authenticate user login */
/*--------------------------------------------------------------*/
function authenticate($username = '', $password = '') {
    global $db;
    $username = $db->escape($username);
    $password = sha1($db->escape($password)); // Hash the password before querying

    $sql = sprintf("SELECT id, username, password, user_level FROM users WHERE username = '%s' LIMIT 1", $username);
    $result = $db->query($sql);

    if ($db->num_rows($result)) {
        $user = $db->fetch_assoc($result);
        if ($password === $user['password']) {
            return $user['id'];
        }
    }
    return false;
}

/*--------------------------------------------------------------*/
/* Function to find the current logged-in user */
/*--------------------------------------------------------------*/
function current_user() {
    static $current_user;
    global $db;

    if (!$current_user && isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $current_user = find_by_id('users', $user_id);
    }
    return $current_user;
}

/*--------------------------------------------------------------*/
/* Function to update the last login time of a user */
/*--------------------------------------------------------------*/
function updateLastLogIn($user_id) {
    global $db;
    $date = date("Y-m-d H:i:s"); // Use current timestamp
    $sql = "UPDATE users SET last_login = '{$date}' WHERE id = '{$db->escape($user_id)}' LIMIT 1";
    $result = $db->query($sql);
    return $result && $db->affected_rows() === 1;
}

/*--------------------------------------------------------------*/
/* Function to find all users with their groups */
/*--------------------------------------------------------------*/
function find_all_user() {
    $sql = "SELECT u.id, u.name, u.username, u.user_level, u.status, u.last_login, g.group_name 
            FROM users u 
            LEFT JOIN user_groups g ON g.group_level = u.user_level 
            ORDER BY u.name ASC";
    return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function to find products by title for auto-suggestions */
/*--------------------------------------------------------------*/
function find_product_by_title($product_name) {
    global $db;
    $p_name = remove_junk($db->escape($product_name));
    $sql = "SELECT name FROM products WHERE name LIKE '%$p_name%' LIMIT 5";
    return find_by_sql($sql);
}
?>
