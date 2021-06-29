<?php
    require ('includes/connect_db.php');
    session_start();
    //Displays the appropriate user header
	if (isset($_SESSION['client_id'])) {
		include('includes/client_header.html');
	} else {
		include('includes/staff_header.html');
	}
    echo '<br />';

    //Gets the values passed in to the page
    $eid=$_GET['eid'];
    $en=$_GET['en'];

    //Queries the event_items table
    $q = "SELECT * from event_items WHERE event_id='{$eid}'";
    
    $r = $dbc->query($q);

    $num = $r->num_rows;

    if ($num > 0){
        echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
        <tr>
            <th>Event name : </th>
            <th><em>" . $en . "</em></th>
        </tr>
        <tr>
            <th>item name</th>
            <th>quantity</th>
        </tr>";

        while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
            $it = "SELECT * from items WHERE item_id = '{$row['item_id']}'";
            
            $r2 = $dbc->query($it);
            
            $num2 = $r2->num_rows;
        
            $row2=mysqli_fetch_array($r2, MYSQLI_ASSOC);
            echo "<tr>
                    <td>" . $row2["item_name"] . "</td>
                    <td>" . $row["item_quantity"] . "</td>
                </tr>";
        }
            echo "</table>";
        
            $r->free_result();
        echo "<br />";
    }else{
        echo "<p class='error'>There are no items for this Event</p>";
    }
    
    //Queries the event_guest table
    $eg = "SELECT * from event_guests WHERE event_id = '{$eid}'";
    
    $r3 = $dbc->query($eg);
    
    $num3 = $r3->num_rows;

    if ($num3 > 0){
        echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
        <tr>
            <th>Guests</th>
            <th>Total: " . $num3 . "</th>
        </tr>
        <tr>
            <th>Guest Email</th>
            <th>Guest Name</th>
        </tr>";

        while($row4=mysqli_fetch_array($r3, MYSQLI_ASSOC)){
            echo "<tr>
                    <td>" . $row4["guestName"] . "</td>
                    <td>" . $row4["guestEmail"] . "</td>
                </tr>";
        }
            echo "</table>";
            $r3->free_result();
    }else{
        echo "<p class='error'>There are no guests for this Event</p>";
    }

?>


