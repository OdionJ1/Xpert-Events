<?php
session_start();
//Redirect to login if a staff is not logged in
if (!isset($_SESSION['staff_id'])) { 
    require ('login_tools.php'); 
    load('login.php');
}
    require ('includes/connect_db.php');
    include('includes/staff_header.html');
    echo '<br />';

    include('classes/Event.php');

    //Instantiated the Event class
	$event = new Event();

    //Called the view event for staff method
	$event->viewEventStaff($dbc);
?>

<?php
    //Queries the users table for customers
    $q = "SELECT * from users WHERE role = 'customer'";

    $r = $dbc->query($q);

    //Runs if the add event form is submited
    if(isset($_POST['submit1'])){
        if(!empty($_POST['customer'])) {

            $row=mysqli_fetch_array($r, MYSQLI_ASSOC);

            $customerID = $_POST['customer'];

            //Called the eventDetails setter method, passing in the form values
            $event->eventDetails($_POST['eventName'], $_POST['eventType'], $_POST['eventLocation'], $_POST['eventDate'], $_POST['eventDiscount'], $customerID);
            //Called add event method
            $event->addEvent($dbc, $event->getEventName(), $event->getEventType(), $event->getEventLocation(), $event->getEventDate(), $event->getEventDiscount(), $event->getEventClient(), $_SESSION['staff_id']);

        } else {
            echo 'Please select the value.';
        }
    }
?>

<!-- Add event form -->
<form action="add_event.php" method="post">
    <h4 class="form-signin-heading">Add Event</h4>
    <span class="select-span">
        <select class="select" name="customer" id="customer">
            <option class="select" value="" selected disabled hidden>select a customer</option>
            <?php
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row["user_id"] . "'>" . $row["username"] ."</option>";
                }
            ?>
        </select>
    </span>
    <input type="text" name="eventName" size="40" value="<?php if (isset($_POST['eventName'])) echo $_POST['eventName']; ?>" placeholder="Event Name">
    <input type="text" name="eventType" size="40" value="<?php if (isset($_POST['eventType'])) echo $_POST['eventType']; ?>" placeholder="Event type">
    <input type="text" name="eventLocation" size="40" value="<?php if (isset($_POST['eventLocation'])) echo $_POST['eventLocation']; ?>" placeholder="Event Location">
    <input type="date" name="eventDate" size="60" value="<?php if (isset($_POST['eventDate'])) echo $_POST['eventDate']; ?>" placeholder="Event Date">
    <br />
    <p><em>You can only add discount to event if you are a sales manager</em></p>
    <input type="number" name="eventDiscount" size="50" value="<?php if (isset($_POST['eventDiscount'])) echo $_POST['eventDiscount']; ?>" placeholder="Event discount">
    <input type="submit" name="submit1" value="Add event" />
</form>


<?php
    $q = "SELECT * from events";

    $r = $dbc->query($q);

    $it = "SELECT * from items";

    $i = $dbc->query($it);
?>

<?php
    //Runs if the add item to event form is submited
    if(isset($_POST['submit2'])){
        if(!empty($_POST['event']) && !empty($_POST['item'])) {

            $selectedEvent = $_POST['event'];
            $selectedItem = $_POST['item'];

            //Called the add item to event method
            $event->addItemToEvent($dbc, $selectedEvent, $selectedItem, $_POST['item_quantity']);

        } else {
            echo 'Please select the value.';
        }
    }
?>


<!-- Add item to event form -->
<form action="add_event.php" class="form-signin" method="post">
    <h4 class="form-signin-heading">Add items to Event</h4> 
    <span class="select-span">
        <select class="select" name="event" id="event">
            <option class="select" value="" selected disabled hidden>select an Event</option>
            <?php
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row["event_id"] . "'>" . $row["eventName"] ."</option>";
                }
            ?>
        </select>
    </span>
    <span class="select-span">
        <select class="select" name="item" id="item">
            <option class="select" value="" selected disabled hidden>select an item</option>
            <?php
                while ($row2=mysqli_fetch_array($i, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row2["item_id"] . "'>" . $row2["item_name"] ."</option>";
                }
            ?>
        </select>
    </span>
    <input type="number" name="item_quantity" size="20" value="<?php if (isset($_POST['item_quantity'])) echo $_POST['item_quantity']; ?>" placeholder="Quantity">
    <input type="submit" name="submit2" value="Add item Event" />
</form>

<?php include('includes/footer.html') ?>

