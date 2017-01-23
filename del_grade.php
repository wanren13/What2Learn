<?php
if(empty($_SESSION))
{
    session_start();
}

$course_code = $_GET['course_code'];
$uni_code = $_GET['uni_code'];
echo $_SESSION['user_id']; //debug
echo $course_code;
echo $uni_code;

include 'conn.php';

$sql = "DELETE FROM GRADE_REPORT WHERE User_id=".$_SESSION['user_id']." AND University_code=".$uni_code." AND course_code=".$course_code;

//echo $sql;

$result = $mysqli->query($sql);
$mysqli->close();

header("refresh:0;url=home.php");
?>