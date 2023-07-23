<?php

session_start();
if(isset($_POST["logout"])){
    unset($_SESSION["logged"]);
    header("location: ../index.php");
    exit();
}
unset($_SESSION["logged"]);
header("location: ../index.php");
exit();

?>