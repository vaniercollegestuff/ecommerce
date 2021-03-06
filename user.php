 <html>
 <head>
 	<title>GameExchange</title>

	<link rel="stylesheet" type="text/css" href="lib/pure-min.css">
	<link rel="stylesheet" type="text/css" href="lib/grids-responsive-min.css">
	<link rel="stylesheet" type="text/css" href="styles/default.css">
 </head>
 <body>
	<?php 
		// Starting Session
		session_Start();
		//add the includes
		include './database/userFunctions.php';
		include './validation/validation.php';	
		
		// Validating URL
		userDisplayVal($_GET, $_SESSION);
		// If user submits, then validate user input!
		if(isset($_POST["upvote"]))
		{
			// DO some SQL add repuation for user
			upvoteUser($_SESSION, $_GET);
			// Alerting user that change was successful!
			echo '<script language="javascript">';
			echo 'alert("Upvoted user!");';
			echo '</script>';
			
		}
		
		// If user submits, then validate user input!
		if(isset($_POST["downvote"]))
		{
			// DO some SQL INSERT to add product to DATABASE!
			downvoteUser($_SESSION, $_GET);
			// Alerting user that change was successful!
			echo '<script language="javascript">';
			echo 'alert("downvoted user!");';
			echo '</script>';
			
			
		}
		
		// if modify button is pressed then modify page
		if(isset($_POST["modify"]))
		{
			// DO some SQL INSERT to add product to DATABASE!
			if (modifyInformation($_POST, $_SESSION['user'])){
				
			// Alerting user that change was successful!
			echo '<script language="javascript">';
			echo 'alert("Information Modified!!");';
			echo '</script>';
			}
		}
	?>
     <div id="doverlay"></div>
	 <header>
         <div id="dmenu" class="dmenu pure-g">
			<?php require "scripts/createMenuBar.php"; createMenu($_SESSION); ?>
         </div>
     </header>
    
	<article>
	
		<h1>User Profile</h1>
			<div class="pure-u-1-2 dpanel">
				<div>
					<legend text-align="center">User Information</legend>
					<table align="center">
						<tr id="dtable-item1">
							<td style="color: #e5edb8"class="labelcell" style="margin-bottom: 5%"><h2>Username:</h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2"><h2>
							<?php getUsername($_GET['userp']);?></h2>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>First Name:></h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2">
							<h2><?php getFname($_GET['userp']); ?> </h2>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>Last Name:</h2> </td>
							<td> </td>
							<td style="color: #e5edb8"class="inputcell2">
							<h2><?php getLname($_GET['userp']); ?></h2>
							
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>Email:</h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2">
							<h2><?php getEmail($_GET['userp']); ?></h2>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>Reputation:</h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2">
							<h2><?php getRepuation($_GET['userp']); ?></h2>
							
							</td>
						</tr>
						
						<?php 
							// Checking if the current user is owner of profile!
							if ($_GET['userp']  == $_SESSION['user']){
						?>
						
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>Address:</h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2">
							<h2><?php getAddress($_GET['userp']); ?></h2>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>Phone Number:</h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2">
							<h2><?php getPhone($_GET['userp']); ?></h2>
							</td>
						</tr>
						<tr id="dtable-item2">
							<td style="color: #e5edb8" class="labelcell"><h2>Balance:</h2> </td>
							<td></td>
							<td style="color: #e5edb8" class="inputcell2">
							<h2>$<?php getBalance($_GET['userp']); ?></h2>
							</td>
						</tr>	
						<?php 
							}
						?>
					</table>
				</div>
			</div>
		
	</article>
	
	<?php 
		// Checking if the current user is owner of profile!
		if ( $_GET['userp']  != $_SESSION['user']){
		?>
		<article>
		<form method="post" action="" >
			<div>
				<input value="Upvote User" class="upvote" name="upvote" type="submit">
				<input value="Downvote User" class="downvote" name="downvote" type="submit">
			</div>
		</form>
		</article>
		<?php
		}
		else{
	?>
	<article>
		<form id="form2" method="post" action="">
			<h1>Modify Profile</h1>
				<div class="pure-u-1-2 dpanel">
					<div>
						<legend text-align="center">Modify Your information</legend>
						<table align="center">
							
							<tr id="dtable-item2">
								<td style="color: #e5edb8" class="labelcell"><h2>First Name:</h2></td>
								<td class="inputcell2">
								<input name="fname" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td style="color: #e5edb8" class="labelcell"><h2>Last Name:</h2></td>
								<td class="inputcell2">
								<input name="lname" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td style="color: #e5edb8" class="labelcell"><h2>Email:</h2></td>
								<td class="inputcell2">
								<input name="email" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td style="color: #e5edb8"class="labelcell"><h2>Address:</h2></td>
								<td class="inputcell2">
								<input name="address" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td style="color: #e5edb8" class="labelcell"><h2>Phone Number:</h2></td>
								<td class="inputcell2">
								<input name="phone_num" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>
							<tr id="dtable-item2">
								<td style="color: #e5edb8" class="labelcell"><h2>Withdraw Balance:</h2></td>
								<td class="inputcell2">
								<input name="wbalance" type="text" size="25" style="width: 75%;" />
								</td>
							</tr>	
						</table>
					</div>
				</div>
			<p>
				<input type="submit" class="modify" name="modify" value="Confirm" />
			</p>
		</form>
	</article>
	
	<article>
		<h1>Your Listings</h1>
		<div class="pure-u-1-2 dpanel">
			<div>
				<legend text-align="center">Manage Your Listings:</legend>
				<?php  displayAllUserListings($_SESSION)?>
			</div>
		</div>
			
	</article>
	
	<article>
		<h1>Your Orders</h1>
		<div class="pure-u-1-2 dpanel">
			<div>
				<legend text-align="center">View/Modify Your Orders</legend>
				<?php displayUserOrders($_SESSION); ?>
			</div>
		</div>
	</article>
	
	<?php } ?>

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