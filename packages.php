<?php
	require ('includes/connect_db.php');
    include('classes/Package.php');
	include('includes/header.html');

	//Instantiated the package class
	$package = new Package();

	echo "<h6><a href='client_register.php'> Register with us</a> to book a package</h6>";
	echo "<br />";

	//Called the view packages method
	$package->viewPackages($dbc);
?>

<?php include('includes/footer.html') ?>
