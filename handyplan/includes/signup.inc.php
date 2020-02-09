<?php

if(isset($_POST['register-submit'])){
	require 'database.inc.php';
	$username = $_POST['un'];
	$email = $_POST['email'];
	$password = $_POST['pwd'];
	$repeatpassword = $_POST['pwd2'];
	$firstname = $_POST['fname'];
	$lastname = $_POST['lname'];

	if(empty($username)|| empty($email)|| empty($password)|| empty($repeatpassword)|| empty($firstname)|| empty($lastname)){
		header("Location: ../signUpPage.html?error=emptyfields&un=".$username."&email=".$email."&firstname=".$firstname."&lastname=".$lastname);
		exit();
	}
	else if($password !== $repeatpassword){
		header("Location: ../signUpPage.html?error=passwordmatch&un=".$username."&email=".$email."&firstname=".$firstname."&lastname=".$lastname);
		exit();
	}
	else{
		$sql = "SELECT username FROM users WHERE username=?";
		$statement = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($statement, $sql)){
			header("Location: ../signUpPage.html?error=sqlerror&un=".$username."&email=".$email."&firstname=".$firstname."&lastname=".$lastname);
			exit();
		}
		else{
			sysqli_stmt_bind_param($statement, "s", $username);
			mysqli_stmt_execute($statement);
			mysqli_stmt_store_result($statement);
			$result = mysqli_stmt_num_rows();
			if($result >0){
				header("Location: ../signUpPage.html?error=usernametaken&un=".$username."&email=".$email."&firstname=".$firstname."&lastname=".$lastname);
				exit();
			}
			else{
				$sql = "INSERT INTO users (username, password, email, firstname, lastname) VALUES (?,?,?,?,?)";
				$statement = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($statement, $sql)){
					header("Location: ../signUpPage.html?error=sqlerror&un=".$username."&email=".$email."&firstname=".$firstname."&lastname=".$lastname);
					exit();
				}
				else{
					$hashpass = password_hash($password, PASSWORD_DEFAULT);
					sysqli_stmt_bind_param($statement, "sssss", $username, $hashpass, $email, $firstname, $lastname);
					mysqli_stmt_execute($statement);
					header("Location: ../login.html?signup=success");
					exit();
				}
			}
		}
	}
	mysqli_stmt_close($statement);
	mysqli_close($conn);
}
else{
	header("Location: ../signUpPage.html");
	exit();
}