<?php
if(empty($_SESSION))
{
    session_start();
}

$co_id = $_GET['co_id'];
$pos_code = $_GET['pos_code'];
//echo $_SESSION['user_id']; //debug
//echo $uni_code;

include 'conn.php';

$sql = "DELETE FROM WORKS_AT WHERE User_id=".$_SESSION['user_id']." AND Company_id=".$co_id." AND Pos_code=".$pos_code;

//echo $sql;

$result = $mysqli->query($sql);
$mysqli->close();
header("refresh:0;url=home.php");
?>