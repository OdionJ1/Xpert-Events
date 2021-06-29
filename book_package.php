<?php
    session_start();
    //Redirect to login if a client is not logged in
    if (!isset($_SESSION['client_id'])) { 
        require ('login_tools.php'); 
        load('login.php');
    }
    require ('includes/connect_db.php');
    include('classes/Package.php');
    include('includes/client_header.html');
    echo '<br />';
    //Instantiated the package class
    $package = new Package();

    //Called the view packages method
    $package->viewPackages($dbc);
?>

<?php
    $q = "SELECT * from packages";

    $r = $dbc->query($q);
?>

<?php
    //Runs if book package form is submitted 
    if(isset($_POST['submit1'])){
        if(!empty($_POST['package'])) {

            $selected = $_POST['package'];
            
            $a = "SELECT * from packages WHERE package_name = '$selected' ";

            $b = $dbc->query($a);

            $row2 = mysqli_fetch_array($b, MYSQLI_ASSOC);

            //Called the book package method
            $package->bookPackage($dbc, $_SESSION['client_id'], $row2['package_id']);
        } else {
            echo 'Please select the value.';
        }
    }
?>

<!-- Book package form -->
<form action="book_package.php" method="post">
    <h5 class="form-signin-heading" >Book a Package</h5>
    <span class="select-span">
        <select class="select" name="package" id="package">
            <option class="select" value="" selected disabled hidden>select a package</option>
            <?php
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row["package_name"] . "'>" . $row["package_name"] ."</option>";
                }
            ?>
        </select>
    </span>
    <input type="submit" name="submit1" value="Book package" />
</form>