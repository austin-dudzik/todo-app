<?php
ob_start();
session_start();

// CONFIGURABLE VARIABLES

// Set the time zone
date_default_timezone_set('America/Chicago');

$host = "localhost"; // Host (typically "localhost")
$username = "root"; // Database Username
$password = ""; // Database password
$dbname = "todo"; // Database name
$url = "http://10.10.10.5/campfire"; // Iinstallation URL (Don't add / at the end)

// DON"T EDIT BELOW THIS LINE


include("functions.php");

$conn = new mysqli($host, $username, $password, $dbname);
if(isset($_SESSION['email'])) {
$emails = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$emails'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $userId = $row["id"];
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $email = $row["email"];
    }
}
}


$con = mysqli_connect($host, $username, $password, $dbname);
function row_count($result){
return mysqli_num_rows($result);
}

function escape($string) {
	global $con;
	return mysqli_real_escape_string($con, $string);
}

function confirm($result) {
	global $con;
	if(!$result) {
		die("QUERY FAILED" . mysqli_error($con));
	}
}

function query($query) {
	global $con;
	$result =  mysqli_query($con, $query);
	confirm($result);
	return $result;
}

function fetch_array($result) {
	global $con;
	return mysqli_fetch_array($result);
}
?>
