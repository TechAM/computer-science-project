<?php

require_once('../mysqli_connect.php');

// if(isset($_POST['submit'])){
// echo json_encode( $_SERVER['REQUEST_METHOD'] . " " . $_POST['username']);
$valid = $validName = $validEmail = $passwordsMatch = false;
$data_missing = array();
$invalid = array();

if(empty($_POST['username'])){
	$data_missing[] = 'username';
}else{
	$username = trim($_POST['username']);
	if(!preg_match("/^[\w-]+$/", $username) ){
		// echo $username." is invalid<br>";
		$invalid[] = "username";
	}else{
		// echo $username." is valid<br>";
		$validName = true;
	}
	// echo json_encode($_SERVER['REQUEST_METHOD'] . " " . $username);
}

if(empty($_POST['email'])){
	$data_missing[] = 'email';
}else{
	$emailValid = false;
	$email = trim($_POST['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// echo $email." is invalid<br>";
		$invalid[] = "email";
	} else {
		// echo $email." is valid<br>";
		$validEmail = true;
	}
	// echo json_encode($_SERVER['REQUEST_METHOD'] . " " . $email);

}

if(empty($_POST['password'])){
	$data_missing[] = 'password';
}else{
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if(strcmp($password, $confirm_password) == 0){
		// echo "passwords match<br>";
		$options = ['cost' => 10];
		$password = password_hash($password, PASSWORD_BCRYPT, $options);
		$passwordsMatch = true;
		// echo $password."<br>";
	}else{
		$invalid[] = "password";		
	}
	// echo json_encode($_SERVER['REQUEST_METHOD'] . " " . $password);

}

// echo json_encode($_SERVER['REQUEST_METHOD'] . " " . $username  . " " . $email . " " . $password);
echo json_encode($data_missing);


if(empty($data_missing)){
	//if either username or email are invalid or passwords don't match then set valid to false
	$valid = $validName && $validEmail && $passwordsMatch;
	
	//for each invalid column store it in an array
	if(!$validName){
		$invalid[] = "Username";
	}
	if(!$validEmail){
		$invalid[] = "Email";
	}
	if(!$passwordsMatch){
		$invalid[] = "Password";
	}

	if($valid){
		//if username or email address already exists don't proceed with insert
		$sqlCheckExists = "SELECT COUNT(*) AS total FROM users WHERE username=? OR email=?";
		$stmt = $db_connection->prepare($sqlCheckExists);
		$stmt->bind_param("ss", $username, $email);
		
		if(!$stmt->execute()){
			trigger_error('The query execution failed; MySQL said ('.$stmt->errno.') '.$stmt->error, E_USER_ERROR);
		}
		$result = null;
		$stmt->bind_result($result);
		$stmt->fetch();
		// echo "counted {$result} records<br>";
		
		$stmt->close();
		//sql to insert new user record passed prepared and executed after binding input values to correct fields
		if($result==0){
			$sqlInsert = "INSERT INTO users (username, email, sign_up_date, password) VALUES (?,?,now(),?)";
			$stmt = $db_connection->prepare($sqlInsert);

			// echo $username."<br>";
			// echo $email."<br>";
			// echo $password."<br>";

			$stmt->bind_param("sss", $username, $email, $password);

			if($stmt->execute()){
				// echo "Record inserted successfully<br>";
			}else{
				// echo "Record failed to insert : " . $stmt->error."<br>";
			}
			//close the statement
			$stmt->close();
		}
	}else{
		// echo "These fields are invalid:<br>";
		// foreach($invalid as $result){
		// 	echo $result."<br>";
		// }

		// $result0 = $invalid[0];
		// echo $result0;
		//using JSON to send data to the Javascript
	}
}

	//close the database connection
	

	//return to main page once signed up
	// header("Location: {$_SERVER['HTTP_REFERER']}");
	// exit;
// }


$db_connection->close();
?>