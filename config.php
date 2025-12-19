<?php
// Start session for login, cart, and user data
session_start();

//database connection settings
$DB_HOST = 'localhost';
$DB_USER = 'u854451208_bakenmix123'; //root
$DB_PASS = 'Jollibee87000_'; // My MySQL password //Password123
$DB_NAME = 'u854451208_bakenmix'; //bakenmix

//Create MySQL connection
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
