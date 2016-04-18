<?php

// This is the "home" or welcome page of the website. 
// Showing the latest information about the forum. 

include "connectToDB.php";
include "header.php";

// Html file for styling. 
$htmlFile = file_get_contents("../html/index.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);

print $htmlParts[0];

// Create a sql query for getting the latest topic posted. 
$sql = "SELECT id, name, cdate 
		FROM topics 
		WHERE cdate IN (SELECT MAX(cdate) FROM topics)";
$result = $conn->query($sql);
// If the sql query fails, an error message is shown. 
if (!$result) {
	$errorMessage = "The latest topic could not be displayed. Please try again later.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
} else {
	// If the query returns zero results, this information is shown. 
	if ($result->num_rows == 0) {
		$errorMessage = "No topics exist.";
		eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
	} else {
		// The query returns the latest topic and it is shown. 
		while ($row = $result->fetch_assoc()) {
			$rowId = $row['id'];
			$rowName = $row['name'];
			$rowDate = $row['cdate'];
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
		}
		print $htmlParts[3];
	}
}

include "footer.php";

?>