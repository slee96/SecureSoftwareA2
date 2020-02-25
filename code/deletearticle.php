<?php
session_start();
#Secured, user now has to be authenticated to delete article
include("lib/auth.php");

include("config.php");
include("lib/db.php");

$aid = $_GET['aid'];
#echo "aid=".$aid."<br>";
$result = delete_article($dbconn, $aid);
#echo "result=".$result."<br>";
# Check result
header("Location: /admin.php");

?>
