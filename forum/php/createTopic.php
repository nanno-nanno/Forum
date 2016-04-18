<?php

// This script is used to create new topics for the
// forum. 

include "connectToDB.php";
include "header.php";

// Html file for styling the content.
$htmlFile = file_get_contents("../html/createTopic.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);
print $htmlParts[0];

// If the user is not signed in, the page is not shown and
// a message about this is displayed. 
if (!isset($_SESSION['signedin'])) {
	$errorMessage = "Sorry, you must be signed in to create a topic.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
} else {
	// If the form for creating a new topic has not been
	// send yet, the form is shown. 
	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		// Sql query for getting all existing categories. 
		$sql = "SELECT id, name, description FROM categories";
		$result = $conn->query($sql);
		// If the sql query fails, an error message is shown. 
		if (!$result) {
			$errorMessage = "Oops, something went wrong when selecting from database. Please try again later.";
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
		} else {
			// The form is shown using styling from the html file. 
			print $htmlParts[3];
			while ($row = $result->fetch_assoc()) {
				$rowId = $row['id'];
				$rowName = $row['name'];
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[4]), '"') . "\";");
			}
			print $htmlParts[5];
		}
	} else {
		// This section handles any errors made by the user.
		// An array stores all the errors that are made. 
		$errors = array();

		// If the subject field is empty. 
		if (strlen($_POST['topicSubject']) <= 0) {
			$errors[] = "The subject field can not be empty";
		}
		// If the content field is empty. 
		if (strlen($_POST['postContent']) <= 0) {
			$errors[] = "The message field can not be empty";
		}

		// If there are any errors, they are shown. 
		if (!empty($errors)) {
			print $htmlParts[6];
			foreach ($errors as $value) {
				$error = $value; 
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[7]), '"') . "\";");
			}
			print $htmlParts[8];
		} else {
			// The were no errors and the work for creating
			// the topic is began. 
			$sql = "BEGIN WORK";
			$result = $conn->query($sql);
			// If the query fails in any way, an error message is shown. 
			if (!$result) {
				$errorMessage = "There was an error when trying to create your topic. Please try again later.";
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
			} else {
				// Create a sql query for inserting the new topic into
				// the database. Before it is inserted, we strip away 
				// any unallowed html tags and also make it safe for
				// storing in our sql database, to prevent sql injection. 
				$sql = "INSERT INTO topics(name, cdate, category, byuser)
						VALUES ('" . strip_tags(mysql_real_escape_string($_POST['topicSubject'])) . "',
								NOW(), 
								" . mysql_real_escape_string($_POST['category']) . ",
								" . $_SESSION['userid'] . ")";
				$result = $conn->query($sql);
				// If the query fails in any way, an error message is shown.
				// Also the insertion is stopped and the database returns to
				// the state before the work began. 
				if (!$result) {
					$errorMessage = "There was an error inserting your data. Please try again later.";
					eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
					$sql = "ROLLBACK";
					$result = $conn->query($sql);
				} else {
					// Create a sql query for adding the newly created
					// topic's first post. This post is also stripped
					// of unallowed html tags and made safe for storing
					// in our database. 
					$topicID = $conn->insert_id;
					$sql = "INSERT INTO posts(content, cdate, topic, byuser)
							VALUES ('" . strip_tags(mysql_real_escape_string($_POST['postContent']), "<a><b><i>") . "',
									NOW(),
									" . $topicID . ",
									" . $_SESSION['userid'] . ")";
					$result = $conn->query($sql);
					// If the query fails in any way, an error message is shown.
					// Also the insertion is stopped and the database returns to
					// the state before the work began. 
					if (!$result) {
						$errorMessage = "There was an error inserting your data. Please try again later.";
						eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
						$sql = "ROLLBACK";
						$result = $conn->query($sql);
					} else {
						// The work is complete and we commit the information. 
						// The user is now directed to their newly created topic.
						$sql = "COMMIT";
						$result = $conn->query($sql);
						header("Location: /forum/php/topics.php?id=" . $topicID . "");
					}
				}
			}
		}
	}
}

include "footer.php";

?>