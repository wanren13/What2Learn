<?php
if(empty($_SESSION))
{
    session_start();
}
?>

<html>
	<head></head>
	<body>
		<form onsubmit="return checkForm()" method="POST" action="edit_profile.php">
			<lable>First Name:</lable><input name="first_name" type="text" value="<?php echo $_SESSION['first_name'] ?>" /><br />
			<lable>Middle Name:</lable><input name="middle_name" type="text" value="<?php echo $_SESSION['middle_name'] ?>" /><br />
			<lable>Last Name:</lable><input name="last_name" type="text" value="<?php echo $_SESSION['last_name'] ?>" /><br />
			<lable>Birthday:</lable><input  name="birthday" type="date" value="<?php echo $_SESSION['birthday'] ?>" /><br />
			<input type="hidden" id="default_c" value="<?php echo $_SESSION['country'] ?>" />
			<label>country:</label>
            <select id="country" name="country">   	
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

            <label>gender:</label>
            <select name="gender">
                <option value ="Male"
                	<?php if ($_SESSION['gender'] == "Female") echo "selected"?>
                >Male</option>
                <option value ="Female" 
                	<?php if ($_SESSION['gender'] == "Female") echo "selected"?>
                	>Female</option>
            </select><br />
            <input type="submit" name="submit" value="modify" />
		</form>

		<script>
		/* default select */
		var d = document.getElementById("default_c").value;
		//alert(d); //debug

		var c = document.getElementById("country");

		//alert(o.length); //debug	
		for(var i = 0; i < c.options.length; i++)
		{
			//alert(c.options[i].value); //debug
			if (c.options[i].value == d)
			{
				c.selectedIndex = i;
				break;
			}
		}

		function checkForm()
        	{
        		//alert("test"); //debug
        		var first_name = document.getElementById("first_name").value;
        		//alert(username); //debug
        		var middle_name = document.getElementById("middle_name").value;
        		var last_name = document.getElementById("last_name").value;
        		var birthday = document.getElementById("birthday").value;
        		var country = document.getElementById("country").value;
        		var gender = document.getElementById("gender").value;

        		if (first_name == "" || middle_name == "" || last_name == "" || birthday == "" || country == "" || gender == "")
        		{
        			alert("please complete the input!");
        			return false;
        		}
        		return true;
        	}
		</script>
	</body>
</html>

<?php
if (isset($_POST['submit']))
{
	unset($_POST['submit']);
	$first_name = htmlspecialchars($_POST['first_name']);
	$middle_name = htmlspecialchars($_POST['middle_name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	$birthday = htmlspecialchars($_POST['birthday']);
	$country = htmlspecialchars($_POST['country']);
	$gender = htmlspecialchars($_POST['gender']);

	include 'conn.php';

	$sql = "UPDATE STUDENT SET First_name='".$first_name."', Middle_name='".$middle_name."', Last_name='".$last_name."', Birthday='".$birthday."', Country='".$country."', Gender='".$gender."' WHERE User_id=".$_SESSION['user_id'];

	//echo $sql; //debug

	$result = $mysqli->query($sql);

	//update session
	$sql = "SELECT First_name, Middle_name, Last_name, Birthday, Country, Gender FROM STUDENT WHERE User_id=".$_SESSION['user_id'];

    //echo $sql."<br />"; //debug

    $result = $mysqli->query($sql);
    if ($result)
    {
        //echo $result->num_rows."<br />"; //debug
        if ($result->num_rows == 1)
        {
            while($row = $result->fetch_array())
            {
                $_SESSION['first_name'] = $row[0];
                if($row[1] != "") {
                    $_SESSION['middle_name'] = $row[1];
                }
                $_SESSION['last_name'] = $row[2];
                $_SESSION['birthday'] = $row[3];
                $_SESSION['country'] = $row[4];
                $_SESSION['gender'] = $row[5];
            }
                $result->close();
                $mysqli->close();
                header("refresh:0;url=home.php");
        }
    }
}
?>