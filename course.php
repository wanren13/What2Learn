<?php
if(empty($_SESSION))
{
    session_start();
}

$course_code = $_GET['code'];
$_SESSION['course_code'] = $course_code;

include 'conn.php';
$query = "SELECT Course_name FROM COURSE WHERE Course_code=".$course_code;
$result = $mysqli->prepare($query);
$result->execute();
$result->bind_result($course_name);
$result->fetch();
$_SESSION['course_name'] = $course_name;
//echo $_SESSION['course_name']; //debug
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
                <h1><?php echo $course_name ?></h1>
            </div>
            <div>
                <h2>CODE: <?php echo $course_code ?></h2>
            </div>


            <hr />

            <div class="block" id="rate" style="padding-bottom: 10px">
                <h3>Average Rate</h3><br />
                <?php
                $query = "SELECT AVG(Score) FROM RATE WHERE Course_code=".$course_code;
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($score);
                $result->fetch();
                $result->close();

                $deltaY = -1;


                if ($score >=4.55)
                {
                    $deltaY = -225;
                }
                else if ($score >= 4.45)
                {
                    $deltaY = -202;
                }
                else if ($score >= 3.55)
                {
                    $deltaY = -180;
                }
                else if ($score >=3.45)
                {
                    $deltaY = -157;
                }
                else if ($score >= 2.55)
                {
                    $deltaY = -136;
                }
                else if ($score >= 2.45)
                {
                    $deltaY = -113;
                }
                else if ($score >= 1.55)
                {
                    $deltaY = -91;
                }
                else if ($score >= 1.45)
                {
                    $deltaY = -68;
                }
                else if ($score >= 0.55)
                {
                    $deltaY = -47;
                }
                else if ($score >= 0.45)
                {
                    $deltaY = -25;
                }
                else if($score >= 0)
                {
                    $deltaY = 0;
                }
                
                if ($deltaY == -1)
                {
                    echo $score;
                }
                else
                {
                    echo "<div id='star' style=\"background:";
                    echo "url('./img/rating-star.png');";
                    echo "background-position: 0 ".$deltaY."px; height: 23px; width: 118px; background-repeat: no-repeat;\" /></div>";
                }
                

                echo "<div style=\"display: inline-block; float: left; padding-top: 2px;\">(".$score.")</div>";
                ?>
                
                <a id="report" href="grade_report.php">report grade</a>
                <style>
                   #star {
                        float: left;
                        display: inline-block;
                        margin-top: 0;
                        padding: 0;
                    }

                    #report {
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
                </style>

                <div id="score_dist">
                    <?php
                        $score = array();

                        $query = "SELECT COUNT(Score) FROM RATE WHERE Course_code=".$course_code." AND Score = 1";
                        $result = $mysqli->prepare($query);
                        $result->execute();
                        $result->bind_result($score[0]);
                        $result->fetch();
                        $result->close();

                        $query = "SELECT COUNT(Score) FROM RATE WHERE Course_code=".$course_code." AND Score = 2";
                        $result = $mysqli->prepare($query);
                        $result->execute();
                        $result->bind_result($score[1]);
                        $result->fetch();
                        $result->close();

                        $query = "SELECT COUNT(Score) FROM RATE WHERE Course_code=".$course_code." AND Score = 3";
                        $result = $mysqli->prepare($query);
                        $result->execute();
                        $result->bind_result($score[2]);
                        $result->fetch();
                        $result->close();

                        $query = "SELECT COUNT(Score) FROM RATE WHERE Course_code=".$course_code." AND Score = 4";
                        $result = $mysqli->prepare($query);
                        $result->execute();
                        $result->bind_result($score[3]);
                        $result->fetch();
                        $result->close();

                        $query = "SELECT COUNT(Score) FROM RATE WHERE Course_code=".$course_code." AND Score = 5";
                        $result = $mysqli->prepare($query);
                        $result->execute();
                        $result->bind_result($score[4]);
                        $result->fetch();
                        $result->close();

                        $total = $score[0] + $score[1] + $score[2] + $score[3] + $score[4];
                    ?>
                    <?php 
                    for ($i = 1; $i <= 5; $i++)
                    {
                    ?>
                    <div id="star_level"><?php echo $i ?> stars</div>
                    <div id="star_bar_out" style="padding: 0">
                        <div id="star_bar_in" style="padding: 0; width: <?php echo round($score[$i-1]/$total*100) ?>px"></div>
                    </div>
                    <div id="star_percent"><?php echo round($score[$i-1]/$total*100) ?>%</div>
                    <br />
                    <?php 
                    }
                    ?>
                    <style>
                        #star_level, #star_percent {
                            font-size: 13px;
                            height: auto;
                            line-height: 19px;
                            border-collapse: collapse;
                            box-sizing: border-box;
                            color: rgb(0, 102, 192);
                        }

                        #star_percent {
                            margin-left: 4px;
                        }

                        #score_dist div{
                            display: inline-block;
                            padding-left: 10px;
                            padding-top: 7px;
                        }

                        #star_bar_out {
                            width: 100px;
                            border-radius: 1px;
                            box-shadow: inset 0 1px 2px rgba(0,0,0,.4),inset 0 0 0 1px rgba(0,0,0,.1);
                            height: 17px;
                            background-color: #f3f3f3;
                            background: linear-gradient(to bottom, #eee,#f6f6f6);
                            margin-left: 6px;
                        }

                        #star_bar_in {
                            border-radius: 1px;
                            box-shadow: inset 0 1px 2px rgba(0,0,0,.4),inset 0 0 0 1px rgba(0,0,0,.1);
                            height: 17px;
                            background-image: linear-gradient(rgb(255, 206, 0), rgb(255, 167, 0));
                            margin-left: 0;
                        }
                    </style>
                </div>
            </div>

            <div class="block">
                <h3>Prerequisite Courses</h3>
                <?php

                $query = "SELECT Course_code, Course_name FROM PREREQUISITE AS p, COURSE AS c WHERE p.Advanced_course_code=".$course_code." AND p.Prerequisite_course_code=c.Course_code";
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($pre_code, $course_name);

                echo "<ul>";
                while ($result->fetch())
                {
                    echo "<li>";
                    echo $course_name."(".$pre_code.")";
                    echo "</li>";
                }
                echo "</ul>";
                $result->close();
                ?>
            </div>

            <div class="block skill_set">
                <h3>Prerequisite Skills</h3>
                <?php
                $query = "SELECT Skill_name, s.Skill_id FROM SKILL AS s, PREREQUISITE AS pre, PROVIDE AS pro WHERE pre.Advanced_course_code=".$course_code." AND pre.Prerequisite_course_code=pro.Course_code AND pro.Skill_id=s.Skill_id";
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($skill_name, $skill_id);

                echo "<ul>";
                while ($result->fetch())
                {
                    echo "<li>";
                    echo "<a href='skill.php?id=".$skill_id."'>";
                    echo $skill_name;
                    echo "</a>";
                    echo "</li>";
                }
                echo "</ul>";
                $result->close();
                ?>
            </div>

            <div class="block skill_set">
                <h3>Skill Provided</h3>
                <?php
                $query = "SELECT Skill_name, s.Skill_id FROM SKILL AS s, PROVIDE AS p WHERE p.Skill_id = s.Skill_id AND p.Course_code=".$course_code;
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($skill_name, $skill_id);

                echo "<ul>";
                while ($result->fetch())
                {
                    echo "<li>";
                    echo "<a href='skill.php?id=".$skill_id."'>";
                    echo $skill_name;
                    echo "</a>";
                    echo "</li>";
                }
                echo "</ul>";
                $result->close();
                ?>
            </div>

            <div class="block">
                <h3>Student Grade Ranking (TOP 10)</h3>
                <?php
                $query = "SELECT s.User_id, First_name, Middle_name, Last_name, Numeric_grade FROM GRADE_REPORT AS g, STUDENT AS s WHERE g.Course_code=".$course_code." AND g.User_id = s.User_id ORDER BY Numeric_grade DESC LIMIT 10";
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($stu_id, $first_name, $middle_name, $last_name, $score);

                echo "<ul>";
                while ($result->fetch())
                {
                    echo "<li><a href='student.php?id=".$stu_id."'>";
                    echo $first_name." ".$middle_name." ".$last_name;
                    echo "<span class=\"sub_title\"> grade: ".$score."</span>";
                    echo "</a></li>";
                }
                echo "</ul>";
                $result->close();
                ?>
            </div>

            <div class="block">
                <h3>Offered By</h3>
                <?php
                $query = "SELECT Dept_name, a.University_code, Uni_name FROM DEPARTMENT AS d, UNIVERSITY AS u, ARRANGE AS a WHERE a.Course_code=".$course_code." AND a.University_code = u.Uni_code AND a.Department_code = d.Dept_code LIMIT 10";
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($dept_name, $uni_code, $uni_name);

                echo "<ul>";
                while ($result->fetch())
                {
                    echo "<li><a href=\"university.php?code=".$uni_code."\">";
                    echo $uni_name;
                    echo "<span class=\"sub_title\"> Dept: ".$dept_name."</span>";
                    echo "</a></li>";
                }
                echo "</ul>";
                $result->close();
                ?>
            </div>

            <div class="block">
                <h3>Gender Distribution</h3>
                <?php
                $query_m = "SELECT COUNT(User_id) FROM GRADE_REPORT WHERE Course_code=".$course_code." AND User_id IN (SELECT User_id FROM STUDENT WHERE Gender='Male')";
                $query_f = "SELECT COUNT(User_id) FROM GRADE_REPORT WHERE Course_code=".$course_code." AND User_id IN (SELECT User_id FROM STUDENT WHERE Gender='Female')";
                $result_m = $mysqli->prepare($query_m);
                $result_m->execute();
                $result_m->bind_result($male);
                $result_m->fetch();
                $result_m->close();

                $result_f = $mysqli->prepare($query_f);
                $result_f->execute();
                $result_f->bind_result($female);
                $result_f->fetch();
                $result_f->close();

                /*$total = $male + $female;
                echo $male."(".($male/$total).") | ".$female."(".($female/$total).")";*/
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
                        title: 'Gender Distribution'
                      };
                      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                      chart.draw(data, options);
                  }
                </script>
                <div id="piechart" style="width: 900px; height: 500px;"></div>
            </div>

            <div class="block">
                <h3>Other Most Popular Courses for A+ Students (TOP 5)</h3>
                <?php
                $query = "SELECT c.Course_name, c.Course_code FROM COURSE AS c, GRADE_REPORT AS g WHERE c.Course_code=g.Course_code AND g.User_id IN (SELECT User_id FROM GRADE_REPORT WHERE Numeric_grade >= 90 AND Course_code=".$course_code.") GROUP BY Course_code ORDER BY COUNT(c.Course_code) LIMIT 5";
                $result = $mysqli->prepare($query);
                $result->execute();
                $result->bind_result($course_name, $pop_code);

                echo "<ul>";
                while ($result->fetch())
                {
                    echo "<li><a href=\"course.php?code=".$pop_code."\">";
                    echo $course_name;
                    echo "<span class=\"sub_title\">(CODE:".$pop_code.")</span>";
                    echo "</a></li>";
                }
                echo "</ul>";
                $result->close();

                ?>
            </div>
        </div>
    </body>
</html>