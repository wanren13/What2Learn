<?php
if(empty($_SESSION))
{
    session_start();
}

$uni_code = $_GET['uni_code'];
$dept_code = $_GET['dept_code'];
//echo $_SESSION['user_id']; //debug
//echo $uni_code;

include 'conn.php';

$sql = "DELETE FROM ATTEND WHERE User_id=".$_SESSION['user_id']." AND University_code=".$uni_code." AND Department_code=".$dept_code;

//echo $sql;

$result = $mysqli->query($sql);
$mysqli->close();
header("refresh:0;url=home.php");
?>