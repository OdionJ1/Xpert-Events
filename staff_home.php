<?php
    session_start();
    if (!isset($_SESSION['staff_id'])) { 
        require ('login_tools.php'); 
        load('login.php');
    }

    $page_title = "Welcome."; 
    include('includes/staff_header.html');
?>

<h1 id='mainhead'>Welcome</h1>
<p>You are now logged in, <?php echo "{$_SESSION['staff_name']}"; ?> </p>

<?php
    include('includes/footer.html');
?>