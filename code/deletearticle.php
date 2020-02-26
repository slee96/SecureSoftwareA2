<?php
# Include session_start & db connection
include("templates/page_header.php");
#Secured, user now has to be authenticated to delete article
include("lib/auth.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$aid = $_GET['aid'];
	//Check if the user created the article, returns one row if its true
	$result = authenticate_article_owner($dbconn, $_SESSION["username"], $aid);
	//Allow the article to be deleted if the user created it or is an admin
	if ($_SESSION["username"] == "admin" || @pg_num_rows($result) == 1) {
		$result = delete_article($dbconn, $aid);
		header("Location: /admin.php");
	}else{
		//Error message, with link back to previous page
		echo "Invalid permissions<br><a href='/admin.php'>Back to Admin</a>";
	}
}
?>
