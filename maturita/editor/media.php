<?php
define("AllowAccess", TRUE);
include("editor-header.php");
echo "<div class='main-window'>
        <div class='menu-panel'>";
include_once("editor-menu.php");
echo "</div>";
?>

<div class="content-div">
<div class="media-header">

<?php

// * Upload functions
if(isset($_POST["upload"])){
    echo "<script>Ping('File upload not available in this version', 'alert');</script>";

    // require("functions/uploadCheck.php");
    // $uploadCheck = new UploadCheck();
    // $files = $uploadCheck->OrganizeArray($_FILES["files"]);
    // $uploadErrors = array();
    // if(isset($_GET["video"]) && $_GET["video"] == true)
    //     $uploadCheck->setDirFiles("../files/video/");
    // else
    //     $uploadCheck->setDirFiles("../files/img/");
    
    // $uploadErrors = $uploadCheck->uploadCheck($files);
    
    // $errors = $uploadCheck->checkErrors($uploadErrors);

    // if($errors !== "[]")
    //     echo "<script>Ping($errors, 'alert');</script>";
    // else
    //     echo "<script>Ping('Súbory boli úspešne nahrané!');</script>";
}

require("functions/printContent.php");
if(isset($_GET["trash"]) && $_GET["trash"] == true){
    if(isset($_GET["video"]) && $_GET["video"] == true){
        echo "<h2>Videá - Kôš</h2>"; // Ukončenie media-header div
        echoFormVid();
        echo '<form class="form-list" action="functions/content-logic.php" method="post">';
        $list = new printContent("trash/video");
    }
    else{
        echo "<h2>Obrázky - Kôš</h2>"; // Ukončenie media-header div
        echoFormImg();
        echo '<form class="form-list" action="functions/content-logic.php" method="post">';
        $list = new printContent("trash/img");
    }
}
else{
    if(isset($_GET["video"]) && $_GET["video"] == true){
        echo "<h2>Videá</h2>"; // Ukončenie media-header div
        echoFormVid();
        echo '<form class="form-list" action="functions/content-logic.php" method="post">';
        $list = new printContent("video");
    }
    else{
        echo "<h2>Obrázky</h2>"; // Ukončenie media-header div
        echoFormImg();
        echo '<form class="form-list" action="functions/content-logic.php" method="post">';
        $list = new printContent("img");
    }
}
echo "</form>";
if(isset($_GET["empty"]) && $_GET["empty"] == true){
    echo '<script>Ping("Nič ste nevybrali", "alert")</script>';
}

function echoFormVid(){
    echo '<form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="files[]" id="files" multiple="" accept="video/*">
            <input type="submit" name="upload" id="upload-button" value="Nahrať">
        </form>
        </div>'; // Ukočenie media-header div
}

function echoFormImg(){
    echo '<form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="files[]" id="files" multiple accept="image/png, image/jpeg">
            <input type="submit" name="upload" id="upload-button" value="Nahrať">
        </form>
        </div>'; // Ukočenie media-header div
}

?>
</div>
</div>

<script>
    document.getElementById("files").addEventListener("change", preventUpload);

    function preventUpload(){
        var files = document.getElementById("files");
        var button = document.getElementById("upload-button");
        if(files.files.length > 20){
            button.disabled = true;
            Ping("Môžete nahrať maximálne 20 súborov", "alert")
        }
        else{
            button.disabled = false;
        }
    }
</script>