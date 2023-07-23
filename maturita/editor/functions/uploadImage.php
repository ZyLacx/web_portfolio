<?php
// * Ak kliknem na submit nech sa selectne Image
require("uploadCheck.php");
$uploadCheck = new UploadCheck();
$files = $uploadCheck->OrganizeArray($_FILES["file"]);
$uploadErrors = array();
$uploadCheck->setDirFiles("../../files/img/");
$uploadErrors = $uploadCheck->uploadCheck($files);
if(!empty($uploadErrors)){
    $echoError = $uploadCheck->checkErrors($uploadErrors);
    if(!isset($uploadErrors["name"])){
        $echo = json_decode($echoError);
        echo $echo[0];
    }
}