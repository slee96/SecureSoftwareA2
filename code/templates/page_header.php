<?php
	session_start();
	include("config.php");
	include("lib/db.php");

#added
function error($x){
	if ($x == 1){
		throw new Exception("Invalid Syntax used");
	}else if ($x == 2){
		throw new Exception("Wrong username/password");
	}else if ($x == 3){
		throw new Exception(" - Wrong QR code");
	}
}
?>
