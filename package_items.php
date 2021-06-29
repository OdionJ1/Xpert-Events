<?php
    require ('includes/connect_db.php');
    session_start();
    //Displays the appropriate header for the user
    if (isset($_SESSION['client_id'])) { 		// if the SESSION 'user_id' is  set...
		include('includes/client_header.html');
	} else if (isset($_SESSION['staff_id'])) {
		include('includes/staff_header.html');
	} else if (isset($_SESSION['admin_id'])) {
		include('includes/admin_header.html');
	} else{
        include('includes/header.html');
    }
    echo '<br />';

    //Gets the values passed in to the page
    $p_id=$_GET['pid'];
    $pname=$_GET['pn'];

    $page_title = 'Package items';

    //Queries the package_items table
    $q = "SELECT * from package_items WHERE package_id='{$p_id}'";
    
    $r = $dbc->query($q);

    $num = $r->num_rows;

    if ($num > 0){
        echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
        <tr>
            <th>Package name : </th>
            <th>" . $pname . "</th>
        </tr>
        <tr>
            <th>item name</th>
            <th>quantity</th>
        </tr>";

    while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
        //Queries the Item table
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
    }else{
        echo "<p class='error'>There are no items for this package</p>";
    }

?>


