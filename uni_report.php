<?php
if (empty($_SESSION))
{
	session_start();
}

//echo $_SESSION['user_id']; //debug

include 'conn.php';

if (isset($_POST['submit']))
{
	//echo "test"; //debug

	unset($_SESSION['submit']);

	$university = htmlspecialchars($_POST['university']);
	$department = htmlspecialchars($_POST['department']);
	$date = htmlspecialchars($_POST['date']);

	$sql = "INSERT INTO ATTEND(User_id, University_code, Attend_time, Department_code)
			VALUES(".$_SESSION['user_id'].", ".$university.", '".$date."', ".$department.")";
	
	echo $sql;

	$result = $mysqli->query($sql);
	//$result->close();

	$mysqli->close();
	header("refresh:0;url=home.php");
}
?>

<html>
	<head>
		<link type="text/css" rel="stylesheet" href="css/skill_report.css">
		<link type="text/css" rel="stylesheet" href="css/home.css">
	</head>

	<body id="dummybodyid">
		<?php include 'header.php' ?>
		<div class="add-box" style="height: 500px">
		<div class="add-box-header">please fill in university registration information</div>
		<div class="add-box-body">
		<form method="POST" onsubmit="return checkForm()" action="uni_report.php">
			<label>university:</lable>
			<select name="university" id="university" onchange="queryDept()">
				<?php
					$query = "SELECT Uni_code, Uni_name 
					 FROM UNIVERSITY";
            		$result = $mysqli->prepare($query);
            		$result->execute();
            		$result->bind_result($uni_code, $uni_name);
            		echo "<option value=''></option>";
            		while ($result->fetch())
            		{
            			echo "<option value='".$uni_code."'>".$uni_name."</option>";
            		}

            		$result->close();
				?>
			</select><br />
			<label>department:</lable>
			<select name="department" id="department">
			</select><br />	
			<label>enter date:</label>
			<input type="date" name="date" /><br />
			<input type="submit" name="submit" value="attend" class="add-btn"/>
		</form>
	</div>
	</div>
		<script>
			var xmlHttp;

			function queryDept(){
				//alert("test") //debug
				var dept = document.getElementById("department");
				var uni_code = document.getElementById("university").value;
				//dept.innerHTML = "<option>test</option"; //debug
				//alert(uni_code); //debug

				//using ajax to connect to db
				xmlHttp = GetXmlHttpObject();
				if (xmlHttp == null)
  				{
					alert ("Browser does not support HTTP Request");
					return;
				} 
				var url="ajax/getDeptByUnicode.php?code=" + uni_code + "&sid=" + Math.random();
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
			 		//alert("state changed"); //debug
			 		//alert(xmlHttp.responseText);
			 		document.getElementById("department").innerHTML = xmlHttp.responseText;
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

			function checkForm(){
				var dept = document.getElementById("department").value;

				if (dept == "")
				{
					alert("please select a department");
					return false;
				}

				return true;
			}
		</script>
	</body>
</html>