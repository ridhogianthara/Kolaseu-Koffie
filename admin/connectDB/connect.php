<?php
// ======================= SESSION START =======================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ======================= DEFINE CONSTANTS =======================
// detect root folder name dynamically
$path = explode(DIRECTORY_SEPARATOR, dirname(__DIR__, 2));
$root_folder = end($path);
if (!defined('HOMEURL')) define('HOMEURL', 'http://localhost/' . $root_folder . '/');
if (!defined('LHOST')) define('LHOST', 'localhost');
if (!defined('DB_UNAME')) define('DB_UNAME', 'root');
if (!defined('DB_PWD')) define('DB_PWD', '');
if (!defined('DB_NAME')) define('DB_NAME', 'food_order');

// ======================= CONNECT TO DATABASE =======================
$conn = mysqli_connect(LHOST, DB_UNAME, DB_PWD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: you can uncomment ini jika mau pakai select db terpisah
// $db_select = mysqli_select_db($conn, DB_NAME) or die("Database selection failed: " . mysqli_error($conn));
?>
