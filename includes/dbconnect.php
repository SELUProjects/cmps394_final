<?php

if(!defined('MyConst')) {
   die('Direct access not permitted');
}

$db_conn = array
(
    "servername" => "localhost",
    "dbname" => "cmps394_LA_Emergency_Response",
    "username" => "cmps394",
    "password" => "secret",
);

$conn = new mysqli($db_conn["servername"], $db_conn["username"], $db_conn["password"], $db_conn["dbname"]);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
