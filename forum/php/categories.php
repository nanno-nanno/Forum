<?php

// The script for the categories page, it displays
// all the available categories. 

include "connectToDB.php";
include "header.php";

// Create sql query for selecting information from
// categories stored in the database.
$sql = "SELECT id, name, description FROM categories";
$result = $conn->query($sql);

// Gets the belonging html file for styling the page.
$htmlFile = file_get_contents("../html/categories.html");
$htmlParts = explode("<!-- ==xxx== -->", $htmlFile);
print $htmlParts[0];

// If the query fails in any way, an error message is displayed.
if (!$result) {
	$errorMessage = "The categories could not be displayed. Please try again later.";
	eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
} else {
	// If the query returns zero results, this information is displayed.
	if ($result->num_rows == 0) {
		$errorMessage = "No categories exist.";
		eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[1]), '"') . "\";");
	} else {
		// If the result contains any information, this is looped through and
		// displayed in the browser using the styling from the html file.
		while ($row = $result->fetch_assoc()) {
			$rowId = $row['id'];
			$rowName = $row['name'];
			$rowDescription = $row['description'];
			eval("print \"" . addcslashes(preg_replace("/(---(.+?)---)/", "\\2", $htmlParts[2]), '"') . "\";");
		}
	}
}

include "footer.php";

?>