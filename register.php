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

 <h1 class="text-center">Create an Account</h1>
 <div class="text-muted text-center mb-4">
 Please sign up in to continue to ToDoApp.
 </div>

 <?php validate_user_registration(); ?>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		<label>First Name</label>
		<input type="text" class="form-control form-control-lg fs-15px" name="first_name" required>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		<label>Last Name</label>
		<input type="text" class="form-control form-control-lg fs-15px" name="last_name" required>
		</div>
	</div>
</div>
 <div class="form-group">
 <label>Email Address</label>
 <input type="email" class="form-control form-control-lg fs-15px" name="email" required>
 </div>
 <div class="form-group">
 <div class="d-flex">
 <label>Password</label>
 </div>
 <input type="password" class="form-control form-control-lg fs-15px" name="password" required>
 </div>
 <button type="submit" name="register-submit" class="btn btn-primary btn-lg btn-block fw-500 mb-3">Create account</button>
 <div class="text-center text-muted">
 Already have an account? <a href="login">Sign in</a>.
 </div>
 </form>
 </div>

 </div>

 </div>
 <script type="text/javascript" src="assets/js/app.min.js"></script>
 </body>
 </html>
