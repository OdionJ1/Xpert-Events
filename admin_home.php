<?php
    session_start();

    if (!isset($_SESSION['admin_id'])) { 
        require ('login_tools.php'); 
        load();
    }

    $page_title = "Welcome boss. Admin."; 
    include('includes/admin_header.html');
?>

<h1 id='mainhead'>Welcome boss</h1>
<p>You are now logged in, <?php echo "{$_SESSION['name']}"; ?> </p>

<?php
    include('includes/footer.html');
?>