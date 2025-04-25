<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Database Credentials (Better Approach)
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "studentdb";


//Attempt Connection
try{
    $con = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
    if ($con->connect_error){
        throw new Exception("Failed to connect to MySQL: " . $con->connect_error);
    }
    //Check if connection is successful
    echo "Connected successfully";

}catch(Exception $e){
    echo "Error: " . $e->getMessage();
    exit; // Stop execution on error
}

?>
