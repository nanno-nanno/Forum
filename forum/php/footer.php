<?php

// This script only adds a footer to the page. This
// file is included in every other page for styling,
// with the exception of header and connectToDB. 

$htmlFile = file_get_contents("../html/footer.html");
print $htmlFile;

?>