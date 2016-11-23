 <?php 
		// validating the data and then inserting into db
		include './database/listingFunctions.php';
		// Starting the session and checking if user_id is valid
		session_start();
		if (!isset($_SESSION["user"])){
			// Telling the user hes not logged in !
			echo '<script language="javascript">';
			echo 'alert("Please Login First!")';
			echo '</script>';
			
			echo "<script>setTimeout(\"location.href = './login.php';\",1);</script>";
		}
		if (!listingBelongsToUser($_SESSION['user'], $_GET['listing_id'])){
		   // Telling the user its not his listing
			echo '<script language="javascript">';
			echo 'alert("Not your Listing!!")';
			echo '</script>';
			
			echo "<script>setTimeout(\"location.href = './index.php';\",1);</script>";
		}
		else{
	?>
 <html>
 <head>
 	<title>GameExchange</title>

	<link rel="stylesheet" type="text/css" href="default.css">
	<link rel="stylesheet" type="text/css" href="lib/pure-min.css">
	<link rel="stylesheet" type="text/css" href="lib/grids-responsive-min.css">
	<link rel="stylesheet" type="text/css" href="styles/default.css">
 </head>
 <body>
	
	
     <div id="doverlay"></div>
	 <header>
         <div id="dmenu" class="dmenu pure-g">
			<?php require "scripts/createMenuBar.php"; createMenu($_SESSION); ?>
         </div>
     </header>
    
	<article>
		<form id="form2" method="post" action="">
			<h1>Modify Your Listing</h1>
				<div class="pure-u-1-2 dpanel">
					<div>
						<legend>Update Your Listing:</legend>
						<table align="center">
							<tr id="dtable-item1">
								<td class="labelcell" style="margin-bottom: 5%">Listing Title:</td>
								<td class="inputcell2">
								<input name="productName" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td class="labelcell">Description:</td>
								<td class="inputcell2">
								<input name="description" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td class="labelcell">Category:</td>
								<td class="inputcell2">
								<select name="category">
								  <option value="2">Cd's (Games)</option>
								  <option value="3">Consoles</option>
								  <option value="4">Peripherals </option>
								  <option value="1">Trading Cards</option>
								</select>
								</td>
							</tr>
							<tr id="dtable-item2">
								<td class="labelcell">Listing Price:</td>
								<td class="inputcell2">
								<input name="price" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td class="labelcell">Item Condition:</td>
								<td class="inputcell2">
								<select name="condition">
								  <option value="GOOD">GOOD</option>
								  <option value="FAIR">FAIR</option>
								  <option value="BAD">BAD</option>
								  <option value="TERRIBLE">TERRIBLE</option>
								</select>
								</td>
							</tr>
						
						</table>
					</div>
				</div>
			<p id="submitbutton">
				<input type="submit" name="update"  value="Update" />
				<input type="submit" name="delete" value="Delete" />
			</p>
		</form>
	</article>

        <footer>
         <div id="footer" class="dfooter">
             Copyright 2016 GameExchange
         </div>
     </footer>
     <script src="lib/jquery.js"></script>
     <script defer="defer" src="scripts/menu.js"></script>
     <script defer="defer" src="scripts/footer.js"></script>
	 
	 <?php				
		
		
		// If user updates
		if(isset($_POST["update"]))
		{
			// DO some SQL INSERT to add product to DATABASE!
			updateListing($_POST, $_GET['listing_id']);
			
			// Alerting user that modfification was successful!
			echo '<script language="javascript">';
			echo 'alert("Lisiting Mofified!")';
			echo '</script>';
			
			$id = getPostedListId($_POST['productName']);
			echo "<script>setTimeout(\"location.href = './product.php?id=".$id."';\",1000);</script>";
		}
		
		//if The user deletes Listing 
		if(isset($_POST["delete"]))
		{
			// DO some SQL INSERT to add product to DATABASE!
			deleteUserListing($_GET['listing_id']);
			
			// Alerting user that modfification was successful!
			echo '<script language="javascript">';
			echo 'alert("Lisiting Deleted!")';
			echo '</script>';
			echo "<script>setTimeout(\"location.href = './index.php';\",1000);</script>";
		}
	?>

 </body>
 </html>
		<?php }?>