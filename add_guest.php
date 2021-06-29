<?php
    session_start();
    //Redirect to login if a client is not logged in
    if (!isset($_SESSION['client_id'])) { 
        require ('login_tools.php'); 
        load('login.php');
    }

    //Gets the values passed in to the page
    $_POST['event_id']=$_GET['eid'];
    $eventName=$_GET['en'];

    require ('includes/connect_db.php');
    include('classes/Event.php');
    include('includes/client_header.html');
    echo '<br />';
    //Instantiated the Event class
    $event = new event();

    if(isset($_POST['submit'])){
        //Called the add guest to event method
        $event->addGuestToEvent($dbc, $_POST['event_id'], $_POST['guestEmail'], $_POST['guestName']);
    }
    
?>

<!-- Add guest form -->
<form action="" method="post" class="form-signin" role="form">
    <h4 class="form-signin-heading">Event: <?php echo $eventName ?></h4>
    <h4 class="form-signin-heading">Add guest</h4>
    <label for="event_id">Event ID</label>
    <input type="number" name="event_id" size="20" value="<?php if (isset($_POST['event_id'])) echo $_POST['event_id']; ?>" placeholder="Event id" readonly>
    <input type="text" name="guestEmail" size="40" value="<?php if (isset($_POST['guestEmail'])) echo $_POST['guestEmail']; ?>" placeholder="Guest Email">
    <input type="text" name="guestName" size="20" value="<?php if (isset($_POST['guestName'])) echo $_POST['guestName']; ?>" placeholder="Guest Name">
    <p><button class="btn btn-primary" name="submit" type="submit">Add</button></p>
</form>


<?php include('includes/footer.html') ?>