<?php
if (!$_SESSION['authenticated']) {
	Header ("Location: /login.php");
	if (!$_SESSION['authenticatedOTP']) {
		Header ("Location: /google-authenticator.php");
	}
}


?>
