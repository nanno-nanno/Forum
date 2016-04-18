<?php

// This script allows the user to sign up
// for the forum. 

include "connectToDB.php";
include "header.php";

// Html file for styling. 
$htmlFile = file_get_contents("../html/signup.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

print $htmlParts[0];

// If the user is signed in, they can't sign up. A message
// is shown to inform the user. 
if (isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {
	$errorMessage = "You are signed in, you can sign out, if you want.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
} else {
	// If the form for signing up has not been posted
	// it is shown. 
	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		print $htmlParts[2];
	} else {
		// This section handles any errors the user makes. 
		$errors = array();

		// If the username field is empty. 
		if (strlen($_POST['username']) <= 0) {
			$errors[] = "The username field can not be empty";
		} else {
			// If the username field is filled in, following errors may occur. 
			if (isset($_POST['username'])) {
				// If the username field contains illegal characters. 
				if (!ctype_alnum($_POST['username'])) {
					$errors[] = "The username contains illegal characters, accepted characters are A-Z,a-z,1-9";
				}
				// If the username exceeds 30 characters. 
				if (!strlen($_POST['username']) > 30) {
					$errors[] = "The username can not be longer than 30 characters";
				}
				// A check is made to see if the username already exists. 
				$sql = "SELECT name FROM users";
				$result = $conn->query($sql);
				// If the check can't be made, an error message is shown. 
				if (!$result) {
					$errorMessage = "Sorry, something went wrong when registering. Please try again later.";
					eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[3]), '"') . "\";");
				} else {
					// Compare existing usernames to the username from the
					// form, if the username already exists, an error 
					// message is shown. 
					while ($row = $result->fetch_assoc()) {
						if (strcmp($_POST['username'], $row['name']) == 0) {
							$errors[] = "The entered username already exists, please choose another one.";
							break;
						}
					}
				}
			}
		}

		// If the password field is empty or not set. 
		if (!isset($_POST['password']) || strlen($_POST['password']) <= 0) {
			$errors[] = "The password field can not be empty";
		}

		// If there are any errors, these are shown. 
		if (!empty($errors)) {
			print $htmlParts[4];
			foreach ($errors as $value) {
				$error = $value;
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[5]), '"') . "\";");
			}
			print $htmlParts[6];
		} else {
			// There were no errors, and the user information is
			// inserted into the database. The username is made
			// safe for storing and the password is encrypted
			// with sha1. 
			$sql = "INSERT INTO users(name, password) 
			VALUES ('" . mysql_real_escape_string($_POST['username']) . "',
					'" . sha1($_POST['password']) . "')";
			$result = $conn->query($sql);
			// If the query fails in any way, an error message is shown. 
			if (!$result) {
				$errorMessage = "Sorry, something went wrong when registering. Please try again later.";
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[3]), '"') . "\";");
			} else {
				// If the query succeeds, the user is now created. 
				// A message is shown to inform the user of this. 
				$successMessage = "Woho! You were successfully registered! You can now sign in.";
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[7]), '"') . "\";");
			}
		}
	}
}

include "footer.php";

?>