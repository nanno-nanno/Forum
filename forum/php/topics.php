<?php

// This script displays a specific topic and its
// posts. It is also where the user can add a reply. 

include "connectToDB.php";
include "header.php";

// Html file for styling. 
$htmlFile = file_get_contents("../html/topics.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

// A sql query for getting the name of the topic. 
$sql = "SELECT id, name
		FROM topics
		WHERE topics.id = " . mysql_real_escape_string($_GET['id']);
$result = $conn->query($sql);
// If the query fails in any way, an error message is shown. 
if (!$result) {
	$errorMessage = "The topic could not be displayed. Please try again later.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
} else {
	// If the query returns zero results, an error message is shown. 
	if ($result->num_rows == 0) {
		$errorMessage = "The topic does not exist.";
		eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
	} else {
		// The topic name is set as the head of the page. 
		while ($row = $result->fetch_assoc()) {
			$head = $row['name'];
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
		}
		// A sql query for getting the posts belonging to the topic. 
		$sql = "SELECT posts.content, posts.cdate, posts.topic, posts.byuser, users.id, users.name
				FROM posts
				LEFT JOIN users
				ON posts.byuser = users.id
				WHERE posts.topic = " . mysql_real_escape_string($_GET['id']);
		$result = $conn->query($sql);
		// If the query fails in any way, an error message is shown. 
		if (!$result) {
			$errorMessage = "The posts could not be displayed. Please try again later.";
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
		} else {
			// The posts are shown in order. 
			print $htmlParts[2];
			while ($row = $result->fetch_assoc()) {
				$rowName = $row['name'];
				$rowDate = $row['cdate'];
				$rowContent = $row['content'];
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[3]), '"') . "\";");
			}
			// The reply box is shown below the posts. 
			print $htmlParts[4];
			$postId = $_GET['id'];
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[5]), '"') . "\";");
			// If the user is signed in, they can reply. 
			if (isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {
				print $htmlParts[6];
			} else {
				// If the user is not signed in, the reply box
				// is made unavailable. 
				print $htmlParts[7];
			}
		}
	}
}

include "footer.php";

?>