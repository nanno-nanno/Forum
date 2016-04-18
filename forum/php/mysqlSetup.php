<?php

// This script should be run in order to create
// and setup the forum database. Certain aspects
// can be changed to ones liking. Such as the 
// name of the database and the names and numbers
// of the categories. 

// Information for connecting to the database. 
$servername = "localhost";
$username = "root";
$password = "";
$database = "forum_database";

// Initial connection to create the database. 
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE forum_database";

if ($conn->query($sql) === TRUE) {
    echo "<br>Database created successfully";
} else {
    echo "<br>Error creating database: " . $conn->error;
}

$conn->close();

// New connection made to create tables for the database. 
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// users table
$sql = "CREATE TABLE users (
id INT(8) NOT NULL AUTO_INCREMENT,
name VARCHAR(30) NOT NULL,
password VARCHAR(255) NOT NULL,
UNIQUE INDEX name_unique (name),
PRIMARY KEY (id)
)";

if ($conn->query($sql) === TRUE) {
    echo "<br>Table users created successfully";
} else {
    echo "<br>Error creating table: " . $conn->error;
}

// categories table
$sql = "CREATE TABLE categories (
id INT(8) NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
description VARCHAR(255) NOT NULL,
UNIQUE INDEX name_unique (name),
PRIMARY KEY (id)
)";

if ($conn->query($sql) === TRUE) {
    echo "<br>Table categories created successfully";
} else {
    echo "<br>Error creating table: " . $conn->error;
}

// topics table
$sql = "CREATE TABLE topics (
id INT(8) NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
cdate DATETIME NOT NULL,
category INT(8) NOT NULL,
byuser INT(8) NOT NULL,
PRIMARY KEY (id)
)";

if ($conn->query($sql) === TRUE) {
    echo "<br>Table topics created successfully";
} else {
    echo "<br>Error creating table: " . $conn->error;
}

// posts table
$sql = "CREATE TABLE posts (
id INT(8) NOT NULL AUTO_INCREMENT,
content TEXT NOT NULL,
cdate DATETIME NOT NULL,
topic INT(8) NOT NULL,
byuser INT(8) NOT NULL,
PRIMARY KEY (id)
)";

if ($conn->query($sql) === TRUE) {
    echo "<br>Table posts created successfully";
} else {
    echo "<br>Error creating table: " . $conn->error;
}

// Alterations are made to the tables in order
// to connect them. 
$sql = "ALTER TABLE topics ADD FOREIGN KEY(category) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE";

if ($conn->query($sql) === TRUE) {
    echo "<br>Altered table successfully";
} else {
    echo "<br>Error altering table: " . $conn->error;
}

$sql = "ALTER TABLE topics ADD FOREIGN KEY(byuser) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE";

if ($conn->query($sql) === TRUE) {
    echo "<br>Altered table successfully";
} else {
    echo "<br>Error altering table: " . $conn->error;
}

$sql = "ALTER TABLE posts ADD FOREIGN KEY(topic) REFERENCES topics(id) ON DELETE CASCADE ON UPDATE CASCADE";

if ($conn->query($sql) === TRUE) {
    echo "<br>Altered table successfully";
} else {
    echo "<br>Error altering table: " . $conn->error;
}

$sql = "ALTER TABLE posts ADD FOREIGN KEY(byuser) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE";

if ($conn->query($sql) === TRUE) {
    echo "<br>Altered table successfully";
} else {
    echo "<br>Error altering table: " . $conn->error;
}

// Initial categories are made. These can be changed
// and the amount of how many categories to create
// can be altered. 
$sql = "INSERT INTO categories (name, description) VALUES ('General Discussion', 'All things PHP related goes here.')";

if ($conn->query($sql) === TRUE) {
    echo "<br>Insert to categories successfull";
} else {
    echo "<br>Insert failed: " . $conn->error;
}

$sql = "INSERT INTO categories (name, description) VALUES ('Problem Solving', 'PHP related problems goes here.')";

if ($conn->query($sql) === TRUE) {
    echo "<br>Insert to categories successfull";
} else {
    echo "<br>Insert failed: " . $conn->error;
}

$sql = "INSERT INTO categories (name, description) VALUES ('Work & Employment', 'PHP masters looking for work.')";

if ($conn->query($sql) === TRUE) {
    echo "<br>Insert to categories successfull";
} else {
    echo "<br>Insert failed: " . $conn->error;
}

$sql = "INSERT INTO categories (name, description) VALUES ('Miscellaneous', 'What does not belong above, belongs here.')";

if ($conn->query($sql) === TRUE) {
    echo "<br>Insert to categories successfull";
} else {
    echo "<br>Insert failed: " . $conn->error;
}

?>