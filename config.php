<?php
// Start session for login, cart, and user data
session_start();

//database connection settings
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = 'Password123'; // My MySQL password
$DB_NAME = 'bakenmix';

//Create MySQL connection
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
