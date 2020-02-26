<?php include("templates/page_header.php");?>
<?php include("lib/auth.php") ?>
<?php
try {
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		$aid = $_GET['aid'];	
		$result= get_article($dbconn, $aid) or error(1);
		$row = pg_fetch_array($result, 0);
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$title = $_POST['title'];
		$content = $_POST['content'];
		$aid = $_POST['aid'];
		//Check if the user created the article, returns one row if its true
		$result = authenticate_article_owner($dbconn, $_SESSION["username"], $aid);
		//Allow the article to be edited if the user created it or is an admin
		if ($_SESSION["username"] == "admin" || @pg_num_rows($result) or error(3) == 1){
			// Upadte the article
			$result= update_article($dbconn, $title, $content, $aid) or error(1);
			Header ("Location: /");
		}else{
			// If the user doesnt own the article, error message will be displayed
			error(3);
		}
	}
}catch(Exception $e) { 
		// Format for error message
		echo "<div id=\"alert\">" . $e->getMessage() . "<br><br><br><button id=\"alertbtn\" onclick=\"document.getElementById('alert').remove()\">[ close ]</button></div>";
} 
?>

<!doctype html>
<html lang="en">
<head>
	<title>New Post</title>
	<?php include("templates/header.php"); ?>
</head>
<body>
	<?php include("templates/nav.php"); ?>
	<?php include("templates/contentstart.php"); ?>

<h2>New Post</h2>

<form action='#' method='POST'>
	<input type="hidden" value="<?php echo $row['aid'] ?>" name="aid">
	<div class="form-group">
	<label for="inputTitle" class="sr-only">Post Title</label>
	<input type="text" id="inputTitle" required autofocus name='title' value="<?php echo $row['title'] ?>">
	</div>
	<div class="form-group">
	<label for="inputContent" class="sr-only">Post Content</label>
	<textarea name='content' id="inputContent"><?php echo $row['content'] ?></textarea>
	</div>
	<input type="submit" value="Update" name="submit" class="btn btn-primary">
</form>
<br>

	<?php include("templates/contentstop.php"); ?>
	<?php include("templates/footer.php"); ?>
</body>
</html>
