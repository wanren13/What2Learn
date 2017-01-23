<?php
if(empty($_SESSION))
{
    session_start();
}

$uni_code = $_GET['code'];

include '../conn.php';

$query = "SELECT Dept_code, Dept_name
			FROM DEPARTMENT, HAS
			WHERE HAS.University_code=".$uni_code." AND HAS.Department_code=DEPARTMENT.Dept_code";

$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($uni_code, $uni_name);

$response = "";

while ($result->fetch())
{
	$response = $response."<option value='".$uni_code."'>".$uni_name."</option>";
}

echo $response;
?>