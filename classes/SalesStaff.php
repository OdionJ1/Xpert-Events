<?php
    include "User.php";

    //Created a SalesStaff class that extends the User class
    class SalesStaff extends User {
        
        public function viewStaff($dbc){
            $page_title = 'Sales staff';

            //Queries the Users table for all staffs
            $q = "SELECT * FROM users WHERE role = 'sales staff' OR role = 'sales manager' ORDER BY reg_date ASC";

            $r = $dbc->query($q);

            $num = $r->num_rows;
            
            if ($num > 0){
                echo "<p>There are currently $num registered staffs.</p>\n";
                echo "<table align='center' cellspacing='3' cellpadding='3' width='75%'>
                <tr>
                    <th>Staff name</th>
                    <th>Contact number</th>
                    <th>Staff address</th>
                    <th>Staff email</th>
                    <th>Staff role</th>
                    <th>Date registered</th>
                    <th>Registered by</th>

                </tr>";
                
                //Loops through the rows, creating a table row
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    //Queries the admin table for the name of admin that registered the staff
                    $a = "SELECT admin_name from admin WHERE admin_id = '{$row['admin_id']}' ";

                    $b = $dbc->query($a);

                    $row2 = mysqli_fetch_array($b, MYSQLI_ASSOC);
                    
                    echo "<tr>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["contact_number"] . "</td>
                            <td>" . $row["user_address"] . "</td>
                            <td>" . $row["user_email"] . "</td>
                            <td>" . $row["role"] . "</td>
                            <td>" . $row["reg_date"] . "</td>
                            <td>" . $row2['admin_name'] . "</td>
                        </tr>";
                }
                echo "</table>";
                
                $r->free_result();
            } else{
                echo "<p class='error'>You haven't added any staff</p>";
            }

        }
        
    }
?>