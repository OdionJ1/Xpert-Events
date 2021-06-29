<?php
    require ('includes/connect_db.php');
	include('includes/header.html');
	include('classes/Client.php');

	//Instantiated the Client class
	$client = new Client();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Called the client account details setter method
		$client->clientAccountDetails($_POST['client_name'], $_POST['contact_number'], $_POST['client_address'], $_POST['client_email'], 'customer', $_POST['pass1'], $_POST['corporate']);
		//Called the create account method, passing in the getter methods
		$client->createAccount($dbc, $client->getName(), $client->getContactNumber(), $client->getAddress(), $client->getEmail(), $client->getRole(), $client->getPassword(), $client->getCorporate());
	}
?>

<!-- Client Registration form -->
<form action="client_register.php" method="post" class="form-signin" role="form">
	<h3 class="form-signin-heading">Register</h3>
	<input type="text" name="client_name" size="40" value="<?php if (isset($_POST['client_name'])) echo $_POST['client_name']; ?>" placeholder="Full name"> 
	<input type="number" name="contact_number" size="20" value="<?php if (isset($_POST['contact_number'])) echo $_POST['contact_number']; ?>" placeholder="Contact number">
	<input type="text" name="client_address" size="200" value="<?php if (isset($_POST['client_address'])) echo $_POST['client_address']; ?>" placeholder="Address">
	<input type="text" name="client_email" size="50" value="<?php if (isset($_POST['client_email'])) echo $_POST['client_email']; ?>" placeholder="Email Address">
	<label for="corporate">Corporate?</label>
	<select class="select" name="corporate">
		<option class="select" value="N" selected>No</option>
		<option class="select" value="Y">Yes</option>
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