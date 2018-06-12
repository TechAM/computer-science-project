<?php

require_once('../mysqli_connect.php');

// if(isset($_POST['submit'])){
// echo json_encode( $_SERVER['REQUEST_METHOD'] . " " . $_POST['username']);

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


if(empty($data_missing)){
	//if either username or email are invalid or passwords don't match then set valid to false
	$valid = $validName && $validEmail && $passwordsMatch;
	
	if($valid){
		//if username or email address already exists don't proceed with insert
		$result = $db_connection->query("SELECT COUNT(*) AS total FROM users WHERE username = '".$username."'");
		$row = $result->fetch_assoc();
		$resultUser = $row['total'];

		$result = null;
		$row = null;

		$result = $db_connection->query("SELECT COUNT(*) AS total FROM users WHERE email = '".$email."'");
		$row = $result->fetch_assoc();
		$resultEmail = $row['total'];


		if($resultUser > 0){
			$existing[] = "username";
		}else if($resultEmail > 0){
			$existing[] = "email";
		}
		

		//sql to insert new user record passed prepared and executed after binding input values to correct fields
		if(empty($existing)){
			$sqlInsert = "INSERT INTO users (username, email, sign_up_date, password) VALUES (?,?,now(),?)";
			$stmt = $db_connection->prepare($sqlInsert);
			$stmt->bind_param("sss", $username, $email, $password);

			if($stmt->execute()){
				// echo "Record inserted successfully<br>";
			}else{
				// echo "Record failed to insert : " . $stmt->error."<br>";
			}
			//close the statement
			$stmt->close();
		}
	}
}

$response = array(
		"missingData" => $data_missing,
		"invalidData" => $invalid,
	 	"existingData" => $existing);

echo json_encode($response);

//close the database connection
$db_connection->close();
?>