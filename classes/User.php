<?php
    //Created the User class
    class User {
        private $name;
        private $contactNumber;
        private $address;
        private $password;
        private $email;
        private $role;

        //Setter method
        public function staffAccountDetails($name, $contactNumber, $address, $email, $role, $password){
            $this->name = $name;
            $this->contactNumber = $contactNumber;
            $this->address = $address;
            $this->email = $email;
            $this->role = $role;
            $this->password = $password;
        }

        //Setter method
        //Assigns the values of the login information to the email and password properties
        public function loginDetails($email, $password){
            $this->email = $email;
            $this->password = $password;
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

        //Created the create account method, passing in the class properties
        public function createAccount($dbc, $name, $contactNumber, $address, $email, $role, $password, $admin_id){
            $errors = array();

            //Check if the username field is empty
            if (empty($name)) {
                $errors[] = 'Enter username.'; 
            } else {
                $n = $dbc->real_escape_string(trim($name)); 
            }

            //Check if the contact number field is empty
            if (empty($contactNumber)) {
                $errors[] = 'Enter user contact.'; 
            } else {
                $cn = $dbc->real_escape_string(trim($contactNumber)); 
            }

            //Check if the address field is empty
            if (empty($address)) {
                $errors[] = 'Enter user address.'; 
            } else {
                $a = $dbc->real_escape_string(trim($address)); 
            }

            //Check if the email field is empty
            if (empty($email)) {
                $errors[] = 'Enter user email.'; 
            } else {
                $e = $dbc->real_escape_string(trim($email)); 
            }

            if (empty($role)) {
                $errors[] = 'Enter user role.'; 
            } else {
                $sp = $dbc->real_escape_string(trim($role)); 
            }

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
                $q = "INSERT INTO users (username, contact_number, user_address, user_email, role, pass, reg_date, admin_id) VALUES ('$n', '$cn', '$a', '$e', '$sp', SHA1('$p'), NOW(), $admin_id)";
                $r = $dbc->query($q);
                if ($r) { 
                    echo '<h1>Registered!</h1>
                        <p>You have registered your user.</p>
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

        //Created the login method passing in the email and password
        public function login($dbc, $email = '', $password = ''){
            $errors = array();

            //Checks if the email field is empty
            if (empty($email)) {
                $errors[] = 'Enter your email address.'; 
            } else {
                $e = $dbc->real_escape_string(trim($email));
            }

            //Checks if the password field is empty
            if (empty($password)) {
                $errors[] = 'Enter your password.'; 
            } else {
                $p = $dbc->real_escape_string(trim($password));
            }
            
            if (empty($errors)) {
                //Queries the admin table using the email and password passed in, if an admin with those details exists, he/she is logged in, 
                $a = "SELECT admin_id, admin_name as name FROM admin WHERE admin_email='$e' AND admin_pass=SHA1('$p')";  
                $r = $dbc->query($a);
                $rows = $r->num_rows;

                if ($rows == 1) {                               
                    $row = $r->fetch_array(MYSQLI_ASSOC);
                    return array(true, $row);
                }

                //Queries the users table using the email and password passed in, if a user with those details exists, he/she is logged in, 
                $ss = "SELECT user_id, username, contact_number, user_address, user_email, role FROM users WHERE user_email='$e' AND pass=SHA1('$p')";  
                $str = $dbc->query($ss);
                $userRow = $str->num_rows;
                
                if ($userRow == 1) {                               
                    $row = $str->fetch_array(MYSQLI_ASSOC);
                    return array(true, $row);
                }

                $errors[] = 'Email address and password not found.';
                return array(false, $errors); 
            }
        }

        //Created the logout method
        public function logout(){
            session_start();
            
            include ('includes/header.html');

            $_SESSION = array();
            session_destroy();

            echo "<h1>Goodbye!</h1>
            <p>You are now logged out.</p>
            <p>See you soon. Ta-ra! Adiós!</p>";

            include ('includes/footer.html');
        }

        //Created the make Enquiry method
        public function makeEnquiry($name, $email, $comments, $phoneNumber){
            error_reporting(0);

            //if the form is filled completely, the enquire is sent to the staff email
            if(!empty($name) && !empty($email) && !empty($comments) && !empty($phoneNumber)){
                $body = "Name: {$name}\n\nComments: {$comments}\n\nPhone Number: {$phoneNumber}";

                $body = wordwrap($body, 70);

                mail('c0048678@my.shu.ac.uk', 'Contact Form Submission', $body, "From: {$email}");

                echo '<p><em>Thank you for contacting Xpert Events. We will respond to your enquiry within 24 hours.</em></p>';

                $_POST = array();
            } else {
                echo '<p style="font-weight: bold;" color: red> Please fill out the form completely </p>';
            }
        }
    }
?>