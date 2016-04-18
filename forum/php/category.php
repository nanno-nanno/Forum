<?php

// This script shows a specific category and the topics
// related to it. 

include "connectToDB.php";
include "header.php";

// Create sql query for selecting information relevant
// to the selected category. 
$sql = "SELECT id, name, description
		FROM categories
		WHERE id = " . mysql_real_escape_string($_GET['id']);

// Html file for styling
$htmlFile = file_get_contents("../html/category.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

$result = $conn->query($sql);
// If the sql query fails in any way, an error message is shown.
if (!$result) {
	$errorMessage = "The category could not be displayed. Please try again later.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
} else {
	// If the query returns zero results, this information is shown. 
	if ($result->num_rows == 0) {
		$errorMessage = "The category does not exist.";
		eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
	} else {
		// The category name is set as the head of the page using styling
		// from the html file.
		while ($row = $result->fetch_assoc()) {
			$head = $row['name'];
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
		}
		// A new query is made for getting topics belonging to
		// the current category.
		$sql = "SELECT id, name, cdate, category
				FROM topics
				WHERE category = " . mysql_real_escape_string($_GET['id']);
		$result = $conn->query($sql);
		// If the query returns zero results, this information is shown. 
		if (!$result) {
			$errorMessage = "The topics could not be displayed. Please try again later.";
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
		} else {
			// If the query returns zero results, this information is shown. 
			if ($result->num_rows == 0) {
				$errorMessage = "No topics exist for this category.";
				eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[0]), '"') . "\";");
			} else {
				// If the result contains any information, this is looped
				// through and displayed with styling from the html file. 
				while ($row = $result->fetch_assoc()) {
					$rowId = $row['id'];
					$rowName = $row['name'];
					$rowDate = $row['cdate'];
					eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
				}
			}
		}
	}
}

include "footer.php";

?>