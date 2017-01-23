<?php
if(empty($_SESSION))
{
    session_start();
}
?>

<html>
    <head>
        <title>Login</title>
        <link type="text/css" rel="stylesheet" href="css/index.css">
    </head>

    <body id="dummybodyid">
        <div class="login-box">
            <div class="login-box-header">What To Learn?</div>
            <div id="login-panel" class="login-box-body">
                <p style="color: red; font-size: 14px; ">
                    <?php
                        //echo "test"; //debug
                        //echo $_SESSION['status']."<br />"; //debug
                        if (isset($_SESSION['status']))
                        {
                            echo $_SESSION['status']."<br />";
                            unset($_SESSION['status']);
                        }
                    ?>
                </p>
                <form method="post" action="login.php">
                    <div class="login-field">
                        <input id="username" name="username" type="text" placeholder="Username" />
                    </div>
                    <div class="login-field">
                        <input id="password" name="password" type="password" placeholder="Password" />
                    </div>
                    <div class="login-field" style="padding: 0">
                        <input type="checkbox" name="remember" checked /><label id="remember">remember me |</label>
                        <input type="submit" class="login-btn" name="submit" value="Log In" style="display: block; float: right;" />
                        <a href="register.php" id="register">register</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>