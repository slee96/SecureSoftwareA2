<?php
#Google stuff
declare(strict_types=1);
ob_start();
ini_set('session.cookie_httponly', '1');

include("templates/page_header.php");

if (!isset($_SESSION['authenticated'])) {
	Header ("Location: /login.php");
}


include_once __DIR__.'/src/FixedBitNotation.php';
include_once __DIR__.'/src/GoogleAuthenticator.php';
include_once __DIR__.'/src/GoogleQrUrl.php';

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();


#$secret = 'XVQ2UIGO75XRUKJO'; 

if (isset($_POST["code"]) && isset($_POST["secret"])){
	if ($g->checkCode($_POST["secret"], $_POST["code"])) {
		$_SESSION['authenticatedOTP'] = True;
		header("Location: /admin.php");
	} else {
		global $secret;
		$secret = $g->generateSecret();
		try {
			error(3);
		}catch(Exception $e) { 
			echo "<div id=\"alert\">" . $_POST["code"] . $e->getMessage() . "<br><br><br><button id=\"alertbtn\" onclick=\"document.getElementById('alert').remove()\">[ close ]</button></div>";
		}	 
	}
}else{
	global $secret;
	$secret = $g->generateSecret();
}


?>
<!doctype html>
<html lang="en">
<head>
	<title>Login</title>
	<?php include("templates/header.php"); ?>
<style>

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}

.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}

.form-signin .form-control:focus {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
</head>

<body>
	<?php include("templates/nav.php"); ?>
	<?php include("templates/contentstart.php"); ?>

<form class="form-signin" action='#' method='POST'>
      <h1 class="h3 mb-3 font-weight-normal" style='text-align:center'>Please Verify QR</h1>
		<h5 style='text-align:center'><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en_CA" target='rand' > - Google Authenticator - </a></h5>
	  <?php 
			echo "<img style='margin:0 auto;display:block' src=\"" . \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($_SESSION["username"], $secret, 'SecureSoftwareA2') . "\"/>";
	
	  ?>
	
	  <input type="hidden" name="secret" value="<?php echo $secret;?>" />
      <label for="inputUsername" class="sr-only">One Time Password</label>
      <input type="text" id="inputUsername" class="form-control" placeholder="000000" required name='code'>
      <button style='margin-top:10px;'class="btn btn-lg btn-primary btn-block" type="submit">Verify</button>
    </form>
<br>
	<?php include("templates/contentstop.php"); ?>
	<?php include("templates/footer.php"); ?>
</body>
</html>

