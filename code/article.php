<?php include("templates/page_header.php");?>
<?php
//First, check to see if we got an article id to lookup.
//If there was no article id suppled, redirect to homepage.
//Since we're potentially doing a redirect, this has to come before ANY html content.
try{
	if (!isset($_GET['aid'])) {
		header("Location: /"); 
	}
	$aid = $_GET['aid'];
	$result= @get_article($dbconn, $aid) or error(1);
	$row = @pg_fetch_array($result, 0) or error(1); //There should only be one row

}catch(Exception $e) { 
	echo "<div id=\"alert\">Exception Caught -> " . $e->getMessage() . "<br><br><br><button id=\"alertbtn\" onclick=\"document.getElementById('alert').remove()\">[ close ]</button></div>";
} 
?>
<!doctype html>
<html lang="en">
<head>
<title><?php echo $row['title'] ?></title>
	<?php include("templates/header.php"); ?>



</head>
<body>
	<?php include("templates/nav.php"); ?>
	<?php include("templates/contentstart.php"); ?>

	<h3 class="pb-4 mb-4 font-italic border-bottom">
        Off the dome. Here we go ... 
      	</h3>

	<div class="blog-post">
	<h2 class="blog-post-title"><?php echo $row['title'] ?></h2>
	<p class="blog-post-meta">
		<?php echo substr($row['date'], 0, 10)." by ".$row['author'] ?>
	</p><p>
		<?php echo $row['content'] ?>
	</p>
      </div><!-- /.blog-post -->
	<?php include("templates/contentstop.php"); ?>
	<?php include("templates/footer.php"); ?>
</body>
</html>
