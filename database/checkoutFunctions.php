<?php
	//Turns the data in the cookie into a table with all the items populating cells
	function showCart($DBH){
		if(isset($_POST["remove"])){
			removeItem($_POST["remove"]);
		}
		
		//Remove slashes that are escaping quotes
		if(get_magic_quotes_gpc() == true){
			foreach($_COOKIE as $key){
				$_COOKIE[$key] = stripslashes($value);
			}
		}
		
		$cart = json_decode($_COOKIE["cart"], true); //Return to a regular array
		
		//Start building the table
		echo "<table border='1' style='border-style: solid; border-width: medium;' align='center'><tr style='color: #e5edb8;'>";
		echo "<th>Product Name</th><th>Price</th></tr>";
		$total = 0; //Running total of the cart
		//Create a table entry for each item in the cart
		foreach($cart as $item){
			$STH = $DBH->prepare("SELECT * FROM listing WHERE listing_id='" . $item . "'");
			$STH->execute();
			
			while($row = $STH->fetch()){
				//Put all the information into individual table cells
				echo "<tr style='color: #e5edb8;'>";
				echo "<td>" . $row["product_name"] . "</td>";
				echo "<td>$" . $row["price"] . "</td>";
				echo "</tr>";
				$total = $total + $row["price"];
			}
		}
		//Close the table
		echo "</table></div></div>";
		
		if(isset($_POST["confirm"])){
			if(checkBalance($total, $DBH)){
				if(checkListingStatuses($cart, $DBH)){
					addTransaction($total, $cart, $DBH);
					
					echo '<script language="javascript">';
					echo 'alert("Purchase complete!");';
					echo '</script>';
				
					echo "<script>setTimeout(\"location.href = './index.php';\",0);</script>";
					exit();
				}
				else{
					echo '<script language="javascript">';
					echo 'alert("Invalid product in cart! Please remove it before continuing!");';
					echo '</script>';
				
					echo "<script>setTimeout(\"location.href = './cart.php';\",0);</script>";
					exit();
				}
			}
			else{
				echo '<script language="javascript">';
				echo 'alert("Insufficient balance! Please modify your cart before proceeding!");';
				echo '</script>';
				
				echo "<script>setTimeout(\"location.href = './cart.php';\",0);</script>";
				exit();
			}
		}
	}
	
	//Checks if the user has enough money to buy everything in the cart
	function checkBalance($total, $DBH){
		$STH = $DBH->prepare("SELECT current_balance FROM balance WHERE user_id='" . $_SESSION["user"] . "'");
		$STH->execute();
		$balance = $STH->fetch();
		
		return $total <= $balance["current_balance"];
	}
	
	function addTransaction($total, $cart, $DBH){
		//Insert into orders
		$STH = $DBH->prepare("	INSERT INTO orders (order_id, user_id, order_date, order_total, status)
								VALUES (NULL, '" . $_SESSION["user"] . "', '" . date('Y-m-d') . "',
								'$total', '9')");
		$STH->execute();
		
		//Insert into order_items
		foreach($cart as $item){
			$STH = $DBH->prepare("	INSERT INTO order_item (order_id, product_id)
									VALUES (LAST_INSERT_ID(), '" . $item . "')");
			$STH->execute();
			
			//Update seller's balance
			$STH = $DBH->prepare("	UPDATE balance
									SET current_balance = current_balance + (SELECT price from listing WHERE listing_id = '$item')
									WHERE user_id=(SELECT user_id from listing WHERE listing_id = '$item')");
			$STH->execute();
			
			//Update the listing's status
			$STH = $DBH->prepare("	UPDATE listing
									SET status = 8
									WHERE listing_id='$item'");
			$STH->execute();
		}
		
		//Update buyer's balance
		$STH = $DBH->prepare("	UPDATE balance
								SET current_balance = current_balance - $total
								WHERE user_id=" . $_SESSION["user"]);
		$STH->execute();
		
		//Get last_insert_id
		$STH = $DBH->query("SELECT LAST_INSERT_ID()");
		$lastInsertId = $STH->fetch(PDO::FETCH_NUM);
		$lastInsertId = $lastInsertId[0];
		
		//Insert into transaction
		$STH = $DBH->prepare("	INSERT INTO transaction (ID, user_id, order_id, transaction_method, amount)
								VALUES (NULL, '" . $_SESSION["user"] . "', '$lastInsertId', '1', '$total')");
		$STH->execute();
		
		clearCart();
	}
	
	//Verifies that all the listings are available for purchase
	function checkListingStatuses($cart, $DBH){
		foreach($cart as $item){
			$STH = $DBH->prepare("SELECT status FROM listing WHERE listing_id=$item");
			$STH->execute();
			$status = $STH->fetch();
			
			if($status["status"] != 7)
				return false;
		}
		
		return true;
	}
	
	//Clears all cookies
	function clearCart(){
		$user_id = $_SESSION["user"];
		$past = time() - 3600;
		foreach ($_COOKIE as $key => $value )
		{
			setcookie( $key, $value, $past, '/' );
		}
		$_SESSION["user"] = $user_id;
	}
?>