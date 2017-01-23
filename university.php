<?php
if(empty($_SESSION))
{
    session_start();
}

$university_code = $_GET['code'];
$_SESSION['university_code'] = $university_code;

include 'conn.php';
$query = "SELECT Uni_name FROM UNIVERSITY WHERE Uni_code=".$university_code;
echo $query;
$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($university_name);
$result->fetch();
$_SESSION['university_name'] = $university_name;
//echo $_SESSION['university_name']; //debug
$result->close();
?>

<html>
	<head>
		<link type="text/css" rel="stylesheet" href="css/base.css">
		<link type="text/css" rel="stylesheet" href="css/base2.css">
		<script type="text/javascript" src="./script/jsapi.js"></script>		
	</head>
    <body>
		<?php include 'header.php' ?>

    	<div class="wrapper">
	        <div>
	            <h1><?php echo $university_name ?></h1>
	        </div>
	        <div>
	            <h2>CODE: <?php echo $university_code ?></h2>
	        </div>
			
	        <hr />

			<div class="block">
	            <h3>Address:</h3>
	            <?php
	            $query = "SELECT City, State, Postcode FROM UNIVERSITY WHERE Uni_code=".$university_code;
	            $result = $mysqli->prepare($query);
	            $result->execute();
	            $result->bind_result($city, $state, $postcode);
	            $result->fetch();
	            echo $city.", ".$state.", ".$postcode;
	            $result->close();
	            ?>
	        </div>

	        <div class="block">
	            <h3>Department List</h3>
	            <?php
	            $query = "SELECT DEPARTMENT.Dept_code, Dept_name FROM DEPARTMENT, HAS WHERE DEPARTMENT.Dept_code=HAS.Department_code AND HAS.University_code=".$university_code;
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($dept_code, $deptname);
				?>
				<ul>			
				<?php	
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><a href="department.php?code=<?php echo $dept_code ?>"><?php echo $deptname; ?></a></li>
				<?php
					}
				}
				else {
					echo "No record";
				}			
				?>
	            </ul>
				<?php $result->close();?>
	        </div>
	        <div class="block">
	            <h3>Sex Ratio</h3>
	            <?php			
	            $query_m = "SELECT COUNT(*) FROM ATTEND, UNIVERSITY, STUDENT WHERE ATTEND.University_code = UNIVERSITY.Uni_code AND ATTEND.User_id = STUDENT.User_id AND STUDENT.Gender = 'Male' AND UNIVERSITY.Uni_code = ".$university_code;

	            $result = $mysqli->prepare($query_m);
				$result->execute();
	            $result->bind_result($male);
				$result->fetch();
				$result->close();
				
				$query_f = "SELECT COUNT(*) FROM ATTEND, UNIVERSITY, STUDENT WHERE ATTEND.University_code = UNIVERSITY.Uni_code AND ATTEND.User_id = STUDENT.User_id AND STUDENT.Gender = 'Female' AND UNIVERSITY.Uni_code = ".$university_code;
	            $result = $mysqli->prepare($query_f);
	            $result->execute();
	            $result->bind_result($female);
				$result->fetch();
				$result->close();
				?>
				<script type="text/javascript">
				  google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {

					var data = google.visualization.arrayToDataTable([					  
					  ['Sex', 'Number of Person'],
					  ['Male',    <?php echo $male; ?>],
					  ['Female',  <?php echo $female; ?>]					  
					]);

					var options = {
					  title: 'Sex Ratio'
					};
					var chart = new google.visualization.PieChart(document.getElementById('piechart'));
					chart.draw(data, options);
				  }
				</script>
				<div id="piechart" style="width: 900px; height: 500px;"></div>
	        </div>
			<div class="block">
	            <h3>Popular Courses</h3>
	            <?php
	            $query = "SELECT COURSE.Course_name, COUNT(*) FROM GRADE_REPORT, COURSE WHERE GRADE_REPORT.University_code = ".$university_code." AND GRADE_REPORT.Course_code = COURSE.Course_code GROUP BY GRADE_REPORT.Course_code ORDER BY COUNT(*) DESC LIMIT 5";
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($cname, $ccount);
	            ?>
				<ul>			
				<?php	
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><?php echo $cname." ".$ccount; ?></li>
				<?php
					}
				}
				else {
					echo "No record";
				}			
				?>
	            </ul>
				<?php $result->close();?>
	        </div>
			<div class="block">
	            <h3>Alumni</h3>
	            <?php
	            $query = "SELECT STUDENT.User_id, First_name, Last_name, Gender, Country FROM STUDENT, ATTEND WHERE STUDENT.User_id = ATTEND.User_id AND ATTEND.University_code = ".$university_code." AND ATTEND.User_id <> ".$_SESSION['user_id'];
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($stu_id, $fname, $lname, $gender, $country);
	            ?>
				<ul>			
				<?php	
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><a href="student.php?id=<?php echo $stu_id ?>"><?php echo $fname." ".$lname." ".$gender." ".$country; ?></a></li>
				<?php
					}
				}
				else {
					echo "No record";
				}			
				?>
	            </ul>
				<?php $result->close();?>
	        </div>
			<div class="block">
	            <h3>Graduate Job Distribution</h3>
	            <?php
	            $query = "SELECT COMPANY.Co_name FROM COMPANY, WORKS_AT, ATTEND WHERE COMPANY.Co_id = WORKS_AT.Company_id AND ATTEND.User_id = WORKS_AT.User_id AND ATTEND.University_code = ".$university_code." GROUP BY WORKS_AT.Company_id ORDER BY COUNT(*) DESC LIMIT 5";
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($cname);
	            ?>
				<ul>
				<?php			
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><?php echo $cname; ?></li>
				<?php
					}
				}
				else {
					echo "No record";
				}			
				?>
	            </ul>
				<?php $result->close();?>
	        </div>
	      </div>
    </body>
</html>