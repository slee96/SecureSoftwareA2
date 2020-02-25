<?php
if (!isset($_SESSION['authenticated'])) {
	Header ("Location: /login.php");
}
if (!isset($_SESSION['authenticatedOTP']) && isset($_SESSION['authenticated'])) {
	Header ("Location: /google-authenticator.php");
}
?>
