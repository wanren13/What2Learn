<?php
if(empty($_SESSION))
{
    session_start();
}

$username = $_GET['username'];

//echo $username;

include '../conn.php';

$query = "SELECT *
			FROM STUDENT
			WHERE Username='".$username."'";

$result = $mysqli->query($query);

$response = "";

//echo $result->num_rows;

if ($result->num_rows > 0)
{
	$response = "false";
}
else
{
	$response = "true";
}

echo $response;
?>