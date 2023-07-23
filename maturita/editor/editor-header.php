<?php 
session_start();
// if(!$_SESSION["logged"]){
//     header("location: ../admin/");
//     exit();
// }
if(!defined("AllowAccess")){
    header("Location: posts.php");
    exit();
}
?>