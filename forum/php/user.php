<?php

// This script allows the user to see information 
// relevant only to them. Right now, that includes
// all the topics they have created. 

include "connectToDB.php";
include "header.php";

// Html file for styling. 
$htmlFile = file_get_contents("../html/user.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

// If the user is signed in, the page is shown. 
if (isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {
	$head = $_SESSION['username'];
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");

	// A sql query for getting all the topics that
	// the user has made. 
	$sql = "SELECT id, name, cdate, category 
			FROM topics 
			WHERE byuser = '" . mysql_real_escape_string($_SESSION['userid']) . "'";
	$result = $conn->query($sql);
	// If the query fails in any way, an error message is shown. 
	if (!$result) {
		$errorMessage = "Sorry, your topics could not be displayed.";
		eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
	} else {
		// The user made topics are shown. 
		print $htmlParts[2];
		if ($result->num_rows > 0) {
			print $htmlParts[3];
			while ($row = $result->fetch_assoc()) {
				$rowId = $row['id'];
				$rowName = $row['name'];
				$rowDate = $row['cdate'];
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[4]), '"') . "\";");
			}
		} else {
			// If no topics have been made by the user, 
			// this information is shown. 
			$errorMessage = "You have not created any topics yet.";
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[5]), '"') . "\";");
		}
		print $htmlParts[6];
	}
} else {
	// If the user is not signed in, the page is not shown. 
	// The user is given the opportunity to sign in. 
	$errorMessage = "You are not signed in, you can sign in, if you want.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[7]), '"') . "\";");
}

include "footer.php";

?>