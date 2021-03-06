<?php
	function registerUser($POST, $DBH){
		//Put all user input into variables
		$user = $POST["username"];
		$pass = $POST["password"];
		$pass = password_hash($pass, PASSWORD_DEFAULT);
		$fname = $POST["fname"];
		$lname = $POST["lname"];
		$email = $POST["email"];
		$address = $POST["address"];
		$phone = $POST["phone"];
		
		//Check if the username is available
		if(verifyUsername($user, $DBH)){
			//SQL to insert a user into the user table
			$status = $DBH->query("SELECT ID FROM status WHERE name='USER'");
			$STH = $DBH->prepare("INSERT INTO user (user_id, username, passwordhash, fname, lname, email,
								address, phone_num, status)
								VALUES (NULL, '$user', '$pass', '$fname', '$lname', '$email',
								'$address', '$phone'," . $status->fetch()["ID"] . ")");
			$STH->execute();
			$STH = $DBH->prepare("INSERT INTO balance (user_id, current_balance)
								VALUES (LAST_INSERT_ID(), 0)");
			$STH->execute();
		}
	}
	
	function verifyUsername($user, $DBH){
		//Checking if username is already taken
		$STH = $DBH->prepare("SELECT username FROM user WHERE username=" . $user);
		$STH->execute();
		
		if($STH->fetch() == null){
			return TRUE;
		}
		else{
			echo '<script language="javascript">';
			echo 'alert("Username has been taken!");';
			echo '</script>';
		}
	}
?>