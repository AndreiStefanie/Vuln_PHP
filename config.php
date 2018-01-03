<?php
    define('DB_SERVER', 'localhost:3036');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'mysql');
    define('DB_DATABASE', 'security_src');

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
 
    if($mysqli === false) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    }
?>
