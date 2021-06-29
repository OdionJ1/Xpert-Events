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
    //Instantiated the Package class
    $package = new Package();

    //Called the view packages method
    $package->viewPackages($dbc);

    if(isset($_POST['submit'])){
        //Called the package details setter method, passing in the form values
        $package->packageDetails($_POST['package_name'], $_POST['event_type'], $_POST['number_of_guest'], $_POST['package_price'], $_POST['package_description'], $_POST['package_discount']);
        //Called the add package method
		$package->addPackage($dbc, $package->getPackageName(), $package->getEventType(), $package->getNumberOfGuest(), $package->getPackagePrice(), $package->getPackageDescription(), $package->getPackageDiscount());
	}
?>

<!-- Add package form -->
<form action="add_packages.php" method="post" class="form-signin" role="form">
    <h4 class="form-signin-heading">Add Package</h4> 
    <input type="text" name="package_name" size="20" value="<?php if (isset($_POST['package_name'])) echo $_POST['package_name']; ?>" placeholder="Package name">
    <input type="text" name="event_type" size="20" value="<?php if (isset($_POST['event_type'])) echo $_POST['event_type']; ?>" placeholder="Event type">
    <input type="text" name="number_of_guest" size="20" value="<?php if (isset($_POST['number_of_guest'])) echo $_POST['number_of_guest']; ?>" placeholder="Guest range">
    <input type="text" name="package_price" size="30" value="<?php if (isset($_POST['package_price'])) echo $_POST['package_price']; ?>" placeholder="Price range">
    <input type="text" name="package_description" size="30" value="<?php if (isset($_POST['package_description'])) echo $_POST['package_description']; ?>" placeholder="Package description">
    <br />
    <p><em>You can only add discount to package if you are a sales manager</em></p>
    <input type="number" name="package_discount" size="50" value="<?php if (isset($_POST['package_discount'])) echo $_POST['package_discount']; ?>" placeholder="Package discount">
    <p><button class="btn btn-primary" name="submit" type="submit">Add</button></p>
</form>

<?php
    $q = "SELECT * from packages";

    $r = $dbc->query($q);

    $it = "SELECT * from items";

    $i = $dbc->query($it);
?>

<?php
    //Runs if the add item to package form is submitted
    if(isset($_POST['submit2'])){
        if(!empty($_POST['package']) && !empty($_POST['item'])) {

            //Assigned the form values to variables
            $selectedPackage = $_POST['package'];
            $selectedItem = $_POST['item'];

            //Called the add item to package method
            $package->addItemToPackage($dbc, $selectedPackage, $selectedItem, $_POST['item_quantity']);

        } else {
            echo 'Please select the value.';
        }
    }
?>


<!-- Add item to package form -->
<form action="add_packages.php" class="form-signin" method="post">
    <h4 class="form-signin-heading">Add item to package</h4> 
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
    <span class="select-span">
        <select class="select" name="item" id="item">
            <option class="select" value="" selected disabled hidden>select an item</option>
            <?php
                while ($row2=mysqli_fetch_array($i, MYSQLI_ASSOC)){
                    echo "<option class='option' value='" . $row2["item_id"] . "'>" . $row2["item_name"] ."</option>";
                }
            ?>
        </select>
    </span>
    <input type="number" name="item_quantity" size="20" value="<?php if (isset($_POST['item_quantity'])) echo $_POST['item_quantity']; ?>" placeholder="Quantity">
    <input type="submit" name="submit2" value="Add item package" />
</form>

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

