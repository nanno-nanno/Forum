<?php

// This script is included on every page, except
// for footer and connectToDB. It displayes the
// header for every page. 

// A session is started for every page, in order 
// to have a user be able to stay logged in and
// display information relevant only to them. 
session_start();

// The html file for styling the header of every page
// is fetched. 
$htmlFile = file_get_contents("../html/header.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

print $htmlParts[0];

// If the user is signed in, the user page and sign out
// options are made available. 
if (isset($_SESSION['signedin'])) {
	$userId = $_SESSION['userid'];
	$userName = $_SESSION['username'];
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
} else {
	// If no user is signed in, the sign in and sign up
	// options are made available. 
	print $htmlParts[2];
}

print $htmlParts[3];

?>