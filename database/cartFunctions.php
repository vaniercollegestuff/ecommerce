<?php
	function createCart($DBH){
		if(isset($_COOKIE["cart"])){
			//Remove slashes that are escaping quotes
			if(get_magic_quotes_gpc() == true){
				foreach($_COOKIE as $key){
					$_COOKIE[$key] = stripslashes($value);
				}
			}
			
			$cart = json_decode($_COOKIE["cart"], true); //Return to a regular array
			
			echo "<div class='main'><div class='pure-u-1-4 dpanel'>";
			
			//Start building the table
			echo "<table border='1' style='border-style: solid; border-width: medium;' align='center'><tr style='color: #e5edb8;'>";
			echo "<th>Product Name</th><th>Price</th><th>Remove</th></tr>";
			$total = 0; //Running total of the cart
			//Create a table entry for each item in the cart
			foreach($cart as $item){
				$STH = $DBH->prepare("SELECT * FROM listing WHERE listing_id='" . $item . "'");
				$STH->execute();
				
				//Go through every row
				while($row = $STH->fetch()){
					//Put all the information into individual table cells
					echo "<tr style='color: #e5edb8;'>";
					echo "<td>" . $row["product_name"] . "</td>";
					echo "<td>$" . $row["price"] . "</td>";
					echo "	<td>
								<form action='' method='POST'>
									<input type='hidden' id='remove' name='remove' value='" . $row['listing_id'] . "' />
									<input style='color: #300018;' type='submit' value='Remove'/>
								</form>
							</td>";
					echo "</tr>";
					$total = $total + $row["price"];
				}
			}
			//Close the table
			echo "<tr style='color: #e5edb8;'><td>Total:</td><td>$$total</td><td></td></tr>";
			echo "</table></div></div>";
		}
		else{
			echo '<script language="javascript">';
			echo 'alert("Cart is empty!");';
			echo '</script>';
			
			echo "<script>setTimeout(\"location.href = './index.php';\",0);</script>";
			exit();	
		}
	}
	
	function removeItem(){//$itemID){
		if(isset($_POST["remove"])){
			$itemID = $_POST["remove"];
			
			//Remove slashes that are escaping quotes
			if(get_magic_quotes_gpc() == true){
				foreach($_COOKIE as $key){
					$_COOKIE[$key] = stripslashes($value);
				}
			}
			$cart = json_decode($_COOKIE["cart"], true); //Return to a regular array
			//Search for the value of the item id then remove it (if found)
			if(($key = array_search($itemID, $cart)) !== false)
				unset($cart[$key]);
			
			$json_cart = json_encode($cart); //Turn the cart to JSON
			$_COOKIE['cart'] = $json_cart; //Override the current value
			setcookie("cart", $json_cart, time() + (86400 * 30), "/"); //Insert the cookie
		}
	}
?>