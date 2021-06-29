<?php
session_start();
//Redirect to login if a staff is not logged in
if (!isset($_SESSION['staff_id'])) { 
    require ('login_tools.php'); 
    load('login.php');
}
    require ('includes/connect_db.php');
    include('classes/event.php');
    include('includes/staff_header.html');
    echo '<br />';

    //Instantiated the Event Class
    $event = new event();

    if(isset($_POST['submit'])){
        //Called the modify event method
		$event->modifyEvent($dbc, $_POST['event_id'], $_POST['eventName'], $_POST['eventType'], $_POST['eventLocation'], $_POST['eventDate'], $_POST['eventDiscount']);
	}
?>

<?php
    //Runs if an event is selected and submited
    if(isset($_POST['submit11'])){
        if(!empty($_POST['event'])) {
            $selected = $_POST['event'];
            
            $a = "SELECT * from events WHERE event_id = '$selected' ";

            $b = $dbc->query($a);

            $row2 = mysqli_fetch_array($b, MYSQLI_ASSOC);

            //Assigns the values from the selected event to the form
            $_POST['event_id'] = $row2['event_id'];
            $_POST['eventName'] = $row2['eventName'];
            $_POST['eventType'] = $row2['eventType'];
            $_POST['eventLocation'] = $row2['eventLocation'];
            $_POST['eventDate'] = $row2['eventDate'];
            $_POST['eventDiscount'] = $row2['eventDiscount'];

        } else {
            echo 'Please select the value.';
        }
    }
?>

<?php
    $q = "SELECT * from events";

    $r = $dbc->query($q);
?>

<!-- Select event form -->
<form action="amend_event.php" method="post">
    <span class="select-span">
        <select class="select" name="event" id="event">
            <option class="select" value="" selected disabled hidden>select an event</option>
            <?php
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row["event_id"] . "'>" . $row["eventName"] ."</option>";
                }
            ?>
        </select>
    </span>
    <input type="submit" name="submit11" value="Amend event"/>
</form>

<!-- Amend event form -->
<form action="amend_event.php" method="post" class="form-signin" role="form">
    <h4 class="form-signin-heading">Amend event</h4> 
    <input type="number" name="event_id" size="20" value="<?php if (isset($_POST['event_id'])) echo $_POST['event_id']; ?>" placeholder="event id" readonly>
    <input type="text" name="eventName" size="40" value="<?php if (isset($_POST['eventName'])) echo $_POST['eventName']; ?>" placeholder="Event Name">
    <input type="text" name="eventType" size="40" value="<?php if (isset($_POST['eventType'])) echo $_POST['eventType']; ?>" placeholder="Event type">
    <input type="text" name="eventLocation" size="40" value="<?php if (isset($_POST['eventLocation'])) echo $_POST['eventLocation']; ?>" placeholder="Event Location">
    <input type="date" name="eventDate" size="60" value="<?php if (isset($_POST['eventDate'])) echo $_POST['eventDate']; ?>" placeholder="Event Date">
    <br />
    <p><em>You can only add discount to event if you are a sales manager</em></p>
    <input type="number" name="eventDiscount" size="50" value="<?php if (isset($_POST['eventDiscount'])) echo $_POST['eventDiscount']; ?>" placeholder="Event discount">
    <p><button class="btn btn-primary" name="submit" type="submit">Amend</button></p>
</form>


<br />
<br />
<br />
<?php include('includes/footer.html') ?>

<!-- css -->
<style>
    .select{
        background-color: grey;
    }
    .option{
        background-color: whitesmoke;
    }
</style>
