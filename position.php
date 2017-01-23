<?php
if(empty($_SESSION))
{
    session_start();
}
$position_code = $_GET['code'];
$_SESSION['pos_code'] = $position_code;

include 'conn.php';
$query = "SELECT Pos_name FROM POSITION WHERE Pos_code=".$position_code;
$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($position_name);
$result->fetch();
$_SESSION['pos_name'] = $position_name;
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
	            <h1><?php echo $position_name ?></h1>
	        </div>
	        <div>
	            <h2>CODE: <?php echo $position_code ?></h2>
	        </div>
			
	        <hr />

			<div class="block">
	            <h3>Employee:</h3>
	            <?php
	            $query = "SELECT STUDENT.First_name, STUDENT.Last_name, COMPANY.Co_name, WORKS_AT.Start_time, WORKS_AT.End_time FROM STUDENT, WORKS_AT, COMPANY WHERE STUDENT.User_id = WORKS_AT.User_id AND WORKS_AT.Company_id = COMPANY.Co_id AND WORKS_AT.Pos_code = ".$position_code." LIMIT 5";
				// echo $query;
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($fname, $lname, $cname, $starttime, $endtime);
				?>	
				<ul>			
				<?php	
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><?php echo $fname."	".$lname."	".$cname."	".$starttime."	".$endtime; ?></li>
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
	            <h3>Offered By</h3>
	            <?php
	            $query = "SELECT COMPANY.Co_name FROM OFFER, COMPANY WHERE OFFER.Company_id = COMPANY.Co_id AND OFFER.Position_code = ".$position_code." LIMIT 5";
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
	        <div class="block">
	            <h3>University Distribution</h3>
	            <?php			
	            $query = "SELECT UNIVERSITY.Uni_name, COUNT(*) FROM WORKS_AT, ATTEND, UNIVERSITY WHERE WORKS_AT.User_id = ATTEND.User_id AND ATTEND.University_code = UNIVERSITY.Uni_code AND WORKS_AT.Pos_code = ".$position_code." GROUP BY UNIVERSITY.Uni_code ORDER BY COUNT(*) DESC LIMIT 5";
				// echo $query;
				$result = $mysqli->prepare($query);
	            $result->execute();
	            $result->store_result();
	            $result->bind_result($uname, $ucount);
				?>				
				<script type="text/javascript">
					google.load('visualization', '1', {packages: ['corechart', 'bar']});
					google.setOnLoadCallback(drawBasic);

					function drawBasic() {
						  var data = google.visualization.arrayToDataTable([						  
						  ['university name', 'num of people',],
						  <?php
							$i = 1;
							while ($result->fetch()) {
								if ($i != $result->num_rows) {
									echo "['".$uname."', ".$ucount."],";
								}
								else {
									echo "['".$uname."', ".$ucount."]";
								}
								$i = $i + 1;
							}
							$result->close();
						  ?>
						  ]);
						  var options = {
							title: 'University Distribution',
							chartArea: {width: '50%'},
							hAxis: {
							  title: 'Total number of people',							  
					//		  format: '#',
					//		  gridlines: { count: 1},
							  minValue: 0
							},
							vAxis: {
							  title: 'Uiniversites'
							}
						  };

						  var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));

						  chart.draw(data, options);
						}
				</script>
				<div id="chart_div1" style="width: 900px; height: 300px;"></div>	
	        </div>
			<div class="block">
	            <h3>Most Popular Courses to have this position</h3>
	            <?php
	            $query = "SELECT COURSE.Course_name, COUNT(*) FROM COURSE, WORKS_AT, GRADE_REPORT WHERE COURSE.Course_code = GRADE_REPORT.Course_code AND WORKS_AT.User_id = GRADE_REPORT.User_id AND WORKS_AT.Pos_code = ".$position_code." GROUP BY COURSE.Course_code ORDER BY COUNT(*) DESC LIMIT 10";
	            $result = $mysqli->prepare($query);
	            $result->execute();
	            $result->bind_result($cname, $scount);
	            ?>				
				<script type="text/javascript">
					google.load('visualization', '1', {packages: ['corechart', 'bar']});
					google.setOnLoadCallback(drawBasic);

					function drawBasic() {
						  var data = google.visualization.arrayToDataTable([						  
						  ['course name', 'num of students',],
						  <?php
							$i = 1;
							while ($result->fetch()) {
								if ($i != $result->num_rows) {
									echo "['".$cname."', ".$scount."],";
								}
								else {
									echo "['".$cname."', ".$scount."]";
								}
								$i = $i + 1;
							}
							$result->close();
						  ?>
						  ]);
						  var options = {
							title: 'Most Popular Courses to Have This Position',
							chartArea: {width: '50%'},
							hAxis: {
							  title: 'Total number of students',
							  minValue: 0
							},
							vAxis: {
							  title: 'Courses'
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