<?php
$db_server ="localhost";
$db_user = "root";
$db_pass = "";
$db_name ="registration";

$db_connection = new mysqli($db_server, $db_user, $db_pass, $db_name);

if($db_connection->connect_error){
	die("Connection failed ".$db_connection->connect_error);
}
?>
