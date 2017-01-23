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

	$company = htmlspecialchars($_POST['company']);
	$position = htmlspecialchars($_POST['position']);
	$start_date = htmlspecialchars($_POST['start_date']);
	$end_date = htmlspecialchars($_POST['end_date']);
	$salary = htmlspecialchars($_POST['salary']);

	$sql = "INSERT INTO WORKS_AT(User_id, Company_id, Pos_code, Start_time, End_time, Salary)
			VALUES(".$_SESSION['user_id'].", ".$company.", ".$position.", '".$start_date."', '".$end_date."', ".$salary.")";
	
	//echo $sql; //debug

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

	<body  id="dummybodyid">
		<?php include 'header.php' ?>
		<div class="add-box" style="height: 500px">

		<div class="add-box-header">Where did you work before?</div>
		<div class="add-box-body">
			<form method="POST" onsubmit="return checkForm()" action="co_report.php">
				<label>company:</lable>
				<select name="company" id="company" onclick="queryPos()">
					<?php
						if (isset($_GET['code']) && isset($_GET['name']))
						{
							echo "<option value='".$_GET['code']."'>".$_GET['name']."</option>";
						}
						else
						{
							$query = "SELECT Co_id, Co_name 
							 FROM COMPANY";
		            		$result = $mysqli->prepare($query);
		            		$result->execute();
		            		$result->bind_result($co_id, $co_name);
		            		echo "<option value=''></option>";
		            		while ($result->fetch())
		            		{
		            			echo "<option value='".$co_id."'>".$co_name."</option>";
		            		}

		            		$result->close();
		            		}
					?>
				</select><br />
				<label>position:</lable>
				<select name="position" id="position">
				</select><br />	
				<label>from:&nbsp&nbsp&nbsp&nbsp</label>
				<input type="date" name="start_date" value="1990-01-01" /><br />
				<label>to:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
				<input type="date" name="end_date" value="2015-01-01" /><br />
				<label>salary:&nbsp&nbsp</label>
				<input type="number" name="salary" id="salary" /><br />
				<input type="submit" name="submit" value="join" class="add-btn" />
			</form>
		</div>
		</div>
		<script>
			var xmlHttp;

			function queryPos(){
				//alert("test") //debug
				var pos = document.getElementById("position");
				var co_code = document.getElementById("company").value;
				//dept.innerHTML = "<option>test</option"; //debug
				//alert(co_code); //debug

				//using ajax to connect to db
				xmlHttp = GetXmlHttpObject();
				if (xmlHttp == null)
  				{
					alert ("Browser does not support HTTP Request");
					return;
				} 
				var url="ajax/getPosByCoid.php?code=" + co_code + "&sid=" + Math.random();
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
			 		document.getElementById("position").innerHTML = xmlHttp.responseText;
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
				var dept = document.getElementById("position").value;
				var salary = document.getElementById("salary").value;

				if (dept == "")
				{
					alert("please select a position");
					return false;
				}

				if (salary == "" || salary < 0)
				{
					alert("please enter a correct salary");
					return false;
				}
				return true;
			}
		</script>
	</body>
</html>