<?php
    include "User.php";

    //Created a Client class that extends the User class
    class Client extends User {
        private $corporate;

        //Setter method
        public function clientAccountDetails($name, $contactNumber, $address, $email, $role, $password, $corporate){
            $this->name = $name;
            $this->contactNumber = $contactNumber;
            $this->address = $address;
            $this->email = $email;
            $this->role = $role;
            $this->password = $password;
            $this->corporate = $corporate;
        }

        //email property getter method
        public function getEmail(){
            return $this->email;
        }

        //ROLE property getter method
        public function getRole(){
            return $this->role;
        }

        //password property getter method
        public function getPassword(){
            return $this->password;
        }

        //address property getter method
        public function getAddress(){
            return $this->address;
        }

        //contactNumber property getter method
        public function getContactNumber(){
            return $this->contactNumber;
        }

        //name property getter method
        public function getName(){
            return $this->name;
        }

        //Corporate property getter method
        public function getCorporate(){
            return $this->corporate;
        }

        //Overwrote the create account method from the User Class, passing in properties from the User class
        public function createAccount($dbc, $name, $contactNumber, $address, $email, $role, $password, $corporate){
            $errors = array();

            //Check if the username field is empty
            if (empty($name)) {
                $errors[] = 'Enter username.'; 
            } else {
                $n = $dbc->real_escape_string(trim($name)); 
            }

            //Check if the contact number field is empty
            if (empty($contactNumber)) {
                $errors[] = 'Enter your contact.'; 
            } else {
                $cn = $dbc->real_escape_string(trim($contactNumber)); 
            }

            //Check if the address field is empty
            if (empty($address)) {
                $errors[] = 'Enter your address.'; 
            } else {
                $a = $dbc->real_escape_string(trim($address)); 
            }
            
            //Check if the email field is empty
            if (empty($email)) {
                $errors[] = 'Enter your email.'; 
            } else {
                $e = $dbc->real_escape_string(trim($email));
            }

            $c = $dbc->real_escape_string(trim($corporate));

            //Check if the password field is empty, and if it matches with the comfirm password field
            if (!empty($_POST['pass1'])) {
                if ($_POST['pass1'] != $_POST['pass2']) { 
                        $errors[] = 'Passwords do not match.';
                }
                else {
                    $p = $dbc->real_escape_string(trim($password));
                }
            } else {
                $errors[] = 'Enter your password.';
            }
            
            //Queries the database to see if the user alrealdy exist using the entered email
            if (empty($errors)) {
                $q = "SELECT user_id FROM users WHERE user_email='$e'";
                $r = $dbc->query($q);
                $rowcount = $r->num_rows;
                if ($rowcount != 0){
                    $errors[] = 'Email address already registered. <a href="login.php">Login</a>' ; 
                }
            }

            //Insert the values entered into the Users table
            if (empty($errors)) {
                $q = "INSERT INTO users (username, contact_number, user_address, user_email, role, pass, reg_date, corporate) VALUES ('$n', '$cn', '$a', '$e', '$role', SHA1('$p'), NOW(), '$c')";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Registered!</h1>
                        <p>You are registered</p>
                        <p><a href="login.php">Login</a></p>'; 
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
    }
?>
