<?php

// This script allows the user to sign in to the forum
// using a form. 

include "connectToDB.php";
include "header.php";

// Html file for styling. 
$htmlFile = file_get_contents("../html/signin.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

print $htmlParts[0];

// If the user is signed in, the user is informed of this and
// is given the opportunity to sign out. 
if (isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {
	$errorMessage = "You are signed in, you can sign out, if you want.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
} else {
	// If the form for signing in has not been posted, it is shown. 
	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		print $htmlParts[2];
	} else {
		// This sections handles errors made by the user. 
		$errors = array();

		// Check that username field is not empty. 
		if (!isset($_POST['username']) || strlen($_POST['username']) <= 0) {
			$errors[] = "The username field can not be empty";
		}
		// Check that password field is not empty. 
		if (!isset($_POST['password']) || strlen($_POST['password']) <= 0) {
			$errors[] = "The password field can not be empty";
		}

		// If there are any errors, these are shown. 
		if (!empty($errors)) {
			print $htmlParts[3];
			foreach ($errors as $value) {
				$error = $value;
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[4]), '"') . "\";");
			}
			print $htmlParts[5];
		} else {
			// There were no errors, so the information from 
			// the form is used to check if the user exists
			// in the database. 
			$sql = "SELECT id, name 
					FROM users 
					WHERE name = '" . mysql_real_escape_string($_POST['username']) . "'
					AND '" . sha1($_POST['password']) . "'";
			$result = $conn->query($sql);
			// If the query fails in any way, an error message is shown. 
			if (!$result) {
				$errorMessage = "Sorry, something went wrong when signing in. Please try again later.";
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[6]), '"') . "\";");
			} else {
				// If the query returns zero results, the user information
				// does not exist in the database. The user is informed of
				// this and is given the opportunity to try again. 
				if ($result->num_rows == 0) {
					$errorMessage = "The username and password you entered are invalid. Please try again.";
					eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[7]), '"') . "\";");
				} else {
					// If the query succeeds, the user exists and
					// is signed in. Relevant information is stored
					// in the current session for use on other pages. 
					$_SESSION['signedin'] = true;
					while ($row = $result->fetch_assoc()) {
						$_SESSION['userid'] = $row['id'];
						$_SESSION['username'] = $row['name'];
					}
					// A message is shown to inform the user that they
					// have been signed in. 
					$userName = $_SESSION['username'];
					eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[8]), '"') . "\";");
				}
			}
		}
	}
}

include "footer.php";

?>