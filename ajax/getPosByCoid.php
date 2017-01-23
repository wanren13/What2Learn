<?php
if(empty($_SESSION))
{
    session_start();
}

$co_id = $_GET['code'];

include '../conn.php';

$query = "SELECT Pos_code, Pos_name
			FROM POSITION, OFFER
			WHERE OFFER.Company_id=".$co_id." AND OFFER.Position_code=POSITION.Pos_code";

$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($pos_code, $pos_name);

$response = "";

while ($result->fetch())
{
	$response = $response."<option value='".$pos_code."'>".$pos_name."</option>";
}

echo $response;
?>