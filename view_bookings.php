<?php
    session_start();
    //Redirect to login if a staff is not logged in
    if (!isset($_SESSION['staff_id'])) { 
        require ('login_tools.php'); 
        load('login.php');
    }

    require ('includes/connect_db.php');
    include('classes/Package.php');
    include('includes/staff_header.html');
    echo '<br />';
    //Instantiated the package class
    $package = new Package();

    //Called the view bookings method
    $package->viewBookings($dbc);
?>