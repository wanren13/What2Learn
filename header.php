<header>
    <div class="wrapper" style="margin: 0 auto">
        <div class="logo"><a href="home.php">What2Learn</a></div>
        <div class="toolbar">
            
            <form method="get" action="search.php" style="display: inline-block; float: left; padding: 0; margin: 0 0 0 20px">

            <select name="type" id="type">
                <option selected="selected" value="course">Course</option>
                <option value="student">Student</option>
                <option value="university">University</option>
                <option value="company">Company</option>
                <option value="position">Position</option>
            </select>

            <input id="search-input" name="keyword" placeholder="Search Something?" type="text" />

            <input type="submit" class="toolbar-btn" value="search" />
            
            </form>
            <a href="home.php">
                <?php
                    echo $_SESSION['first_name']." ";
                    if(isset($_SESSION['middle_name'])){
                        echo $_SESSION['middle_name']." ";
                    }
                    echo $_SESSION['last_name'];
                ?>
            </a>
            <div style="height: 26px; width: 1px; border-right: #006092 1px solid; display: inline-block; float: left; margin-left: 10px; margin-top: 7px;"></div>
            <a id="logout" href="logout.php">Logout</a>         
        </div>
    </div>
</header>