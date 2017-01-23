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

$keyword = $_GET['keyword'];
$type = $_GET['type'];

include 'conn.php';
?>

<html>
    <head>
        <link type="text/css" rel="stylesheet" href="css/base.css">
        <link type="text/css" rel="stylesheet" href="css/search.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    </head>
    <body>
        <?php include 'header.php' ?>

        <div class="wrapper" style="margin-top: 5px; background: white; padding: 10px 10px 10px 10px; box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);">
            <?php
                $result_count = 0;

                if($type == "course")
                {
                    $query = "SELECT Course_code, Course_name FROM COURSE WHERE Course_name LIKE '%".$keyword."%'";
                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($course_code, $course_name);
                
                    echo "<ul>";
                    while ($result->fetch())
                    {
                        echo "<li class='result'>";
                        echo "<a href='course.php?code=".$course_code."'>";
                        echo $course_name."<span style='color: #66696a; font-size: 11px'>(CODE:".$course_code.")</span>";
                        echo "</a>";
                        echo "</li>";

                        $result_count++;
                    }
                    echo "</ul>";
                    $result->close();
                }

                if($type == "student")
                {
                    $query = "SELECT User_id, First_name, Middle_name, Last_name, Birthday, Country, Gender 
                                FROM  STUDENT
                                WHERE First_name LIKE '%".$keyword."%' OR Middle_name LIKE '%".$keyword."%' OR Last_name LIKE '%".$keyword."%'";
                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($stu_id, $first_name, $middle_name, $last_name, $birthday, $country, $gender);

                    echo "<ul>";
                    while ($result->fetch())
                    {
                        echo "<li class='result'>";
                        echo "<a href='student.php?id=".$stu_id."'>";
                        echo $first_name." ".$middle_name." ".$last_name."<br />";
                        echo "<span style='color: #333; font-size: 13px'>".$gender.", ".$country."</span><br />";
                        echo "<span style='color: #a9a9a9; font-size: 11px'>b-day: ".$birthday."</span>";
                        echo "</a>";
                        echo "</li>";

                        $result_count++;
                    }
                    echo "</ul>";
                    $result->close();
                }

                if($type == "university")
                {
                    $query = "SELECT Uni_code, Uni_name, State, City, Postcode
                                FROM  UNIVERSITY
                                WHERE Uni_name LIKE '%".$keyword."%'";
                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($uni_code, $uni_name, $state, $city, $postcode);

                    echo "<ul>";
                    while ($result->fetch())
                    {
                        echo "<li class='result'>";
                        echo "<a href='university.php?code=".$uni_code."'>";
                        echo $uni_name."<br />";
                        echo "<span style='color: #333; font-size: 13px'>".$state.", ".$city." ".$postcode."</span>";
                        echo "</a>";
                        echo "</li>";

                        $result_count++;
                    }
                    echo "</ul>";
                    $result->close();
                }

                if($type == "company")
                {
                    $query = "SELECT Co_id, Co_name, Country, State, City, Postcode 
                                FROM  COMPANY
                                WHERE Co_name LIKE '%".$keyword."%'";
                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($co_id, $co_name, $country, $state, $city, $postcode);

                    echo "<ul>";
                    while ($result->fetch())
                    {
                        echo "<li class='result'>";
                        echo "<a href='company.php?code=".$co_id."'>";
                        echo $co_name."<br />";
                        echo "<span style='color: #333; font-size: 13px'>".$country.", ".$state.", ".$city."</span>";
                        echo "</a>";
                        echo "</li>";

                        $result_count++;
                    }
                    echo "</ul>";
                    $result->close();
                }

                if($type == "position")
                {
                    $query = "SELECT Pos_code, Pos_name
                                FROM  POSITION
                                WHERE Pos_name LIKE '%".$keyword."%'";
                    $result = $mysqli->prepare($query);
                    $result->execute();
                    $result->bind_result($pos_code, $pos_name);

                    echo "<ul>";
                    while ($result->fetch())
                    {
                        echo "<li class='result'>";
                        echo "<a href='position.php?code=".$pos_code."'>";
                        echo $pos_name."<span style='color: #66696a; font-size: 11px'>(CODE:".$pos_code.")</span>";
                        echo "</a>";
                        echo "</li>";

                        $result_count++;
                    }
                    echo "</ul>";
                    $result->close();
                }

                echo "<input type='hidden' id='count' value='".$result_count."' />";
            ?>
        </div>

        <script>
            //这个方法实在是太蠢。。。最后来加总计数，而非一开始就存数组再显示，总之，没时间了不管了
            var count = document.getElementById("count").value;
            //alert(count); //debug
            var wrapper = document.getElementsByClassName("wrapper");
            //alert(wrapper[0].innerHTML);
            var insertText = "<div style='display: block; max-width: 1000px; font-size: 13px; margin: 0 auto; margin-top: 50px;'>total <span style='font-weight: bold'>" + count + "</span> results: </div>";
            //alert(insertText); //pay attention to the index!!!
            wrapper[1].insertAdjacentHTML("beforeBegin", insertText);
        </script>
    </body>
</html>