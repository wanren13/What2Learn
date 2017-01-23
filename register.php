<?php
if(empty($_SESSION))
{
    session_start();
}

include 'conn.php';
?>
<html>
    <head><link type="text/css" rel="stylesheet" href="css/register.css"></head>

    <body id="dummybodyid">
        <div class="reg-box">
            <div class="reg-box-header">Register</div>
            <div class="reg-box-body">
                <form name="LoginForm" method="post" onsubmit="return checkForm()" action="register.php">
                    <input type="text" id="username" name="username" placeholder="User Name" onkeyup="javascript: queryUsername()" /><br /><span style="display: none; color: red; font-size: 11px" id="hint"></span>
                    <input type="password" id="password" name="password" placeholder="Password" /><br />
                    <input type="password" id="password2" name="password2" placeholder="Repeat Password" /><br />
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" /><br />
                    <input type="text" name="middle_name" placeholder="Middle Name" /><br />
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" />
                    <label>Birthday:</label><input type="date" name="birthday" placeholder="Birthday" /><br />

                    <label>Country:</label>
                    <select name="country">
                        <option value ="Indonesia">Indonesia</option>
                        <option value ="Portugal">Portugal</option>
                        <option value ="Greece">Greece</option>
                        <option value ="France">France</option>
                        <option value ="South Africa">South Africa</option>
                        <option value ="Philippines">Philippines</option>
                        <option value ="Afghanistan">Afghanistan</option>
                        <option value ="Myanmar">Myanmar</option>
                        <option value ="Poland">Poland</option>
                        <option value ="United States">United States</option>
                        <option value ="Germany">Germany</option>
                        <option value ="Jordan">Jordan</option>
                        <option value ="Russia">Russia</option>
                        <option value ="Angola">Angola</option>
                        <option value ="China">China</option>
                        <option value ="Chile">Chile</option>
                        <option value ="Tanzania">Tanzania</option>
                        <option value ="Cameroon">Cameroon</option>
                        <option value ="Venezuela">Venezuela</option>
                        <option value ="Japan">Japan</option>
                        <option value ="Sweden">Sweden</option>
                        <option value ="Syria">Syria</option>
                        <option value ="Thailand">Thailand</option>
                        <option value ="Iran">Iran</option>
                        <option value ="Ukraine">Ukraine</option>
                    </select><br />

                    <label>Gender:</label>
                    <select name="gender">
                        <option value ="Male">Male</option>
                        <option value ="Female">Female</option>
                    </select><br />

                    <input type="submit" name="submit" value="register" class="reg-btn"/>
                </form>
                <script>
                var xmlHttp;

                function queryUsername(){
                    //alert("test") //debug
                    var username = document.getElementById("username").value;

                    //using ajax to connect to db
                    xmlHttp = GetXmlHttpObject();
                    if (xmlHttp == null)
                    {
                        alert ("Browser does not support HTTP Request");
                        return;
                    } 
                    var url="ajax/getUsername.php?username=" + username + "&sid=" + Math.random();
                    //alert(url); //debug
                    xmlHttp.onreadystatechange = stateChanged;
                    xmlHttp.open("GET", url, true);
                    xmlHttp.send(null);

                }

                function stateChanged() 
                { 
                    //alert("state detect"); //debug
                    //alert(xmlHttp.readyState); //debug
                    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
                    { 
                        var result = xmlHttp.responseText;
                        document.getElementById("hint").style.display = "block";
                        //alert(result);
                        if (result == "true")
                        {
                            document.getElementById("hint").innerText = "you can use this";
                        }
                        if (result == "false")
                        {
                            document.getElementById("hint").innerText = "this name has been taken";
                        }
                        
                    } 
                }

                function GetXmlHttpObject()
                {
                    var xmlHttp = null;
                    try
                    {
                     // Firefox, Opera 8.0+, Safari
                     xmlHttp=new XMLHttpRequest();
                    }
                    catch (e)
                    {
                     // Internet Explorer
                         try
                         {
                          xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                         }
                         catch (e)
                         {
                          xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                         }
                     }
                    return xmlHttp;
                }
                </script>
                <script>
                    function checkForm()
                    {
                        //alert("test"); //debug
                        var username = document.getElementById("username").value;
                        //alert(username); //debug
                        var password = document.getElementById("password").value;
                        var password2 = document.getElementById("password2").value;
                        var first_name = document.getElementById("first_name").value;
                        var last_name = document.getElementById("last_name").value;

                        if (username == "" || password == "" || password2 == "" || first_name == "" || last_name == "")
                        {
                            alert("please complete the input!");
                            return false;
                        }
                        if (password != password2)
                        {
                            alert("password input must match!");
                            return false;
                        }
                        return true;
                    }
                </script>
            </div>
        </div>
        
    </body>
</html>

<?php
if (isset($_POST['submit']))
{
	unset($_POST['submit']);
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$first_name = htmlspecialchars($_POST['first_name']);
	$middle_name = htmlspecialchars($_POST['middle_name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	$birthday = htmlspecialchars($_POST['birthday']);
	$country = htmlspecialchars($_POST['country']);
	$gender = htmlspecialchars($_POST['gender']);

	$sql = "INSERT INTO STUDENT(Username, Passwd, First_name, Middle_name, Last_name, Birthday, Country, Gender) VALUES('".$username."', '".$password."' ,'".$first_name."', '".$middle_name."', '".$last_name."', '".$birthday."', '".$country."', '".$gender."');";

	//echo $sql; //debug

	$result = $mysqli->query($sql);
	if ($result)
	{
	    $_SESSION['status'] = "register_ok";
	}
	else
	{
	    $_SESSION['status'] = "register_fail";
	}

	//echo $_SESSION['status']; //debug

	$mysqli->close();
    //echo "test";
	//header("refresh:0;url=index.php");
    echo "<script>window.location.href='index.php';</script>";
}
?>