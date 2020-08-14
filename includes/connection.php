<?php

$servername = "localhost";
$username = "root";
$password = "Lu636593";
$database = "soccer";

// create connection
$conn = new mysqli($servername, $username, $password, $database);
mysqli_set_charset($conn, 'UTF8');

// check connection
if ($conn->connect_error) {
	echo mysqli_connect_error();
}
?>