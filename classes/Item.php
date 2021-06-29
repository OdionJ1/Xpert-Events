<?php
    //Created the Item Class
    class Item {
        private $itemNumber;
        private $itemName;
        private $itemQuantity;
        private $itemType;

        //Setter method
        public function itemDetails($itemNumber, $itemName, $itemQuantity, $itemType){
            $this->itemNumber = $itemNumber;
            $this->itemName = $itemName;
            $this->itemQuantity = $itemQuantity;
            $this->itemType = $itemType;
        }

        //item number property getter method
        public function getItemNumber(){
            return $this->itemNumber;
        }

        //item name property getter method
        public function getItemName(){
            return $this->itemName;
        }
        //item quantity property getter method
        public function getItemQuantity(){
            return $this->itemQuantity;
        }
        //item type property getter method
        public function getItemType(){
            return $this->itemType;
        }


        //Created the add item method, passing in the class properties
        public function addItem($dbc, $itemNumber, $itemName, $itemQuantity, $itemType, $admin_id){
            $errors = array();

            //Check if the item number field is empty
            if (empty($itemNumber)) {
                $errors[] = 'Enter the item number'; 
            } else {
                $itnum = $dbc->real_escape_string(trim($itemNumber)); 
            }

            //Check if the item name field is empty
            if (empty($itemName)) {
                $errors[] = 'Enter the name of the item.'; 
            } else {
                $itn = $dbc->real_escape_string(trim($itemName)); 
            }

            //Check if the quantity field is empty
            if (empty($itemQuantity)) {
                $errors[] = 'Enter the quantity.'; 
            } else {
                $itq = $dbc->real_escape_string(trim($itemQuantity)); 
            }

            //Check if the item type field is empty
            if (empty($itemType)) {
                $errors[] = 'Enter the item type.'; 
            } else {
                $itt = $dbc->real_escape_string(trim($itemType)); 
            }

            //Checks if the item already exists by querying the Item table using the item number
            if (empty($errors)) {
                $q = "SELECT item_id FROM items WHERE item_number='$itnum'";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'Item already exists' ; 
                } 
            }

            //Insert the values entered into the Item table
            if (empty($errors)) {
                $q = "INSERT INTO items (item_number, item_quantity, item_type, item_name, admin_id, date_added) VALUES ('$itnum', '$itq', '$itt', '$itn', '$admin_id', NOW())";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Added!</h1>
                        <p>You have added your item.</p>'; 
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
        
        public function modifyItem($itemNumber){
            
        }

        public function viewItems($dbc){
            $page_title = 'Items';
            //Queries the Item table for all items
            $q = "SELECT * from items";

            $r = $dbc->query($q);

            $num = $r->num_rows;

            if ($num > 0){
                echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
                <tr>
                    <th>Item number</th>
                    <th>Item name</th>
                    <th>Item type</th>
                    <th>Quantity</th>
                    <th>Date Added</th>
                    <th>Added by</th>
                </tr>";

                //Loops through the item rows, creating a table row
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    $a = "SELECT admin_name from admin WHERE admin_id = '{$row['admin_id']}' ";

                    $b = $dbc->query($a);

                    $row2 = mysqli_fetch_array($b, MYSQLI_ASSOC);

                    echo "<tr>
                            <td>" . $row["item_number"] . "</td>
                            <td>" . $row["item_name"] . "</td>
                            <td>" . $row["item_type"] . "</td>
                            <td>" . $row["item_quantity"] . "</td>
                            <td>" . $row["date_added"] . "</td>
                            <td>" . $row2["admin_name"] . "</td>
                        </tr>";
                }
                echo "</table>";
                
                $r->free_result();
            } else{
                echo "<p class='error'>You haven't added any staff</p>";
            }

        }

        public function deleteItem($itemNumber){
            return boolean;
        }

    }
?>