<?php

// This script is used to connect to the forum
// database. It is included in every other script
// with the exception of header and footer. 

// User information for accessing the database. 
$servername = "localhost";
$username = "root";
$password = "";
$database = "forum_database";

// The connection is made with the database. 
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

?>