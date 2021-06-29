<?php
	session_start();
	//Redirect to login if an admin is not logged in
    if (!isset($_SESSION['admin_id'])){
        require ('login_tools.php'); 
        load('login.php');
    }

	require ('includes/connect_db.php');
	include('includes/admin_header.html');
	include('classes/SalesStaff.php');

	//Instantiated the sales staff class
	$staff = new SalesStaff();
	echo "<br /> ";

	//Called the view staff method
	$staff->viewStaff($dbc);

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Called the staff account details setter method, passing in the form values
		$staff->staffAccountDetails($_POST['staff_name'], $_POST['contact_number'], $_POST['staff_address'], $_POST['staff_email'], $_POST['role'], $_POST['pass1']);
		//Called the create account method
		$staff->createAccount($dbc, $staff->getName(), $staff->getContactNumber(), $staff->getAddress(), $staff->getEmail(), $staff->getRole(), $staff->getPassword(), $_SESSION['admin_id']);
	}
?>


<!-- Add staff form -->
<!-- Display body section with sticky form. -->
<form action="staff_register.php" method="post" class="form-signin" role="form">
	<h3 class="form-signin-heading">Add Staff</h3>
	<input type="text" name="staff_name" size="40" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" placeholder="staff name"> 
	<input type="number" name="contact_number" size="20" value="<?php if (isset($_POST['contact_number'])) echo $_POST['contact_number']; ?>" placeholder="Contact number">
	<input type="text" name="staff_address" size="200" value="<?php if (isset($_POST['staff_address'])) echo $_POST['staff_address']; ?>" placeholder="Staff address">
	<input type="text" name="staff_email" size="50" value="<?php if (isset($_POST['staff_email'])) echo $_POST['staff_email']; ?>" placeholder="Email Address">
	<select class="select" name="role">
		<option class="select" value="sales staff" selected>sales staff</option>
		<option class="select" value="sales manager">sales manager</option>
	</select>
	<input type="password" name="pass1" size="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" placeholder="Password">
	<input type="password" name="pass2" size="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" placeholder="Confirm Password">
	<p><button class="btn btn-primary" name="submit" type="submit">Register</button></p>
</form>

<?php
include('includes/footer.html')
?>


<style>
    .select{
        background-color: whitesmoke;
    }
    .option{
        background-color: gray;
    }
</style>
