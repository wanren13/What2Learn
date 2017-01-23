<?php
if(empty($_SESSION))
{
    session_start();
}

$co_code = $_GET['code'];

include 'conn.php';
$query = "SELECT Co_name, Country, State, City, Postcode FROM COMPANY WHERE Co_id=".$co_code;
$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($co_name, $country, $state, $city, $postcode);
$result->fetch();
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
                        <h1><?php echo $co_name?></h1>
      		</div>
      		<div>
      			
      			<h2>CODE: <?php echo $co_code?></h2>
      		</div>

      		<ul id="co_info">
      			<li><?php echo $country."," ?></li>
      			<li><?php echo $state."," ?></li>
      			<li><?php echo $city ?></li>
      			<li><?php echo $postcode ?></li>
                        
                        <a href='co_report.php?code=<?php echo $co_code ?>&name=<?php echo $co_name ?>'>Join Company</a>
      		</ul>
                  

                  <hr />

                  <style>
                        #co_info {
                              display: inline-block;
                        }

                        #co_info li{
                              width: auto;
                              font-size: 13px;
                              line-height: 17px;
                              color: #66696a;
                              font-weight: normal;
                              text-decoration: none;
                              display: inline-block;
                              background: none;
                              border: none;
                              padding-left: 0;
                              padding-top: 0;
                              padding-bottom: 0;
                        }

                        #co_info a {
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
                        }
                  </style>

      		<div class="block skill_set">
      			<h3>Position List</h3>
      			<?php

                  $query = "SELECT Pos_name 
                  			FROM POSITION AS p, OFFER AS o
                  			WHERE o.Company_id=".$co_code." AND o.Position_code=p.Pos_code";
                  $result = $mysqli->prepare($query);
                  $result->execute();
                  $result->bind_result($pos_name);

                  echo "<ul>";
                  while ($result->fetch())
                  {
                      echo "<li>";
                      echo $pos_name;
                      echo "</li>";
                  }
                  echo "</ul>";
                  $result->close();
                  ?>
      		</div>

      		<div class="block">
      			<h3>Employee Roster</h3>
      			<?php

                  $query = "SELECT s.User_id, First_name, Middle_name, Last_name 
                  			FROM STUDENT AS s, WORKS_AT AS w
                  			WHERE w.Company_id=".$co_code." AND w.User_id=s.User_id";
                  $result = $mysqli->prepare($query);
                  $result->execute();
                  $result->bind_result($stu_id, $first_name, $middle_name, $last_name);

                  echo "<ul>";
                  while ($result->fetch())
                  {
                      echo "<li><a href='student.php?id=".$stu_id."'>";
                      echo $first_name." ".$middle_name." ".$last_name;
                      echo "</a></li>";
                  }
                  echo "</ul>";
                  $result->close();
                  ?>
      		</div>

      		<div class="block">
      			<h3>Gender Distribution</h3>
      			<?php

                  $query = "SELECT COUNT(Gender), Gender 
                  			FROM STUDENT AS s, WORKS_AT AS w
                  			WHERE w.Company_id=".$co_code." AND w.User_id=s.User_id
                  			GROUP BY Gender
                  			ORDER BY Gender";
                  $result = $mysqli->prepare($query);
                  $result->execute();
                  $result->bind_result($gender_num, $gender);

                  /*echo "<ul>";*/
                  $gender_num_f = 0;
                  $gender_num_m = 0;
                  while ($result->fetch())
                  {
                        if ($gender == "Female") 
                        {
                              $gender_num_f = $gender_num;
                        }
                        if ($gender == "Male") 
                        {
                              $gender_num_m = $gender_num;
                        }

                  }

                  $result->close();
                  ?>
                  <script type="text/javascript">
                          google.load("visualization", "1", {packages:["corechart"]});
                          google.setOnLoadCallback(drawChart);
                          function drawChart()
                          {
                              var data = google.visualization.arrayToDataTable([                              
                                ['Sex', 'Number of Person'],
                                ['Male',    <?php echo $gender_num_m; ?>],
                                ['Female',  <?php echo $gender_num_f; ?>]                               
                              ]);

                              var options = {
                                title: 'Sex Ratio',
                              };

                              var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                              chart.draw(data, options);
                          }
                  </script>
                        <div id="piechart" style="width: 900px; height: 500px;"></div>
      		</div>

      		<div class="block">
      			<h3>University Distribution</h3>
      			<?php

                  $query = "SELECT Uni_name, COUNT(Uni_name)
      						FROM UNIVERSITY AS u, WORKS_AT AS w, ATTEND AS a 
      						WHERE w.Company_id=".$co_code." AND w.User_id=a.User_id AND a.University_code=u.Uni_code
      						GROUP BY u.Uni_code;";
                  $result = $mysqli->prepare($query);
                  $result->execute();
                  $result->bind_result($uni_name, $uni_num);

                  $uni_name_array = array();
                  $uni_num_array = array();

                  $i = 0;
                  $total = 0;
                  echo "<ul>";
                  while ($result->fetch())
                  {
                  	$uni_name_array[$i] = $uni_name;
                  	$uni_num_array[$i] = $uni_num;
                  	$total += $uni_num;
                  	$i++;
                  }

                  /*for ($j = 0; $j < $i; $j++)
                  {
                  	echo "<li>";
                  	echo $uni_name_array[$j]."(".($uni_num_array[$j]/$total).")";
                  	echo "</li>";
                  }
                  echo "</ul>";*/
                  $result->close();
                  ?>
                  <script>
                        google.load("visualization", "1", {packages:["corechart"]});
                        google.setOnLoadCallback(drawChart);

                        function drawChart() {
                              var data = google.visualization.arrayToDataTable([
                                    ['Unversity', 'Population'],
                                    <?php
                                          for ($j = 0; $j < $i; $j++)
                                          {
                                                echo "['".$uni_name_array[$j]."',";
                                                echo $uni_num_array[$j]."],";  
                                          }
                                    ?>
                                    ]);

                              //alert();
                              var options = {
                                title: 'University Distribution',
                                chartArea: {width: '50%'},
                                hAxis: {
                                            title: 'Total number of students',
                                            minValue: 0
                                          },
                                          vAxis: {
                                            title: 'Universities'
                                          },
                              };

                              var chart = new google.visualization.BarChart(document.getElementById('barchart'));
                              chart.draw(data, options);
                        }
                  </script>
                  <div id="barchart" style="width: 900px; height: 500px; margin-left: 10px"></div>
      		</div>

      		<div class="block skill_set">
      			<h3>Most Required Skills</h3>
      			<?php

                  /* this shit is crazy, but needless */
                  /*$query = "SELECT s.*, COUNT(s.Skill_id) FROM
      						(
      						SELECT s.Skill_name, s.Skill_id
      						FROM SKILL AS s, WORKS_AT AS w, POSSESS AS pos
      						WHERE w.Company_id=".$co_code." AND w.User_id=pos.User_id AND pos.Skill_id=s.Skill_id
      						UNION ALL
      						SELECT s.Skill_name, s.Skill_id
      						FROM SKILL AS s, WORKS_AT AS w, PROVIDE AS pro, GRADE_REPORT AS g 
      						WHERE w.Company_id=".$co_code." AND w.User_id=g.User_id AND g.Course_Code=pro.Course_code AND pro.Skill_id=s.Skill_id
      						) AS s
      						GROUP BY s.Skill_id
      						ORDER BY COUNT(s.Skill_id) DESC
      						LIMIT 10;";*/
                  $query = "SELECT s.Skill_name, s.Skill_id, COUNT(s.Skill_id)
                              FROM SKILL AS s, WORKS_AT AS w, POSSESS AS pos
                              WHERE w.Company_id=".$co_code." AND w.User_id=pos.User_id AND pos.Skill_id=s.Skill_id
                              GROUP BY s.Skill_id
                              ORDER BY COUNT(s.Skill_id) DESC
                              LIMIT 10;";
                  $result = $mysqli->prepare($query);
                  $result->execute();
                  $result->bind_result($skill_name, $skill_id, $skill_count);

                  echo "<ul>";
      			while ($result->fetch())
                  {
      	        	echo "<li>";
      	        	echo "<a href='' title='CODE:".$skill_id.", HEAD COUNT:".$skill_count."'>".$skill_name."(".$skill_count.")</a>";
      	        	echo "</li>";
                  }
                  echo "</ul>";
                  $result->close();
                  ?>
      		</div>

      		<div class="block">
      			<h3>Most Required Courses</h3>
      			<?php

                  $query = "SELECT c.Course_code, c.Course_name, COUNT(c.Course_code)
      						FROM COURSE AS c, WORKS_AT AS w, GRADE_REPORT AS g 
      						WHERE w.Company_id=".$co_code." AND w.User_id=g.User_id AND g.Course_code=c.Course_code
      						GROUP BY c.Course_code
      						ORDER BY COUNT(c.Course_code) DESC
      						LIMIT 10;";
                  $result = $mysqli->prepare($query);
                  $result->execute();
                  $result->bind_result($course_code, $course_name, $course_count);

                  echo "<ul>";
      			while ($result->fetch())
                  {
      	        	echo "<li><a href='course.php?code=".$course_code."'>";
      	        	echo $course_count." chose ".$course_name;
                        echo "<span class=\"sub_title\">(CODE:".$course_code.")</span>";
      	        	echo "</a></li>";
                  }
                  echo "</ul>";
                  $result->close();
                  ?>
      		</div>
            </div>

	</body>

</html>