<?php
if(empty($_SESSION))
{
    session_start();
}

//echo $_SESSION['user_id']."<br />"; //debug
//echo $_SESSION['course_code']; //debug
//echo $_SESSION['course_name'];

include 'conn.php';
?>

<?php
if (isset($_POST['submit']))
{
	unset($_POST['submit']);

	$grade = htmlspecialchars($_POST['grade']);
	$year = htmlspecialchars($_POST['year']);
	$semester = htmlspecialchars($_POST['semester']);
	$prof = htmlspecialchars($_POST['prof']);
	$university = htmlspecialchars($_POST['university']);
	$rate = htmlspecialchars($_POST['rate']);

	//echo $grade." ".$year." ".$semester." ".$prof." ".$university." ".$rate;//debug

	$query1 = "INSERT INTO GRADE_REPORT(Numeric_grade, Semester_year, Semester_season, Professor, User_id, University_code, Course_code)
				VALUES(".$grade.", ".$year.", '".$semester."', '".$prof."', ".$_SESSION['user_id'].", ".$university.", ".$_SESSION['course_code'].")";
	$query2 = "INSERT INTO RATE(User_id, Course_code, Score) VALUES(".$_SESSION['user_id'].", ".$_SESSION['course_code'].", ".$rate.")";
	//echo $query1; //debug
	//echo $query2; //debug
	$mysqli->query($query1);
	$mysqli->query($query2);
	header("refresh:0;url=home.php");
}
?>

<html>
	<head></head>

	<body>
		<h1>report your grade on <?php echo $_SESSION['course_name'] ?> and rate it:</h1>
		<form method="POST" onsubmit="return checkForm()" action="grade_report.php">
			<label>numeric grade:</label><input type="number" name="grade" id="grade" placeholder="numeric grade between 0 and 100" /><br >
			<label>year:</label>
			<select name="year" id="year">
				<option value="2015">2015</option>
				<option value="2014">2014</option>
				<option value="2013">2013</option>
				<option value="2012">2012</option>
				<option value="2011">2011</option>
				<option value="2010">2010</option>
				<option value="2009">2009</option>
				<option value="2008">2008</option>
			</select><br />
			<label>semester</label>
			<select name="semester" id="semester">
				<option value="spring">spring</option>
				<option value="summer">summer</option>
				<option value="fall">fall</option>
				<option value="winter">winter</option>
			</select><br />
			<label>professor name:</label><input type="text" name="prof" id="prof" placeholder="professor name" /><br />
			<label>university</label>
			<select name="university" id="university">
				<?php
					$query = "SELECT Uni_code, Uni_name 
					 FROM UNIVERSITY AS u, ARRANGE AS a
					 WHERE a.Course_code=".$_SESSION['course_code']." AND a.University_code=u.Uni_code";
            		$result = $mysqli->prepare($query);
            		$result->execute();
            		$result->bind_result($uni_code, $uni_name);

            		while ($result->fetch())
            		{
            			echo "<option value='".$uni_code."'>".$uni_name."</option>";
            		}
				?>
			</select><br />
			<label>rate on this course:</label>
			<select name="rate" id="rate">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select><br />
			<br />
			<input type="submit" name="submit" value="report" />
		</form>

		<script>
        	function checkForm()
        	{
        		var grade = document.getElementById("grade").value;
        		var prof = document.getElementById("prof").value;

        		if (grade == "" || prof == "")
        		{
        			alert("please complete the input!");
        			return false;
        		}

        		return true;
        	}
    	</script>
	</body>
</html>