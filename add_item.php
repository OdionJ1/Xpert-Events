<?php
	session_start();
	//Redirect to login if an admin is not logged in
    if (!isset($_SESSION['admin_id'])) { 
        require ('login_tools.php'); 
        load();
    }
	require ('includes/connect_db.php');
    include('includes/admin_header.html');
    include('classes/Item.php');

	//Instantiated the Item Class
	$item = new Item();
    echo "<br /> ";

	//Called the view Items method
	$item->viewItems($dbc);

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Called the itemDetails setter method, passing in the form values
		$item->itemDetails($_POST['item_number'], $_POST['item_name'], $_POST['item_quantity'], $_POST['item_type']);
		//Called the add item method, passing in the item properties getter method
		$item->addItem($dbc, $item->getItemNumber(), $item->getItemName(), $item->getItemQuantity(), $item->getItemType(), $_SESSION['admin_id']);
	}
    
?>

<!-- Add item form -->
<!-- Display body section with sticky form. -->
<form action="add_item.php" method="post" class="form-signin" role="form">
	<h3 class="form-signin-heading">Add item</h3> 
	<input type="number" name="item_number" size="20" value="<?php if (isset($_POST['item_number'])) echo $_POST['item_number']; ?>" placeholder="item number">
	<input type="text" name="item_name" size="10" value="<?php if (isset($_POST['item_name'])) echo $_POST['item_name']; ?>" placeholder="Item name">
	<input type="number" name="item_quantity" size="10" value="<?php if (isset($_POST['item_quantity'])) echo $_POST['item_quantity']; ?>" placeholder="Quantity">
	<input type="text" name="item_type" size="50" value="<?php if (isset($_POST['item_type'])) echo $_POST['item_type']; ?>" placeholder="Item Type">
	<p><button class="btn btn-primary" name="submit" type="submit">Add</button></p>
</form>

<?php
    include('includes/footer.html');
?>