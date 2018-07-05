<?php
require_once('../mysqli_connect.php');

$data_missing = array();
$userExists = false;
$correctPassword = false;

if(empty($_POST['login_username'])){
	$data_missing[] = "login_username";
}else{
	$username = $_POST['login_username'];
}

if(empty($_POST['login_password'])){
	$data_missing[] = "login_password";
}else{
	$password = $_POST['login_password'];
}


//check if user exists
$sql = "SELECT password FROM users WHERE username = ?";

if($stmt = $db_connection->prepare($sql)){
	$stmt->bind_param("s", $username);
 	if($stmt->execute()){
 		$stmt->store_result();
 		$stmt->bind_result($hash);

		if($stmt->num_rows>0){
			$userExists = true;
			$stmt->fetch();
			if(password_verify($password, $hash)){
				$correctPassword = true;
			}
		}
 	}
}

$stmt->close();

$response = array(
	"missingData" => $data_missing,
	"userExists" => $userExists,
	"correctPassword" => $correctPassword
);

echo json_encode($response);

$db_connection->close();
?>