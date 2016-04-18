<?php

// This script creates a new reply in a specific 
// topic. 

include "connectToDB.php";
include "header.php";

// Html file for styling. 
$htmlFile = file_get_contents("../html/post.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

// If the url is called directly, and error message is shown. 
if ($_SERVER['REQUEST_METHOD'] != "POST") {
	$errorMessage = "URL called directly, please do not.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
} else {
	// If the user is not signed in, an error message is shown. 
	if (!isset($_SESSION['signedin'])) {
		$errorMessage = "Sorry, you must be signed in to reply.";
		eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
	} else {
		// This section handles any errors the user makes. 
		$errors = array();

		// The content field is empty. 
		if (strlen($_POST['replyContent']) <= 0) {
			$errors[] = "The reply field can not be empty";
		}
		
		// If there are any errors, these are shown. 
		if (!empty($errors)) {
			print $htmlParts[2];
			foreach ($errors as $value) {
				$error = $value;
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[3]), '"') . "\";");
			}
			$returnId = htmlentities($_GET['id']);
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[4]), '"') . "\";");

		} else {
			// A query for inserting the new post to the topic. 
			$sql = "INSERT INTO posts(content, cdate, topic, byuser)
					VALUES ('" . strip_tags($_POST['replyContent'], "<a><b><i>") . "',
	                        NOW(),
	                        " . mysql_real_escape_string($_GET['id']) . ",
	                        " . $_SESSION['userid'] . ")";
			$result = $conn->query($sql);
			// If the query fails, the post is not saved and an error message is shown. 
			if (!$result) {
				$errorMessage = "Oops! Your reply was not saved. Please try again later.";
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
			} else {
				// If the query succeeds, the page is refreshed to
				// show the new reply. 
				header("Location: /forum/php/topics.php?id=" . htmlentities($_GET['id']) . "");
			}
		}
	}
}

include "footer.php";

?>