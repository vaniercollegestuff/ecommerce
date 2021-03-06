<?php
	function displayAllListings($POST, $DBH){
		if(!(isset($POST["Search"]))){
			//Get all the listings
			$STH = $DBH->prepare("SELECT * FROM listing WHERE status=7");
			$STH->execute();
		}
		else{
			//Get search results
			$STH = $DBH->prepare("SELECT * FROM listing WHERE " . $POST["select"] . " LIKE '%" . $POST["search"] . "%' AND status=7");
			$STH->execute();
		}
		
		//Start building the table
		echo "<table border='1' style='border-style: solid; border-width: medium;' align='center'><tr style='color: #e5edb8;'>";
		echo "<th>Image</td><th>Posted By</th><th>Product Name</th><th>Category</th><th>Price</th><th>Condition</th><th>List Date</th><th>Link</th></tr>";
		while($row = $STH->fetch()){
			//Save the user id, and category id in variables
			$uid = $row["user_id"];
			$cid = $row["category"];
			
			//Get the username
			$STHuname = $DBH->prepare("SELECT username FROM user WHERE user_id=" . $uid);
			$STHuname->execute();
			$rowuname = $STHuname->fetch();
			
			//Get the category name
			$STHcname = $DBH->prepare("SELECT name FROM category WHERE id=" . $cid);
			$STHcname->execute();
			$rowcname = $STHcname->fetch();
			
			//Put all the information into individual table cells
			echo "<tr style='color: #e5edb8;'>";
			echo "<td align='center'><img style='width: 50px; height: 50px;' src='img/" . $rowcname["name"] . ".png' /></td>";
			echo "<td align='center'><a href='user.php?userp=" . $uid . "'>" . $rowuname["username"] . "</a></td>";
			echo "<td align='center'>" . $row["product_name"] . "</td>";
			echo "<td align='center'>" . $rowcname["name"] . "</td>";
			echo "<td align='center'>$" . $row["price"] . "</td>";
			echo "<td align='center'>" . $row["item_condition"] . "</td>";
			echo "<td align='center'>" . $row["list_date"] . "</td>";
			echo "	<td align='center'>
						<form action='product.php' method='GET'>
							<input type='hidden' id='id' name='id' value='" . $row['listing_id'] . "' />
							<input style='color: #300018;' type='submit' value='Go to'/>
						</form>
					</td>";
			echo "</tr>";
		}
		//Close the table
		echo "</table>";
	}
?>