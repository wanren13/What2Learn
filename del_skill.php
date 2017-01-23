<?php
if(empty($_SESSION))
{
    session_start();
}

$skill_id = $_GET['id'];

include 'conn.php';

$sql = "DELETE FROM POSSESS WHERE User_id=".$_SESSION['user_id']." AND Skill_id=".$skill_id;

//echo $sql;

$result = $mysqli->query($sql);
$mysqli->close();
header("refresh:0;url=home.php");
?>