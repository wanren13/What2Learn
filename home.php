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

include 'conn.php';

?>

<html>
    <head>
        <link type="text/css" rel="stylesheet" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.css">

    </head>

    <body>

        <?php include 'header.php' ?>

        <div class="wrapper">

            <div id="wrapper_left">
                <div class="block course">
                    <div class="block-header">
                        <span>My Course List</span>
                        <span style="float:right; margin-right: 10px; font-size: 14px; cursor: pointer;" onclick="displayDel()" id="edit_course">[edit list]</span>
                    </div>
                    <div class="block-body">
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
                                    <span><a class="del-grade" style="display: none" href='javascript:void(0)' onclick="javascript: confirmDelCourse()">[-]</a></span>
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
                    </div>
                    <div class="block-footer"></div>
                </div>

                <div class="block university">
                    <div class="block-header">
                        <span>Universities Attended</span>
                        <span style="float:right; margin-right: 10px; font-size: 14px;"><a href="uni_report.php">[+]</a></span>
                    </div>
                    <div class="block-body">
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
                            echo $uni_name;
                            echo "<br /><span class=\"sub_title\">Dept: ".$dept_name."| Enter time: ".$attend_time."</span>";
                            echo "</a>";

                            echo "<span style='float:right; font-size: 14px;'><a href='del_uni.php?uni_code=".$uni_code."&dept_code=".$dept_code."'>[-]</a></span>";

                            echo "</li>";

                        }
						
                        echo "</ul>";
                        ?>
                    </div>
                    <script>
                        function confirmDelCourse()
                        {

                            var r = confirm("if you delete this course, your rating will be deleted. However, skills will be remained. Proceed?");
                            if (r == true)
                              {
                                var url = "./del_grade.php?course_code=" + <?php echo $course_code; ?> + "&uni_code=" + <?php echo $uni_code; ?>;
                                //alert(url);
                                window.location.href = url;
                                //windows.location.href = "http://www.baidu.com";
                                return true;
                              }
                            else
                              {
                                return false;
                              }
                        }
                    </script>
                    <div class="block-footer"></div>
                </div>      

                <div class="block company">
                    <div class="block-header">
                        <span>Companies Joined</span>
                        <span style="float:right; margin-right: 10px; font-size: 14px;"><a href="co_report.php">[+]</a></span>
                    </div>
                    <div class="block-body">
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
                            echo $pos_name." @ ".$co_name;
                            echo "<br /><span class=\"sub_title\">From ".$start." to ".$end." | Salary: ".$salary;
                            echo "</a>";

                            echo "<a href='del_com.php?co_id=".$co_id."&pos_code=".$pos_code."'><span style='display: block; float:right; font-size: 14px;'>[-]</span></a>";

                            echo "</li>";
                        }
                        echo "</ul>";
                        ?>
                    </div>
                    <div class="block-footer"></div>
                </div>   
            </div>

            <div id="wrapper_right">
                <div class="block info">
                    <div class="block-header">
                            <span>My Info</span>
                            <span style="float:right; margin-right: 10px; font-size: 14px;"><a href="edit_profile.php">[edit]</a></span>
                    </div>
                    <div class="block-body">
                        <img src='./img/cake.png' /><p><?php echo $_SESSION['birthday']; ?></p><br />
                        <img src='./img/gender.png' /><p><?php echo $_SESSION['gender']; ?></p><br />
                        <img src='./img/globe.png' /><p><?php echo $_SESSION['country']; ?></p>
                        <style>
                            .info p {
                                display: inline-block;
                                margin-top: 6px;
                                margin-bottom: 0;
                                margin-left: 5px;
                                color: #636262;
                            }

                            img {
                                margin-top: 6px;
                                display: inline-block;
                                width: 18px;
                                height: 18px;
                            }
                        </style>
                    </div>
                    <div class="block-footer"></div>
                </div>

                <div class="block skill">
                    <div class="block-header">
                        <span>My Skill Set</span>
                        <span style="float:right; margin-right: 10px; font-size: 14px;"><a href="skill_report.php">[+]</a></span>
                    </div>
                    <div class="block-body" id="skill_set">
                        <?php
                            //get skill list
                            /*$query = "SELECT Skill_name 
                            FROM SKILL AS s, POSSESS AS pos
                            WHERE s.Skill_id = pos.Skill_id AND pos.User_id=".$_SESSION['user_id']." 
                                UNION 
                                SELECT SKill_name
                                FROM SKILL AS s, PROVIDE AS pro 
                                WHERE s.Skill_id = pro.Skill_id AND pro.Course_code IN
                                    (SELECT Course_code FROM GRADE_REPORT WHERE User_id=".$_SESSION['user_id']."
                                    )";*/
                            
                            $query = "SELECT Skill_name, s.Skill_id
                            FROM SKILL AS s, POSSESS AS pos
                            WHERE s.Skill_id = pos.Skill_id AND pos.User_id=".$_SESSION['user_id'];

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
                    <div class="block-footer"></div>
                </div>
            </div>
        </div>

        <script>
            function displayDel()
            {
                //alert("test"); //test


                var edit = document.getElementById("edit_course");
                if (edit.innerText == "[edit list]")
                {
                    edit.innerText = "[cancel edit]";
                }
                else
                {
                    edit.innerText = "[edit list]";
                }

                var grade = document.getElementsByClassName("course-grade");

                //var v = grade[0].style.display;

                //alert("test");
                //alert(grade[0].style.display == "block");

                // only when display is declared inline
                for (var i = 0; i < grade.length; i++)
                {
                    if(grade[i].style.display != "none")
                    {
                        grade[i].style.display = "none";
                    }
                    else
                    {
                        grade[i].style.display = "block";
                    }
                }

                
                var del = document.getElementsByClassName("del-grade");
                //alert(del[0].style.display == "none");
                
                for (var i = 0; i < del.length; i++)
                {

                    if(del[i].style.display == "none")
                    {
                        del[i].style.display = "block";
                        del[i].style.float = "right";
                    }
                    else
                    {
                        del[i].style.display = "none";
                    }
                    
                }
            }
        </script>
    </body>
</html>