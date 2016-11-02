<?php
// Connection parameters for Database
//For Security reasons this file should not be exposed to public
$dbhostname = "xxxxxxxxxxxxxxxxxxxxxxxresource.com";
$dbname = "xxxxxxxxxx";
$dbusername = "xxxxxxxxxxxx";
$dbpassword = "xxxxxxxxxxxx";
$connect = new mysqli($dbhostname, $dbusername, $dbpassword,$dbname);
// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?> 
