<?php
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "user_roledb";

$con = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
