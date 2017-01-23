<?php
if(empty($_SESSION))
{
    session_start();
}
$skill_id = $_GET['id'];
$_SESSION['skill_id'] = $skill_id;

include 'conn.php';
$query = "SELECT Skill_name FROM SKILL WHERE Skill_id=".$skill_id;
//echo $query;
$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($skill_name);
$result->fetch();
$_SESSION['skill_name'] = $skill_name;
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
	            <h1><?php echo $skill_name ?></h1>
	        </div>
	        <div>
	            <h2>CODE: <?php echo $skill_id ?></h2>
	        </div>
			
	        <hr />
	        
        	<?php

        		$query = "SELECT *
							FROM POSSESS
							WHERE User_id=".$_SESSION['user_id']." AND Skill_id=".$skill_id;
				$result = $mysqli->query($query);

				if ($result->num_rows > 0)
				{
			?>
				<div class="block" style="padding: 10px">
				<span>You Have This Skill!</span>
				<a href="del_skill.php?id=<?php echo $skill_id ?>" class="operate">Delete Skill</a>
				</div>
			<?php
				}
				else
				{
			?>
				<div class="fake">
				<a href="skill_report.php?id=<?php echo $skill_id ?>&name=<?php echo $skill_name ?>" class="operate" >Add This Skill</a>
				</div>
			<?php
				}
        	?>
	        

			<style>
                .operate {
                  display: inline-block;
                  text-decoration: none;
                  border: 1px solid;
                  font-size: 13px;
                  padding: 6px;
                  background-color: #2672ae;
                  border: #1e4f7e solid 1px;
                  box-shadow: 0 1px 3px rgba(0,0,0,0.25);
                  font-weight: bold;
                  color: white;
                  border-radius: 3px;
                  margin-left: 5px;
                  }

                  .fake a:visted {
                  	color: white;
                  }

                  .fake a:hover {
                  	color: white;
                  }

                  .fake a:link {
                  	color: white;
                  }

                  .fake a:active {
                  	color: white;
                  }
            </style>

			<div class="block">
	            <h3>Courses Provide This Skill:</h3>
	            <?php
	            $query = "SELECT COURSE.Course_code, COURSE.Course_name FROM COURSE, PROVIDE WHERE COURSE.Course_code = PROVIDE.Course_code AND PROVIDE.Skill_id = ".$skill_id." LIMIT 5";
				// echo $query;
	            $result = $mysqli->prepare($query);
	            $result->execute();
				$result->store_result();
	            $result->bind_result($ccode, $cname);
				?>	
				<ul>			
				<?php	
				if($result->num_rows) { 
					while ($result->fetch()) { ?>
						<li><a href="course.php?code=<?php echo $ccode ?>"><?php echo $cname; ?></a></li>
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
	            <h3>Most Popular Positions Have Been Applied With This Skill</h3>
	            <?php
	            $query = "SELECT POSITION.Pos_name, COUNT(*) FROM POSITION, WORKS_AT, POSSESS WHERE POSITION.Pos_code = WORKS_AT.Pos_code AND WORKS_AT.User_id = POSSESS.User_id AND POSSESS.Skill_id = ".$skill_id." GROUP BY WORKS_AT.Pos_code ORDER BY COUNT(*) DESC LIMIT 5";
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
						  ['Position name', 'num of students',],
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
							title: 'Most Popular Positions Have Been Applied With This Skill',
							chartArea: {width: '50%'},
							hAxis: {
							  title: 'Total number of people',
							  minValue: 0
							},
							vAxis: {
							  title: 'Postions'
							}
						  };

						  var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));

						  chart.draw(data, options);
						}
				</script>
				<div id="chart_div1" style="width: 900px; height: 300px;"></div>	
	        </div>
	        <div class="block">
	            <h3>Companies to Work With This Skill</h3>
	            <?php			
	            $query = "SELECT COMPANY.Co_name, COUNT(*) FROM COMPANY, WORKS_AT, POSSESS WHERE COMPANY.Co_id = WORKS_AT.Company_id AND WORKS_AT.User_id = POSSESS.User_id AND POSSESS.Skill_id = ".$skill_id." GROUP BY WORKS_AT.Company_id ORDER BY COUNT(*) DESC LIMIT 5";
				// echo $query;
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
						  ['Company name', 'num of students',],
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
							title: 'Companies to Work With This Skill',
							chartArea: {width: '50%'},
							hAxis: {
							  title: 'Total number of people',
							  minValue: 0
							},
							vAxis: {
							  title: 'Companies'
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