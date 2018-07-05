<?php

require_once('../mysqli_connect.php');

$valid = $validName = $validEmail = $passwordsMatch = false;
$data_missing = array();
$invalid = array();
$existing = array();

//store each missing/invalid/already existing field in respective arrays
if(empty($_POST['username'])){
	$data_missing[] = 'username';
}else{
	$username = trim($_POST['username']);
	if(!preg_match("/^[\w-]+$/", $username) ){
		$invalid[] = "username";
	}else{
		$validName = true;
	}
}

if(empty($_POST['email'])){
	$data_missing[] = 'email';
}else{
	$emailValid = false;
	$email = trim($_POST['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$invalid[] = "email";
	} else {
		$validEmail = true;
	}
}

if(empty($_POST['password'])){
	$data_missing[] = 'password';
}else{
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if(strcmp($password, $confirm_password) == 0){
		$options = ['cost' => 10];
		$passwordHash = password_hash($password, PASSWORD_BCRYPT);
		$passwordsMatch = true;
	}else{
		$invalid[] = "password";		
	}
}

if(empty($data_missing)){
	//if either username or email are invalid or passwords don't match then don't carry on with registration process
	$valid = $validName && $validEmail && $passwordsMatch;
	
	if($valid){

		//if username or email address already exists don't proceed with insert
		//using prepared statements because more secure
		$sqlCount = "SELECT * FROM users WHERE username = ?";
		$stmt = $db_connection->prepare($sqlCount);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();
		$resultUser = $stmt->num_rows;


		$sqlCount = "SELECT * FROM users WHERE email = ?";
		$stmt = $db_connection->prepare($sqlCount);
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		$resultEmail = $stmt->num_rows;

		if($resultUser > 0){
			$existing[] = "username";
		}else if($resultEmail > 0){
			$existing[] = "email";
		}

		//if another user(s) with same username and/or email doesn't exist insert new record
		if(empty($existing)){
			//sql to insert new user record passed prepared and executed after binding input values to correct fields

			$sqlInsert = "INSERT INTO users (username, email, sign_up_date, password) VALUES (?,?,now(),?)";
			$stmt = $db_connection->prepare($sqlInsert);
			$stmt->bind_param("sss", $username, $email, $passwordHash);

			$stmt->execute();
		}
		//close statement
		$stmt->close();
	}
}

//in JSON format, echo arrays containing containing names of fields causing errors
$response = array(
		"missingData" => $data_missing,
		"invalidData" => $invalid,
	 	"existingData" => $existing);

echo json_encode($response);

//close the database connection
$db_connection->close();
?>