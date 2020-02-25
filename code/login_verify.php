<?php
ini_set('session.cookie_httponly', '1');
include("templates/page_header.php");?>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	#Setting up values for prepared statments to be done in db.php
	$user = $_POST["username"] ?? '';
  	$pass = $_POST["password"] ?? '';
	$result = authenticate_user($dbconn, $user, $pass);
	try {
		if (@pg_num_rows($result) or error(2) == 1) {
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['authenticated'] = True;
			$_SESSION['id'] = pg_fetch_array($result)['id'];
			echo "success";
			//Redirect to 2fa area
			//header("Location: /google-authenticator.php");
		}
	}catch(Exception $e) { 
		echo $e->getMessage();
	} 
}

?>