<?php
require_once('config.php');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if($mysqli === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

$mysqli->set_charset('utf8');
