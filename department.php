<?php
if(empty($_SESSION))
{
    session_start();
}

$department_code = $_GET['code'];
$_SESSION['department_code'] = $department_code;

include 'conn.php';
$query = "SELECT Dept_name FROM DEPARTMENT WHERE Dept_code=".$department_code;
// echo $query;
$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($department_name);
$result->fetch();
$_SESSION['department_name'] = $department_name;
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
	            <h1><?php echo $department_name ?></h1>
	        </div>
	        	
	        <hr />

			<div class="block">
	            <h3>Number of Users Have Attend This Department</h3>
	            <?php
	            $query = "SELECT COUNT(*) FROM DEPARTMENT, ATTEND WHERE DEPARTMENT.Dept_code = ATTEND.Department_code AND DEPARTMENT.Dept_code = ".$department_code;
	            $result = $mysqli->prepare($query);
	            $result->execute();
	            $result->bind_result($scount);
	            $result->fetch();
	            echo "  ".$scount;
	            $result->close();
	            ?>
	        </div>

	        <div class="block">
	            <h3>Universities Have This Department</h3>
	            <?php
	            $query = "SELECT UNIVERSITY.Uni_name FROM UNIVERSITY, HAS WHERE UNIVERSITY.Uni_code = HAS.University_code AND HAS.Department_code = ".$department_code;
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($uname);
				?>
				<ul>			
				<?php	
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><?php echo $uname; ?></li>
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
	            <h3>Most Popular Courses in This Department</h3>
	            <?php			
	            $query = "SELECT COURSE.Course_name, COUNT(*) FROM ATTEND, GRADE_REPORT, COURSE WHERE ATTEND.User_id = GRADE_REPORT.User_id AND ATTEND.Department_code = ".$department_code." AND GRADE_REPORT.Course_code = COURSE.Course_code GROUP BY COURSE.Course_code ORDER BY COUNT(*) DESC LIMIT 5";
				$result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($cname, $ccount);
	            ?>				
				<script type="text/javascript">
					google.load('visualization', '1', {packages: ['corechart', 'bar']});
					google.setOnLoadCallback(drawBasic);

					function drawBasic() {
						  var data = google.visualization.arrayToDataTable([						  
						  ['courses name', 'num of taken',],
						  <?php
							$i = 1;
							while ($result->fetch()) {
								if ($i != $result->num_rows) {
									echo "['".$cname."', ".$ccount."],";
								}
								else {
									echo "['".$cname."', ".$ccount."]";
								}
								$i = $i + 1;
							}
							$result->close();
						  ?>
						  ]);
						  var options = {
							title: 'Popular Courses in This Department',
							chartArea: {width: '50%'},
							hAxis: {
							  title: 'Total number of taken',
							  minValue: 0
							},
							vAxis: {
							  title: 'Courses'
							}
						  };

						  var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));

						  chart.draw(data, options);
						}
				</script>
				<div id="chart_div1" style="width: 900px; height: 300px;"></div>				
	        </div>
			<div class="block">
	            <h3>Most Popular Positions Have Been Applied by Students Graduated From This Department</h3>
	            <?php
	            $query = "SELECT POSITION.Pos_name, COUNT(*) FROM WORKS_AT, ATTEND, POSITION WHERE WORKS_AT.Pos_code = POSITION.Pos_code AND WORKS_AT.User_id = ATTEND.User_id AND ATTEND.Department_code = ".$department_code." GROUP BY POSITION.Pos_code ORDER BY COUNT(*) DESC LIMIT 5";
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($pname, $pcount);
	           ?>				
				<script type="text/javascript">
					google.load('visualization', '1', {packages: ['corechart', 'bar']});
					google.setOnLoadCallback(drawBasic);

					function drawBasic() {
						  var data = google.visualization.arrayToDataTable([						  
						  ['position name', 'num of people',],
						  <?php
							$i = 1;
							while ($result->fetch()) {
								if ($i != $result->num_rows) {
									echo "['".$pname."', ".$pcount."],";
								}
								else {
									echo "['".$pname."', ".$pcount."]";
								}
								$i = $i + 1;
							}
							$result->close();
						  ?>
						  ]);
						  var options = {
							title: 'Popular Positions Have Been Applied by Students Graduated From This Department',
							chartArea: {width: '50%'},
							hAxis: {
							  title: 'Total number of people',
							  minValue: 0
							},
							vAxis: {
							  title: 'Positions'
							}
						  };

						  var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));

						  chart.draw(data, options);
						}
				</script>
				<div id="chart_div2" style="width: 900px; height: 300px;"></div>	
	        </div>
	      </div>
    </body>
</html>