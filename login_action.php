<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        require ('includes/connect_db.php');
        require ('login_tools.php');
        include ('classes/User.php');

        //Instantiated the user class
        $user = new User();

        //Called logindetails setter method
        $user->loginDetails($_POST['email'], $_POST['pass']);

        list($check, $data) = $user->login($dbc, $user->getEmail(), $user->getPassword());
        
        if($check){
            session_start();
            //Admin id begins with 10001,
            if($data['admin_id'] >= 10001){
                $_SESSION['admin_id'] = $data['admin_id'];
                $_SESSION['name'] = $data['name'];
                load('admin_home.php');
            }

            //Create session for sales staff
            if($data['role'] != 'customer'){
                $_SESSION['staff_id'] = $data['user_id'];
                $_SESSION['staff_name'] = $data['username'];
                $_SESSION['contact_number'] = $data['contact_number'];
                $_SESSION['staff_address'] = $data['user_address'];
                $_SESSION['staff_email'] = $data['user_email'];
                $_SESSION['staff_role'] = $data['role'];
                load('staff_home.php');
            }

            //Create session for client
            if($data['role'] == 'customer'){
                $_SESSION['client_id'] = $data['user_id'];
                $_SESSION['client_name'] = $data['username'];
                $_SESSION['contact_number'] = $data['contact_number'];
                $_SESSION['client_address'] = $data['user_address'];
                $_SESSION['client_email'] = $data['user_email'];
                $_SESSION['client_role'] = $data['role'];
                load('client_home.php');
            }
        } else {
            $errors = $data;
        }

        $dbc->close();
    }

    include('login.php');
?>