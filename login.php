<?php

include("functions/init.php");

	if(logged_in()) {
		header("Location:index.php");
	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Log in | ToDoApp</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="assets/css/app.min.css" rel="stylesheet" />
<link href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet">
</head>
<body>

<div id="app" class="app app-full-height app-without-header">

<div class="login">
<div class="login-content">
<form method="post">

<h1 class="text-center">Sign In</h1>
<div class="text-muted text-center mb-4">
Please log in to continue to ToDoApp.
</div>

<?php display_message(); ?>
	<?php validate_user_login(); ?>


<div class="form-group">
<label>Email Address</label>
<input type="email" class="form-control form-control-lg fs-15px" name="email" placeholder="username@address.com" required>
</div>
<div class="form-group">
<div class="d-flex">
<label>Password</label>
</div>
<input type="password" class="form-control form-control-lg fs-15px" name="password" placeholder="Enter your password" required>
</div>
<button type="submit" name="login-submit" class="btn btn-primary btn-lg btn-block fw-500 mb-3">Sign In</button>
<div class="text-center text-muted">
Don't have an account yet? <a href="register">Sign up</a>.
</div>
</form>
</div>

</div>

</div>
<script type="text/javascript" src="assets/js/app.min.js"></script>
</body>
</html>
