<?php
//Sets https only cookies
ini_set('session.cookie_httponly', '1');
include("templates/page_header.php");?>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//Authentication query
	$result = authenticate_user($dbconn, $_POST["username"], $_POST["password"]);
	try {
		// The @ supresses any php errors, the error() function is still fired although 
		// The error() function prints custom error messages defined in 'page_header.php'
		if (@pg_num_rows($result) or error(2) == 1) {
			//Set sessions, "seccess" message will redirect the user via javascript
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['authenticated'] = True;
			$_SESSION['id'] = pg_fetch_array($result)['id'];
			echo "success";
		}
	}catch(Exception $e) { 
		//Throws error 'Wrong username/password'
		echo $e->getMessage();
	} 
}

?>