<?php
if (empty($_SESSION))
{
	session_start();
}

include 'conn.php';

if (isset($_POST['submit']))
{
	//echo "test"; //debug

	unset($_SESSION['submit']);

	$skill = htmlspecialchars($_POST['skill']);

	$sql = "INSERT INTO POSSESS(User_id, Skill_id)
			VALUES(".$_SESSION['user_id'].", ".$skill.")";
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
		<div class="add-box">
			<div class="add-box-header">Which Skill to Add?</div>
			<div class="add-box-body">
			<form method="POST" onsubmit="return checkForm()" action="skill_report.php">
				<label>skill name:</lable>
				<select name="skill" id="skill">
					<?php
					if (isset($_GET['id']) && isset($_GET['name']))
						{
							echo "<option value='".$_GET['id']."'>".$_GET['name']."</option>";
						}
						else{
							$query = "SELECT Skill_id, Skill_name 
						 FROM SKILL";
	            		$result = $mysqli->prepare($query);
	            		$result->execute();
	            		$result->bind_result($skill_id, $skill_name);
	            		echo "<option value=''></option>";
	            		while ($result->fetch())
	            		{
	            			echo "<option value='".$skill_id."'>".$skill_name."</option>";
	            		}

	            		$result->close();
						}
						
					?>
				</select><br />
				<input type="submit" name="submit" value="add" class="add-btn" />
			</form>
		</div>
		</div>

		<script>
		function checkForm()
		{
			var skill = document.getElementById("skill").value;
			if (skill == "")
			{
				alert("please select a skill");
				return false;
			}

			return true;
		}
		</script>
	</body>
</html>