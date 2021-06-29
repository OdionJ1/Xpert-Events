<?php
	include('includes/header.html');

	if(isset($errors) && !empty($errors)){
		echo '<p id="err_msg">Oops! There was a problem:<br>';
		foreach($errors as $msg){
			echo " - $msg<br>";
		}
		echo '<p>Details could not be found</p>';
	}
?>

<!-- Display login form -->
<form action="login_action.php" method="post" class="form-signin" role="form">

	<h2 class="form-signin-heading">Please login</h2>

	<input type="text" name="email" placeholder="Email Address">
	<input type="password" name="pass" placeholder="Password">
	<p><button class="btn btn-primary" name="submit" type="submit">Login</button></p>
	<small><a href="password.php">Reset Password?</a></small>

</form>

<?php include('includes/footer.html') ?>
