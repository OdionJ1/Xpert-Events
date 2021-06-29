<?php
    //Create the Event class
    class Event{
        private $eventName;
        private $eventType;
        private $eventLocation;
        private $eventDate;
        private $itemsRequiredForEvent;
        private $discountOffered;
        private $eventClient;

        //Setter method
        public function eventDetails($eventName, $eventType, $eventLocation, $eventDate, $discountOffered, $eventClient){
            $this->eventName = $eventName;
            $this->eventType = $eventType;
            $this->eventLocation = $eventLocation;
            $this->eventDate = $eventDate;
            $this->discountOffered = $discountOffered;
            $this->eventClient = $eventClient;
        }

        //event name property getter method
        public function getEventName(){
            return $this->eventName;
        }

        //event type property getter method
        public function getEventType(){
            return $this->eventType;
        }

        //event location property getter method
        public function getEventLocation(){
            return $this->eventLocation;
        }

        //event date property getter method
        public function getEventDate(){
            return $this->eventDate;
        }

        //event client property getter method
        public function getEventClient(){
            return $this->eventClient;
        }

        //discount offered property getter method
        public function getEventDiscount(){
            return $this->discountOffered;
        }
        
        //Created an add event method to add an event, passing in the event properties
        public function addEvent($dbc, $eventName, $eventType, $eventLocation, $eventDate, $discountOffered, $eventClient, $staffID){
            $errors = array();

            //Check if the event name field is empty
            if (empty($eventName)) {
                $errors[] = 'Enter the event name'; 
            } else {
                $en = $dbc->real_escape_string(trim($eventName));
            }

            //Check if the event type field is empty
            if (empty($eventType)) {
                $errors[] = 'Enter the event type.'; 
            } else {
                $et = $dbc->real_escape_string(trim($eventType));
            }

            //Check if the event location field is empty
            if (empty($eventLocation)) {
                $errors[] = 'Enter event location.'; 
            } else {
                $el = $dbc->real_escape_string(trim($eventLocation)); 
            }

            //Check if the event Date field is empty
            if (empty($eventDate)) {
                $errors[] = 'Enter event date.'; 
            } else {
                $ed = $dbc->real_escape_string(trim($eventDate));
            }

            $dO = $dbc->real_escape_string(trim($discountOffered));

            //Queries the database to see if the event alrealdy exist using the event name and client ID
            if (empty($errors)) {
                $q = "SELECT event_id FROM events WHERE eventName='$en' AND user_id = '$eventClient'";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'Event already exists'; 
                }
            }
            
            //Insert the values entered into the Events table
            if (empty($errors)) {
                //Checks if the staff adding this event is a sales manager, if so, he/she can add a discount, if not, event discount will be set to null
                if($_SESSION['staff_role'] == 'sales manager' && $discountOffered != ''){
                    $q = "INSERT INTO events(eventName, eventType, eventLocation, eventDate, eventDiscount, user_id, staff_id) VALUES ('$en', '$et', '$el', '$ed', '$dO', '$eventClient', '$staffID')";
                } else {
                    $q = "INSERT INTO events(eventName, eventType, eventLocation, eventDate, user_id, staff_id) VALUES ('$en', '$et', '$el', '$ed', '$eventClient', '$staffID')";
                }
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Added!</h1>
                        <p>You have added an Event.</p>'; 
                }
                $dbc->close();
                include ('includes/footer.html'); 
                exit();
            } else {
                echo '<h1>Error!</h1>
                    <p id="err_msg">The following error(s) occurred:<br>';
                foreach ($errors as $msg) {
                    echo " - $msg<br>";
                }
                echo 'Please try again.</p>';
                $dbc->close();
            }
        }

        //Created a modify event method to admend an event, passing in the event properties
        public function modifyEvent($dbc, $eventId, $eventName, $eventType, $eventLocation, $eventDate, $eventDiscount){
            $errors = array();
            
            //Check if the event name field is empty
            if (empty($eventName)) {
                $errors[] = 'Enter the Event name'; 
            } else {
                $en = $dbc->real_escape_string(trim($eventName)); 
            }

            //Check if the event type field is empty
            if (empty($eventType)) {
                $errors[] = 'Enter the event type.'; 
            } else {
                $et = $dbc->real_escape_string(trim($eventType)); 
            }

            //Check if the event location field is empty
            if (empty($eventLocation)) {
                $errors[] = 'Enter event location.'; 
            } else {
                $el = $dbc->real_escape_string(trim($eventLocation)); 
            }

            //Check if the event Date field is empty
            if (empty($eventDate)) {
                $errors[] = 'Enter the event date.'; 
            } else {
                $ed = $dbc->real_escape_string(trim($eventDate)); 
            }

            $dO = $dbc->real_escape_string(trim($eventDiscount));
            
            //Updates the event with the values entered
            if (empty($errors)) {
                //Checks if the staff updating this event is a sales manager, if so, he/she can amend the event discount, if not, the event discount will be left the same
                if($_SESSION['staff_role'] == 'sales manager' && $eventDiscount != ''){
                    $q = "UPDATE events SET eventName = '$en', eventType = '$et', eventLocation = '$el', eventDate = '$ed', eventDiscount = '$dO' WHERE event_id='$eventId' ";
                } else if($_SESSION['staff_role'] == 'sales staff'){
                    $q = "UPDATE events SET eventName = '$en', eventType = '$et', eventLocation = '$el', eventDate = '$ed' WHERE event_id='$eventId' ";
                } else {
                    //If the event discount was not set, it will remain so
                    $q = "UPDATE events SET eventName = '$en', eventType = '$et', eventLocation = '$el', eventDate = '$ed', eventDiscount = null WHERE event_id='$eventId' ";
                }
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>event updated</h1>
                        <p><a href="add_event">View events</a></p>'; 
                }
                $dbc->close();
                include ('includes/footer.html'); 
                exit();
            } else {
                echo '<h1>Error!</h1>
                    <p id="err_msg">The following error(s) occurred:<br>';
                foreach ($errors as $msg) {
                    echo " - $msg<br>";
                }
                echo 'Please try again.</p>';
                $dbc->close();
            }
        }

        //Created the view Event method for staff, since the staff will should see all events
        public function viewEventStaff($dbc){
            //Queries the database for all events
            $q = "SELECT * from events";

            $r = $dbc->query($q);

            $num = $r->num_rows;

            if ($num > 0){
                echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
                <tr>
                    <th>Event name</th>
                    <th>Event Type</th>
                    <th>Event Location</th>
                    <th>Event Date</th>
                    <th>Event Discount</th>
                    <th>Customer Name</th>
                    <th>Contact Number</th>
                    <th>Added by</th>
                    <th>Details</th>
                </tr>";

                //Loops through the event rows, creating a table row
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    //Queries the Users table for all information about a user who's user_id is equals to the user_id registered under the event
                    //in order to get the name of the client registered under the event
                    $c = "SELECT * from users WHERE user_id = '{$row['user_id']}'";
                    $cc = $dbc->query($c);
                    $row2 = mysqli_fetch_array($cc, MYSQLI_ASSOC);

                    //Queries the Users table for all information about a user who's user_id is equals to the staff_id registered under the event
                    //in order to get the name of the staff that added the event
                    $s = "SELECT * from users WHERE user_id = '{$row['staff_id']}'";
                    $ss = $dbc->query($s);

                    $row3 = mysqli_fetch_array($ss, MYSQLI_ASSOC);
                    echo "<tr>
                            <td>" . $row["eventName"] . "</td>
                            <td>" . $row["eventType"] . "</td>
                            <td>" . $row["eventLocation"] . "</td>
                            <td>" . $row["eventDate"] . "</td>
                            <td>" . $row["eventDiscount"] . "%</td>
                            <td>" . $row2["username"] . "</td>
                            <td>" . $row2["contact_number"] . "</td>
                            <td>" . $row3["username"] . "</td>
                            <td><a href='event_details.php?eid=$row[event_id]&en=$row[eventName]'>More details</a></td>
                        </tr>";
                }
                echo "</table>";

                $r->free_result();
            } else{
                echo "<p class='error'>There are no Events</p>";
            }
        }

        //Created the view Event method for Clients
        public function viewEventClient($dbc){
            //Queries the Events table for all events whose user_id is equals to the client_id in session  
            $q = "SELECT * from events WHERE user_id = '{$_SESSION['client_id']}'";

            $r = $dbc->query($q);

            $num = $r->num_rows;

            if ($num > 0){
                echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
                <tr>
                    <th>Event name</th>
                    <th>Event Type</th>
                    <th>Event Location</th>
                    <th>Event Date</th>
                    <th>Event Discount</th>
                    <th>Add Guest</th>
                    <th>Details</th>
                </tr>";

                //Loops through the Event table rows creating a table row
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<tr>
                            <td>" . $row["eventName"] . "</td>
                            <td>" . $row["eventType"] . "</td>
                            <td>" . $row["eventLocation"] . "</td>
                            <td>" . $row["eventDate"] . "</td>
                            <td>" . $row["eventDiscount"] . "%</td>
                            <td><a href='add_guest.php?eid=$row[event_id]&en=$row[eventName]'>Add guest</a></td>
                            <td><a href='event_details.php?eid=$row[event_id]&en=$row[eventName]'>More details</a></td>
                        </tr>";
                }
                echo "</table>";

                $r->free_result();
            } else{
                echo "<p class='error'>There are no Events</p>";
            }
        }

        //Create the add item to event method, passing in the eventId, itemId and quantity
        public function addItemToEvent($dbc, $eventId, $itemId, $quantity){
            $errors = array();
            
            //Checks if the quantity field is empty
            if (empty($quantity)) {
                $errors[] = 'Enter item quantity.'; 
            } else {
                $itq = $dbc->real_escape_string(trim($quantity)); 
            }

            //Queries the database to see if the item has alrealdy be added to the event using the eventId and the itemId passed in
            if (empty($errors)) {
                $q = "SELECT event_id FROM event_items WHERE event_id = '$eventId' AND item_id = '$itemId' ";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'This item has already been added to this event'; 
                }
            }

            //Inserts the values passed in, into the event_items table
            if (empty($errors)) {
                $q = "INSERT INTO event_items(event_id, item_id, item_quantity) VALUES ('$eventId', '$itemId', '$itq')";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Added!</h1>
                        <p>You have added an item.</p>
                        <p><a href="add_events">View events</a></p>'; 
                }
                exit();
            } else {
                echo '<h1>Error!</h1>
                    <p id="err_msg">The following error(s) occurred:<br>';
                foreach ($errors as $msg) {
                    echo " - $msg<br>";
                }
                echo 'Please try again.</p>';
                $dbc->close();
            }
        }

        //Created the add guest to event method, passing in the eventId, guestEmail and guestName
        public function addGuestToEvent($dbc, $eventId, $guestEmail, $guestName){
            $errors = array();
            
            //Check if the guest name field is empty
            if (empty($guestName)) {
                $errors[] = 'Enter guest name.'; 
            } else {
                $gn = $dbc->real_escape_string(trim($guestName)); 
            }

            //Check if the guest email field is empty
            if (empty($guestEmail)) {
                $errors[] = 'Enter guest email.'; 
            } else {
                $ge = $dbc->real_escape_string(trim($guestEmail)); 
            }

            //Queries the database to see if the guest has alrealdy be added to the event using the eventId and the guestEmail passed in
            if (empty($errors)) {
                $q = "SELECT guestName FROM event_guests WHERE event_id = '$eventId' AND guestEmail = '$guestEmail' ";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'This guest has already been added'; 
                }
            }

             //Inserts the values passed in, into the event_guests table
            if (empty($errors)) {
                $q = "INSERT INTO event_guests(event_id, guestName, guestEmail) VALUES ('$eventId', '$gn', '$ge')";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Added!</h1>
                        <p>You have added a guest</p>
                        <p><a href="events">View events</a></p>'; 
                }
                exit();
            } else {
                echo '<h1>Error!</h1>
                    <p id="err_msg">The following error(s) occurred:<br>';
                foreach ($errors as $msg) {
                    echo " - $msg<br>";
                }
                echo 'Please try again.</p>';
                $dbc->close();
            }
        }


        public function deleteEvent(){
            return boolean;
        }
    }
?>