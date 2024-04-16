<?php

$db_config = [
    'hostName' => "localhost",
    'dbUser' => "root",
    'dbPassword' => "",
    'dbName' => "login_register",
];

$conn = mysqli_connect($db_config['hostName'], $db_config['dbUser'], $db_config['dbPassword'], $db_config['dbName']);
if (!$conn) {
    die("Something went wrong;");
}