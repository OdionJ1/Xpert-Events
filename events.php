<?php
	session_start();
	//Redirect to login if a client is not logged in
	if (!isset($_SESSION['client_id'])) { 
		require ('login_tools.php'); 
		load('login.php');
	}
	include('includes/client_header.html');
    echo '<br />';
	require ('includes/connect_db.php');
    include('classes/Event.php');

	//Instantiated the event class
	$event = new Event();

	//Called the view event for client method
	$event->viewEventClient($dbc);
?>

<?php include('includes/footer.html') ?>
