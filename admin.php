<?php
	// Starting the session!
	session_start();
	// validating the data and then inserting into db
	include './database/adminFunctions.php';
	include './validation/validation.php';
	
	// Checking if user is admin or not!
	if (!isAdmin($_SESSION)){
		echo '<script language="javascript">';
		echo 'alert("NOT admin!");';
		echo '</script>';
		
		echo "<script>setTimeout(\"location.href = './index.php';\",0);</script>";
		exit();	
	}
	// If user submits, then validate user input!
	if(isset($_POST["userStatus"]) && adminUserVal($_POST)){
		// DO some SQL INSERT to add product to DATABASE!
		updateUserStatus($_POST);
		// Alerting user that change was successful!
		echo '<script language="javascript">';
		echo 'alert("User Status Changed!");';
		echo '</script>';
		
	}
	
	//Checking if the listing button is pressed and if it exists
	if(isset($_POST["listingStatus"]) && adminListVal($_POST)){
		// DO some SQL INSERT to add product to DATABASE!
		updateListingStatus($_POST);
		// Alerting user that change was successful!
		echo '<script language="javascript">';
		echo 'alert("Listing Status Changed!");';
		echo '</script>';
	}
	
	// Checking if ticket button is pressed and if it exists
	if(isset($_POST["ticketStatus"]) && adminTicketVal($_POST)){
		// DO some SQL INSERT to add product to DATABASE!
		updateTicketStatus($_POST);
		// Alerting user that change was successful!
		echo '<script language="javascript">';
		echo 'alert("Ticket Status Changed!");';
		echo '</script>';
	}
 ?>
<html>
<head>
	<title>GameExchange</title>
  
	<link rel="stylesheet" type="text/css" href="lib/pure-min.css">
	<link rel="stylesheet" type="text/css" href="lib/grids-responsive-min.css">
	<link rel="stylesheet" type="text/css" href="styles/default.css">
</head>
<body>

    <div id="doverlay"></div>
     
	<header>
        <div id="dmenu" class="dmenu pure-g">
			<?php  require "scripts/createMenuBar.php"; createMenu($_SESSION); ?>
        </div>
    </header>
    
	<article>
		<form method="post" action="" >
			<h1>Manage User Accounts</h1>
				<div class="pure-u-1-2 dpanel">
					<div>
						<legend text-align="center">Select What To Do With User:</legend>
						<table align="center">
							<tr >
								<td style="color: #e5edb8" style="margin-bottom: 5%">Username: </td>
								<td class="inputcell2">
								<?php
									require "database/databaseTools.php";
									$DBH = loginToDatabase();
									
									//Get a list of all usernames and associated IDs
									$STH = $DBH->query("SELECT user_id, username FROM user");
									//Start building the select
									echo "	<select name='user_id'>
											<option>- Select a User -</option>";
									while($row = $STH->fetch()){
										echo "<option value='" . $row["user_id"] . "'>" . $row["username"] . "</option>";
									}
									echo "</select>";
								?>
								</td>
							</tr>
							<tr >
								<td style="color: #e5edb8">User Status:</td>
								<td class="inputcell2">
								<select name="uStatus">
									<option>- Select a Status -</option>
									<option value="5">BAN</option>
									<option value="6">LOCK</option>
									<option value="12">USER</option>
									<option value="13">ADMIN</option>
								</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
			<p id="submitbutton">
				<input type="submit" name="userStatus" class="userStatus" value="Confirm" />
			</p>
		</form>
	</article>
	
	<article>
		<form method="post" action="">
			<h1>Manage Listings</h1>
				<div class="pure-u-1-2 dpanel">
					<div>
						<legend>Select A Listing To Remove:</legend>
						<table align="center">
							<tr >
								<td style="color: #e5edb8" class="labelcell">Listing Id:</td>
								<td class="inputcell2">
								<?php
									//Get a list of all usernames and associated IDs
									$STH = $DBH->query("SELECT listing_id, product_name FROM listing");
									//Start building the select
									echo "	<select name='listId'>
											<option>- Select a Listing -</option>";
									while($row = $STH->fetch()){
										echo "<option value='" . $row["listing_id"] . "'>" . $row["product_name"] . "</option>";
									}
									echo "</select>";
								?>
								</td>
							</tr>
							<tr >
								<td style="color: #e5edb8" class="labelcell">Status:</td>
								<td class="inputcell2">
								<select name="lStatus">
									<option>- Select a Status -</option>
									<option value="7">AVAILABLE</option>
									<option value="8">PURCHASED</option>
									<option value="6">LOCKED</option>
								</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
			<p>
				<input type="submit" name="listingStatus" value="Confirm" />
			</p>
		</form>
	</article>
	
	<article>
		<form method="post" action="">
			<h1>Manage Tickets</h1>
				<div class="pure-u-1-2 dpanel">
					<div>
						<legend >View Tickets</legend>
						<?php 
							displayAllTickets();
						?>
					</div>
					<br/>
					<legend>Change Ticket Status</legend>
					<table align="center">
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell">Ticket ID:</td>
							<td class="inputcell2">
							<?php
								//Get a list of all usernames and associated IDs
								$STH = $DBH->query("SELECT ticket_id FROM ticket");
								//Start building the select
								echo "	<select name='ticketId'>
										<option>- Select a Ticket -</option>";
								while($row = $STH->fetch()){
									echo "<option value='" . $row["ticket_id"] . "'>" . $row["ticket_id"] . "</option>";
								}
								echo "</select>";
							?>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td  style="color: #e5edb8" class="labelcell">Status:</td>
							<td class="inputcell2">
							<select name="tStatus">
								<option>- Select a Status -</option>
								<option value="1">OPEN</option>
								<option value="2">CLOSED</option>
								<option value="3">PENDING</option>
							</select>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell">Verified By:</td>
							<td class="inputcell2">
							<input type="text" name="verifiedBy" >
							</td>
						</tr>
					</table>
				</div>
			<p>
				<input type="submit" name="ticketStatus" class="ticketStatus" value="Confirm" />
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
</body>
</html>