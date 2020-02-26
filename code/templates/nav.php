 <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <a class="navbar-brand" href="/">Blog</a>
  <div class="collapse navbar-collapse" id="navbarCollapse">
<ul class="navbar-nav mr-auto">
<li><a class="nav-link" href="/">Home</a></li>
<?php 
	// Only show admin link, if the user is authenticaticated via username/password and google qr-code
	if ($_SESSION['authenticated'] && $_SESSION['authenticatedOTP']) {
?>
      <li class="nav-item">
        <a class="nav-link" href="/admin.php">Admin</a>
      </li>
<?php
	}
?>
</ul>
<?php
	  ## Only show logout link, if the user is authenticaticated via username/password and google qr-code
	if ($_SESSION['authenticated'] && $_SESSION['authenticatedOTP']) {
?>
<a href="/logout.php"><span class="navbar-text">Logout <?php echo $_SESSION['username'] ?></a>
</span>
<?php
	}
?>
  </div>
</nav>
