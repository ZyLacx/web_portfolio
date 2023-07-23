<?php session_start(); ?><!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/admin.css">
    <title>Log in</title>
    
    <script>
        function showCountDown(finishTime){
            var x = setInterval(function() {
                var currentTime = Math.round(new Date().getTime() / 1000);
                var timeLeft = finishTime - currentTime;
                var timeLeftMin = Math.floor((timeLeft % (60 * 60)) / 60 );
                var timeLeftSec = Math.floor( timeLeft % 60 );
                if(timeLeftSec <= 10)
                    timeLeftSec = "0" + timeLeftSec;
                var timerDiv = document.getElementById("timer").innerHTML = "Dosiahnutý maximálny počet prihlásení \n skúste znovu za " + timeLeftMin +":" + timeLeftSec;
            if(timeLeft <= 0){
                var div = document.getElementById("timer");
                div.parentNode.removeChild(div);
                clearInterval(x);
            }
            }, 200);
        }
    </script>
</head>
<body>
<?php

$url = explode("/", $_SERVER["REQUEST_URI"]);
if($url[count($url) - 1] == "admin"){
    header("Location: admin/index.php");
    exit();
}
if(!isset($_SESSION["logged"])){
    if(!isset($_SESSION["tries"])){$_SESSION["tries"] = 5;}
    if(!isset($_SESSION["loginlock"])){$_SESSION["loginlock"] = false;}
?>
    <div class="wrapper">
        <h2>Prihlásenie</h2>
        <form action="functions.php" method="post">
            <input type="text" name="username" id="username" placeholder="Meno používateľa">
            <input type="password" name="password" id="password" placeholder="Heslo">
            <input type="submit" name="login" id="login" value="Prihlásiť sa">
        </form>

        <?php
            if(isset($_GET["error"])){
                if($_GET["error"] == "login"){
                    if($_SESSION["loginlock"] == false){
                        $_SESSION["tries"]--;
                        echo "<p>Zadané meno alebo heslo nebolo správne</p>";
                    }
                    if($_SESSION["tries"] > 0 && $_SESSION["loginlock"] == false){
                        echo "<p>Zvyšný počet pokusov je ". $_SESSION["tries"] ."</p>";
                    }
                    if($_SESSION["tries"] == 0 && $_SESSION["loginlock"] == false){ 
                        $_SESSION["loginlock"] = true;
                        echo '<div id="timer"></div>';
                        countDownStart();
                    } // ak je viac ako 5 tries zablokuj login function na nejaký čas
                }
                else if($_GET["error"] == "empty"){
                    echo "<p>Prázdne pole</p>";
                }
            }
            else if($_SESSION["loginlock"] == true){
                echo '<div id="timer"></div>';
                echo '<script>showCountDown('.$_SESSION["timerTime"].');</script>';
            }
}
else{
    header("location: ../editor/posts.php");
    exit();
};

function countDownStart(){
    $time = date('H:i:s');
    $_SESSION["timerTime"] = strtotime(date('H:i:s', strtotime($time) + (60*5)));
    echo '<script>showCountDown('.$_SESSION["timerTime"].');</script>';
}
?>
</div>
</body>
</html>