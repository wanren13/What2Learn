
<?php
if(empty($_SESSION))
{
    session_start();
}


include 'loadusers.php';

//when submitted
if (isset($_POST['submit']))
{
    unset($_POST['submit']);
    //echo "test"; //debug

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    include 'conn.php';

    $sql = "SELECT User_id, First_name, Middle_name, Last_name, Birthday, Country, Gender FROM STUDENT WHERE Username='".$username."' AND Passwd='".$password."'";

    //echo $sql."<br />"; //debug

    $result = $mysqli->query($sql);
    if ($result)
    {
        //echo $result->num_rows."<br />"; //debug
        if ($result->num_rows == 1)
        {
            while($row = $result->fetch_array())
            {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row[0];
                $_SESSION['first_name'] = $row[1];
                if($row[2] != "") {
                    $_SESSION['middle_name'] = $row[2];
                }
                $_SESSION['last_name'] = $row[3];
                $_SESSION['birthday'] = $row[4];
                $_SESSION['country'] = $row[5];
                $_SESSION['gender'] = $row[6];
            }
                $result->close();
                $mysqli->close();
                header("refresh:0;url=home.php");
        }
        else
        {
            $_SESSION['status'] = "username/password error!";
            //echo $_SESSION['status']; //debug
            header("refresh:0;url=index.php");
        }
    }
}
?>

