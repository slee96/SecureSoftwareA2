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
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputUsername" class="sr-only">Username</label>
      <input type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus name='username'>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name='password'>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
<br>
	<?php include("templates/contentstop.php"); ?>
	<?php include("templates/footer.php"); ?>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/forge/0.8.2/forge.all.min.js"></script>
	<script type="text/javascript">
	// Event listener for the form 
	$(".form-signin").submit(function(event){
	  // Prevents the default event of the from (the submit functionality, post/get)
	  event.preventDefault();
	  // Store the SHA-256 hash, generateHash() is a method from the libary 'forge' for hashing functionality 
	  var password = generateHash();
      // Ajax post request to 'login_verify.php'
	  $.post( "login_verify.php", { 
		  // post parameters
		  username: $("input[name='username']").val(), 
		  password: password }
			// this function fires after the post request has been competed, and returns the contents of the page 'login_verify.php'
			).done(function( data ) {
		  			// Custom alert messages depending on the pages response
					if (data == "Wrong username/password"){
							$("body").append("<div id=\"alert\">Wrong username/password<br><br><br><button id=\"alertbtn\" onclick=\"$('#alert').remove();\">[ close ]</button></div>");
					}else if(data == "success"){
							// Redirect the user using javascript, all the Sessions have been set in 'login_verify.php'.
							// But we can use the method 'Header "Location .." in php for redirection, 
							// since we are using ajax the user, him/herself never actually navigates to this page. Therefor cannot be redirected using php
							window.location = "/google-authenticator.php";
					}else{
							 $("body").append("<div id=\"alert\">Unknown error<br><br><br><button id=\"alertbtn\" onclick=\"$('#alert').remove();\">[ close ]</button></div>");
					}
			});
	});
	//Function to generate sha-256 hash
	function generateHash()
	{
		// password the user entered
		var plainText = $("input[name='password']").val();
		//create sha-256 hash
		var md = forge.md.sha256.create();  
		md.start();  
		md.update(plainText, "utf8");  
		// return the hashed text
		return md.digest().toHex();
	} 
   </script> 
</body>
</html>
