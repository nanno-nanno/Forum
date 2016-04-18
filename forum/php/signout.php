<?php

// This script allows the user to sign out. 

include "connectToDB.php";
include "header.php";

// When the user is signed out, the current session
// is destroyed. 
session_unset();
session_destroy();

// Html file for styling. 
$htmlFile = file_get_contents("../html/signout.html");
print $htmlFile;

include "footer.php";

?>