<?php
    //Created a Package class
    class Package {
        private $packageName;
        private $eventType;
        private $numberOfGuest;
        private $itemDetails;
        private $packagePrice;
        private $packageDescription;
        private $packageDiscount;

        //Setter method
        public function packageDetails($packageName, $eventType, $numberOfGuest, $packagePrice, $packageDescription, $packageDiscount){
            $this->packageName = $packageName;
            $this->eventType = $eventType;
            $this->numberOfGuest = $numberOfGuest;
            $this->packagePrice = $packagePrice;
            $this->packageDescription = $packageDescription;
            $this->packageDiscount = $packageDiscount;
        }

        //package name getter method
        public function getPackageName(){
            return $this->packageName; 
        }

        //event type getter method
        public function getEventType(){
            return $this->eventType; 
        }

        //number of guest getter method
        public function getNumberOfGuest(){
            return $this->numberOfGuest; 
        }

        //package price getter method
        public function getPackagePrice(){
            return $this->packagePrice; 
        }

        //package description getter method
        public function getPackageDescription(){
            return $this->packageDescription; 
        }

        //package discount getter method
        public function getPackageDiscount(){
            return $this->packageDiscount; 
        }
        
        //Created the add Package class, passing in the Package class properties
        public function addPackage($dbc, $packageName, $eventType, $numberOfGuest, $packagePrice, $packageDescription, $packageDiscount){
            $errors = array();

            //Check if the package name field is empty
            if (empty($packageName)) {
                $errors[] = 'Enter the package name'; 
            } else {
                $pn = $dbc->real_escape_string(trim($packageName));
            }

            //Check if the event Type field is empty
            if (empty($eventType)) {
                $errors[] = 'Enter the event type.'; 
            } else {
                $et = $dbc->real_escape_string(trim($eventType));
            }

            //Check if the number of guest field is empty
            if (empty($numberOfGuest)) {
                $errors[] = 'Enter guest range.'; 
            } else {
                $nog = $dbc->real_escape_string(trim($numberOfGuest)); 
            }

            //Check if the package price field is empty
            if (empty($packagePrice)) {
                $errors[] = 'Enter the price range.'; 
            } else {
                $pp = $dbc->real_escape_string(trim($packagePrice));
            }

            //Check if the package description field is empty
            if (empty($packageDescription)) {
                $errors[] = 'Enter package description.'; 
            } else {
                $pdes = $dbc->real_escape_string(trim($packageDescription));
            }

            $pd = $dbc->real_escape_string(trim($packageDiscount));

            //Check if the package already exists by querying the Package table using the package name
            if (empty($errors)) {
                $q = "SELECT package_id FROM packages WHERE package_name='$pn'";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'Package already exists'; 
                }
            }
            
            //Insert the values entered into the Packages table
            if (empty($errors)) {
                //Checks if the staff adding this package is a sales manager, if so, he/she can add a discount, if not, package discount will be set to null
                if($_SESSION['staff_role'] == 'sales manager' && $packageDiscount != ''){
                    $q = "INSERT INTO packages(package_name, event_type, number_of_guests, package_price, package_description, package_discount) VALUES ('$pn', '$et', '$nog', '$pp', '$pdes', '$pd')";
                } else {
                    $q = "INSERT INTO packages(package_name, event_type, number_of_guests, package_price, package_description) VALUES ('$pn', '$et', '$nog', '$pp', '$pdes')";
                }
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Added!</h1>
                        <p>You have added a package.</p>'; 
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

        //Created a modify package method to admend a package, passing in the Package class properties
        public function modifyPackage($dbc, $packageid, $packageName, $eventType, $numberOfGuest, $packagePrice, $packageDescription, $packageDiscount){
            $errors = array();

            //Check if the package name field is empty
            if (empty($packageName)) {
                $errors[] = 'Enter the package name'; 
            } else {
                $pn = $dbc->real_escape_string(trim($packageName)); 
            }

            //Check if the event type field is empty
            if (empty($eventType)) {
                $errors[] = 'Enter the event type.'; 
            } else {
                $et = $dbc->real_escape_string(trim($eventType)); 
            }

            //Check if the number of guest field is empty
            if (empty($numberOfGuest)) {
                $errors[] = 'Enter guest range.'; 
            } else {
                $nog = $dbc->real_escape_string(trim($numberOfGuest)); 
            }

            //Check if the package price field is empty
            if (empty($packagePrice)) {
                $errors[] = 'Enter the price range.'; 
            } else {
                $pp = $dbc->real_escape_string(trim($packagePrice)); 
            }

            //Check if the package description field is empty
            if (empty($packageDescription)) {
                $errors[] = 'Enter package description.'; 
            } else {
                $pdes = $dbc->real_escape_string(trim($packageDescription)); 
            }

            $pd = $dbc->real_escape_string(trim($packageDiscount));
            
            //Updates the Package with the values entered
            if (empty($errors)) {
                //Checks if the staff updating this Package is a sales manager, if so, he/she can amend the package discount, if not, the package discount will be left the same
                if($_SESSION['staff_role'] == 'sales manager' && $packageDiscount != ''){
                    $q = "UPDATE packages SET package_name = '$pn', event_type = '$et', number_of_guests = '$nog', package_price = '$pp', package_description = '$pdes', package_discount = '$pd' WHERE package_id='$packageid' ";
                } else if($_SESSION['staff_role'] == 'sales staff'){
                    $q = "UPDATE packages SET package_name = '$pn', event_type = '$et', number_of_guests = '$nog', package_price = '$pp', package_description = '$pdes' WHERE package_id='$packageid'";
                } else {
                    //If the package discount was not set, it will remain so
                    $q = "UPDATE packages SET package_name = '$pn', event_type = '$et', number_of_guests = '$nog', package_price = '$pp', package_description = '$pdes', package_discount = null WHERE package_id='$packageid' ";
                }
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Package updated</h1>
                        <p><a href="add_packages">View packages</a></p>'; 
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

        public function viewPackages($dbc){
            $page_title = 'Packages';

            //Queries the packages table for a packages
            $q = "SELECT * from packages";

            $r = $dbc->query($q);

            $num = $r->num_rows;

            if ($num > 0){
                echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
                <tr>
                    <th>Package name</th>
                    <th>Event Type</th>
                    <th>Number of guests</th>
                    <th>Price (£)</th>
                    <th>description</th>
                    <th>Discount</th>
                    <th>items</th>
                </tr>";

                //Loops through the packages row, creating a table row
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<tr>
                            <td>" . $row["package_name"] . "</td>
                            <td>" . $row["event_type"] . "</td>
                            <td>" . $row["number_of_guests"] . "</td>
                            <td>" . $row["package_price"] . "</td>
                            <td>" . $row["package_description"] . "</td>
                            <td>" . $row["package_discount"] . "%</td>
                            <td><a href='package_items.php?pid=$row[package_id]&pn=$row[package_name]'>see items</a></td>
                        </tr>";
                }
                echo "</table>";

                $r->free_result();
            } else{
                echo "<p class='error'>There are no packages</p>";
            }

        }

        //Created the add item to package method, passing in the packageId, itemId and quantity
        public function addItemToPackage($dbc, $packageId, $itemId, $quantity){
            $errors = array();
            
            //Checks if the quantity field is empty
            if (empty($quantity)) {
                $errors[] = 'Enter item quantity.'; 
            } else {
                $itq = $dbc->real_escape_string(trim($quantity)); 
            }

            //Queries the database to see if the item has alrealdy be added to the package using the packageId and the itemId passed in
            if (empty($errors)) {
                $q = "SELECT package_id FROM package_items WHERE package_id = '$packageId' AND item_id = '$itemId' ";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'This item has already been added to this package'; 
                }
            }

            //Inserts the values passed in, into the package_items table
            if (empty($errors)) {
                $q = "INSERT INTO package_items(package_id, item_id, item_quantity) VALUES ('$packageId', '$itemId', '$itq')";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Added!</h1>
                        <p>You have an item.</p>
                        <p><a href="add_packages">View packages</a></p>'; 
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

        //Created the book package method, passing in the clientId and the packageId
        public function bookPackage($dbc, $clientId, $packageId){
            $errors = array();

            //Checks if this package has already been booked by this client by querying the database using the packageId and clientId
            if (empty($errors)) {
                $q = "SELECT * FROM booking WHERE package_id = '$packageId' AND client_id = '$clientId' ";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'You have already booked this package'; 
                }
            }

            //Inserts the values passed in to the booking table
            if (empty($errors)) {
                $q = "INSERT INTO booking(package_id, client_id) VALUES ('$packageId', '$clientId')";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Booked!</h1>
                        <p>You have booked a package.</p>
                        <p>A staff will contact you within 24hrs</p>'; 
                }
                exit();
            } else {
                echo '<h1>Error!</h1>
                    <p id="err_msg">The following error(s) occurred:<br>';
                foreach ($errors as $msg) {
                    echo " - $msg<br>";
                }
                $dbc->close();
            }
        }

        //Created a view bookings method
        public function viewBookings($dbc){
            //Queries the booking table for all bookings
            $q = "SELECT * from booking";

            $r = $dbc->query($q);

            $num = $r->num_rows;

            if ($num > 0){
                echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
                <tr>
                    <th>Package name</th>
                    <th>Package discount</th>
                    <th>Event Type</th>
                    <th>Package Price (£)</th>
                    <th>Number of guests</th>
                    <th>Client name</th>
                    <th>Client PNo</th>
                    <th>Client Email</th>
                </tr>";

                //Loops through the Booking table rows, creating a table row
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    //Queries the Users table for all information about a user who's user_id is equals to the client_id in the Booking table row
                    //in order to get the name of the client that made the booking
                    $c = "SELECT * FROM users WHERE user_id = '{$row['client_id']}'";
                    $cc = $dbc->query($c);

                    $row2=mysqli_fetch_array($cc, MYSQLI_ASSOC);

                    //Queries the Packages table for all information about the package whose package_id is equals to the package_id in the booking row
                    //in order to get the name of the package that was booked
                    $p = "SELECT * FROM packages WHERE package_id = '{$row['package_id']}'";
                    $pp = $dbc->query($p);

                    $row3=mysqli_fetch_array($pp, MYSQLI_ASSOC);

                    echo "<tr>
                            <td>" . $row3["package_name"] . "</td>
                            <td>" . $row3["package_discount"] . "%</td>
                            <td>" . $row3["event_type"] . "</td>
                            <td>" . $row3["package_price"] . "</td>
                            <td>" . $row3["number_of_guests"] . "</td>
                            <td>" . $row2["username"] . "</td>
                            <td>" . $row2["contact_number"] . "</td>
                            <td>" . $row2["user_email"] . "</td>
                        </tr>";
                }
                echo "</table>";

                $r->free_result();
            } else{
                echo "<p class='error'>There are no bookings</p>";
            }
        }

        public function deletePackage(){
            return boolean;
        }
    }
?>