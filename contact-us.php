<?php
	session_start();
	//Displays the appropriate header if a client is logged in
	if (isset($_SESSION['client_id'])) {
		include('includes/client_header.html');
	} else {
		include('includes/header.html');
	}
?>

<div class="form">
	<h3>Contact Xpert Events</h3>
	<?php
		include ('classes/User.php');

		//Instantiated the user class
		$user = new User();

		if(isset($_POST['submit'])){
			//Called the make Enquiry method
			$user->makeEnquiry($_POST['name'], $_POST['email'], $_POST['comments'], $_POST['phoneNumber']);
		}
	?>

	<!-- Make Enquiry form -->
	<p>Please fill out this form to contact Xpert Events.</p>
	<form action="contact-us.php" method="post" class="form-signin" role="form">
		<table width="60%"> 
			<tr>
				<td>Name: </td>
				<td><input type="text" name="name" size="30" maxlength="60" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></td>
			</tr>
			<tr>
				<td>Email Address: </td>
				<td><input type="text" name="email" size="30" maxlength="80" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
			</tr>

			<tr>
				<td>PhoneNumber: </td>
				<td><input type="number" name="phoneNumber" size="30" maxlength="20" value="<?php if (isset($_POST['phoneNumber'])) echo $_POST['phoneNumber']; ?>" /></td>
			</tr>
			
			<tr>
				<td>Enquiry: </td>
				<td><textarea name="comments" rows="5" cols="30"><?php if (isset($_POST['comments'])) echo $_POST['comments']; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="Submit" /></td>
			</tr>
		</table>
	</form>
</div>

<?php
include('includes/footer.html')
?>
