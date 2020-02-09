<?php

if(isset($_POST['login-submit'])){
	require 'database.inc.php';
	$username = $_POST['un'];
	$password = $_POST['pwd'];

	if(empty($username) || empty($password)){
		header("Location: ../login.html?error=emptyfields");
		exit();
	}
	else{
		$sql = "SELECT * FROM users WHERE username=? OR email=?;";
		$statement=mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($statement,$sql)){
			header("Location: ../login.html?error=sqlerror");
			exit();
		}
		else{
			mysqli_stmt_bind_param($statement, "ss", $username, $password);
			mysqli_stmt_execute($statement);
			$result = mysqli_stmt_get_result($statement);
			if($row = mysqli_fetch_assoc($result)){
				$pwdCheck = password_verify($password, $row['password']);
				if($pwdCheck == false){
					header("Location: ../login.html?error=wrongpwd");
					exit();
				}
				else if($pwdCheck == true){
					session_start();
					$_SESSION['userid']= $row['userid'];
					$_SESSION['username']= $row['username'];
					$_SESSION['email']= $row['email'];
					$_SESSION['firstname']= $row['firstname'];
					$_SESSION['lastname']= $row['lastname'];

					header("Location: ../handiplan.html?login=success");
					exit();
				}
				else{
					header("Location: ../login.html?error=wrongpwd");
					exit();
				}
			}
			else{
				header("Location: ../login.html?error=nouser");
				exit();
			}
		}
	}
}
else{
	header("Location: ../login.html");
	exit();
}