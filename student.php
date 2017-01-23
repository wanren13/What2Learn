<?php
if(empty($_SESSION))
{
    session_start();
}

if(!isset($_SESSION['username']))
{ //if not yet logged in
    header("Location: index.php");// send to login page
    exit;
}

$user_id = $_GET['id'];

include 'conn.php';

$sql = "SELECT First_name, Middle_name, Last_name, Birthday, Country, Gender FROM STUDENT WHERE User_id=".$user_id;

$result = $mysqli->prepare($sql);
$result->execute();
$result->bind_result($first_name, $middle_name, $last_name, $birthday, $country, $gender);
$result->fetch();
$result->close();
?>

<html>
    <head>
        <link type="text/css" rel="stylesheet" href="css/base.css">
        <link type="text/css" rel="stylesheet" href="css/base2.css">
    </head>

    <body>
    	<div class="wrapper">
			<?php include 'header.php' ?>

    		<div>
                <h1><?php echo $first_name." ".$middle_name." ".$last_name ?></h1>
      		</div>

      		<ul id="stu_info">
      			<li><?php echo $birthday."," ?></li>
      			<li><?php echo $country."," ?></li>
      			<li><?php echo $gender ?></li>
      		</ul>
                  

                  <hr />

                  <style>
                        #stu_info {
                              display: inline-block;
                        }

                        #stu_info li{
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
                  </style>

      		<div class="block skill_set">
      			<h3>Skill Set</h3>
      			<?php
                    $query = "SELECT Skill_name, s.Skill_id
                    FROM SKILL AS s, POSSESS AS pos
                    WHERE s.Skill_id = pos.Skill_id AND pos.User_id=".$user_id;

                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($skill_name, $skill_id);

                    echo "<ul>";
                    while ($result->fetch())
                    {
                        echo "<li><a href='skill.php?id=".$skill_id."'>";
                        echo $skill_name;
                        echo "</a></li>";
                    }
                    echo "</ul>";
                ?>
      		</div>

      		<div class="block course">
      			<h3>Course List</h3>
      			<?php
                    //get course list
                    $query = "SELECT g.Course_code, Course_name, Numeric_grade, Semester_year, Semester_season, Professor, Uni_name, g.University_code 
                                FROM COURSE AS c, UNIVERSITY AS u, GRADE_REPORT AS g 
                                WHERE g.User_id =".$_SESSION['user_id']." AND g.University_code = u.Uni_code AND g.Course_code = c.Course_code";
                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($course_code, $course_name, $grade, $year, $season, $prof, $uni_name, $uni_code);
                ?>
                <ul>
                    <?php
                        while ($result->fetch())
                        {
                    ?>
                    <li>
                        <a href='course.php?code=<?php echo $course_code;?>'>
                        <div class="course-basic-info">
                            <span class="course-name"><?php echo $course_name;?></span>
                            <span class="course-grade" style="display: block"><?php echo $grade;?></span>
                            <span><a class="del-grade" style="display: none" href='del_grade.php?course_code=<?php echo $course_code; ?>&uni_code=<?php echo $uni_code; ?>'>[-]</a></span>
                        </div>
                        <div class="course-extra">
                            <span class="course-time"><?php echo $year;?>-<?php echo $season;?></span>
                            <span class="course-instructor">Prof: <?php echo $prof;?></span>
                            <span class="course-university"><?php echo $uni_name;?></span>
                        </div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <style>
                .block.course ul{
				    padding: 0;
				    margin: 0;
				}

				.block.course ul li {
				    display: block;
				    padding: 10px 15px 5px 5px;
				    color: #000;
				    border-bottom: 1px solid rgba(0,0,0,0.1);
				}

				.block.course ul li a {
				    text-decoration: none;
				    color: #000;
				    /*box-shadow: 0 0 2px #000;*/
				}

				.course-basic-info{
				    color: #333;
				    position: relative;
				}


				.course-grade{
				    display: block;
				    float: right;
				    font-size: 24px;
				    color: #333;
				    font-weight: bold;
				}

				.course-extra{
				    color: #66696a;
				    font-size: 12px;
				}

				.course-name {
				    font-size: 16px;
				}

				.course-time, .course-instructor, .course-university {
				    padding: 0 2px;
				    display: inline-block;
				}

				.course-time{
				    width: 15%;
				}

				.course-instructor{
				    width: 25%;
				}

				.course-university{
				    width: 25%;
				}
                </style>
      		</div>

      		<div class="block">
      			<h3>School Attended</h3>
      			<?php
	                //get university list
	                $query = "SELECT Uni_code, Uni_name, Dept_code, Dept_name, Attend_time FROM ATTEND AS a, UNIVERSITY AS u, DEPARTMENT AS d WHERE a.User_id =".$_SESSION['user_id']." AND a.University_code = u.Uni_code AND a.Department_code = d.Dept_code";
	                $result = $mysqli->prepare($query);
	                $result->execute();
	                $result->bind_result($uni_code, $uni_name, $dept_code, $dept_name, $attend_time);

	                echo "<ul>";
	                while ($result->fetch())
	                {
						                        
						echo "<li><a href='university.php?code=".$uni_code."'>";
	                    echo $uni_name." | ";
	                    echo $dept_name." | ";
	                    echo $attend_time;
	                    echo "</a>";
	                    echo "</li>";

	                }
					
	                echo "</ul>";
                ?>
      		</div>

      		<div class="block">
      			<h3>Job Experence</h3>
      			<?php
	                //get company list
	                $query = "SELECT Co_id, Co_name, Start_time, End_time, Salary, w.Pos_code, Pos_name
	                            FROM WORKS_AT AS w, COMPANY AS c, POSITION AS p
	                            WHERE w.User_id =".$_SESSION['user_id']." AND w.Company_id = c.co_id AND w.Pos_code = p.Pos_code";
	                $result = $mysqli->prepare($query);
	                $result->execute();
	                if($result) {
	                    $result->bind_result($co_id, $co_name, $start, $end, $salary, $pos_code, $pos_name);
	                }

	                echo "<ul>";
	                while ($result->fetch())
	                {
	                    echo "<li><a href='company.php?code=".$co_id."'>";
	                    echo $co_name." | ".$pos_name." | ";
	                    echo $start." | ";
	                    echo $end." | ";
	                    echo $salary;
	                    echo "</a>";
	                    echo "</li>";
	                }
	                echo "</ul>";
                ?>
      		</div>


    	</div>
    </body>
</html>
