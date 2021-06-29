<?php
	require ('includes/connect_db.php');
    include('classes/Package.php');
	include('includes/admin_header.html');

	//Instantiated the package class
	$package = new Package();

	echo "<br />";

	//Called the view packages method
	$package->viewPackages($dbc);
?>

<?php include('includes/footer.html') ?>
