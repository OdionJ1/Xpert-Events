<?php
    session_start();
    if (!isset($_SESSION['client_id'])) { 
        require ('login_tools.php'); 
        load('login.php');
    }

    $page_title = "Welcome."; 
    include('includes/client_header.html');
?>

<h1 id='mainhead'>Welcome</h1>
<p>You are now logged in, <?php echo "{$_SESSION['client_name']}"; ?> </p>

<?php
    include('includes/footer.html');
?>