<?php
session_start();
//Redirect to login if a staff is not logged in
if (!isset($_SESSION['staff_id'])) { 
    require ('login_tools.php'); 
    load('login.php');
}
    require ('includes/connect_db.php');
    include('classes/Package.php');
    include('includes/staff_header.html');
    echo '<br />';
    //Instantiated the Package Class
    $package = new Package();

    if(isset($_POST['submit'])){
        //Called the modify package method
		$package->modifyPackage($dbc, $_POST['package_id'], $_POST['package_name'], $_POST['event_type'], $_POST['number_of_guest'], $_POST['package_price'], $_POST['package_description'], $_POST['package_discount']);
	}

?>

<?php
    //Runs if a package is selected and submited
    if(isset($_POST['submit1'])){
        if(!empty($_POST['package'])) {

            $selected = $_POST['package'];
            
            $a = "SELECT * from packages WHERE package_id = '$selected' ";

            $b = $dbc->query($a);

            $row2 = mysqli_fetch_array($b, MYSQLI_ASSOC);

            //Assigns the values from the selected event to the form
            $_POST['package_id'] = $row2['package_id'];
            $_POST['package_name'] = $row2['package_name'];
            $_POST['event_type'] = $row2['event_type'];
            $_POST['number_of_guest'] = $row2['number_of_guests'];
            $_POST['package_price'] = $row2['package_price'];
            $_POST['package_description'] = $row2['package_description'];
            $_POST['package_discount'] = $row2['package_discount'];

        } else {
            echo 'Please select the value.';
        }
    }
?>

<?php
    $q = "SELECT * from packages";

    $r = $dbc->query($q);
?>
<!-- Select Package form -->
<form action="amend_package.php" method="post">
    <span class="select-span">
        <select class="select" name="package" id="package">
            <option class="select" value="" selected disabled hidden>select a package</option>
            <?php
                while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row["package_id"] . "'>" . $row["package_name"] ."</option>";
                }
            ?>
        </select>
    </span>
    <input type="submit" name="submit1" value="Amend package" />
</form>

<!-- Amend package form -->
<form action="amend_package.php" method="post" class="form-signin" role="form">
    <h4 class="form-signin-heading">Amend Package</h4> 
    <input type="number" name="package_id" size="20" value="<?php if (isset($_POST['package_id'])) echo $_POST['package_id']; ?>" placeholder="Package id" readonly>
    <input type="text" name="package_name" size="20" value="<?php if (isset($_POST['package_name'])) echo $_POST['package_name']; ?>" placeholder="Package name">
    <input type="text" name="event_type" size="20" value="<?php if (isset($_POST['event_type'])) echo $_POST['event_type']; ?>" placeholder="Event type">
    <input type="text" name="number_of_guest" size="20" value="<?php if (isset($_POST['number_of_guest'])) echo $_POST['number_of_guest']; ?>" placeholder="Guest range">
    <input type="text" name="package_price" size="30" value="<?php if (isset($_POST['package_price'])) echo $_POST['package_price']; ?>" placeholder="Price range">
    <input type="text" name="package_description" size="30" value="<?php if (isset($_POST['package_description'])) echo $_POST['package_description']; ?>" placeholder="Package description">
    <br />
    <p><em>You can only add discount to package if you are a sales manager</em></p>
    <input type="number" name="package_discount" size="50" value="<?php if (isset($_POST['package_discount'])) echo $_POST['package_discount']; ?>" placeholder="Package discount">
    <p><button class="btn btn-primary" name="submit" type="submit">Amend</button></p>
</form>


<br />
<br />
<br />
<?php include('includes/footer.html') ?>

<!-- css -->
<style>
    .select{
        background-color: grey;
    }
    .option{
        background-color: whitesmoke;
    }
</style>
