<?php
// redirect the user to login.php if they arent authenticated via username/password
if (!isset($_SESSION['authenticated'])) {
	Header ("Location: /login.php");
}
// redirect the user google-authenticator.php page if they arent authenticated via qr-code
if (!isset($_SESSION['authenticatedOTP']) && isset($_SESSION['authenticated'])) {
	Header ("Location: /google-authenticator.php");
}
?>
