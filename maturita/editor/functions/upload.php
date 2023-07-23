<head>
    <link rel="stylesheet" href="../../css/editor.css">
    <script src="https://kit.fontawesome.com/93fe4c4cda.js" crossorigin="anonymous"></script>
    <script src="../../js/jquery-3.5.1.min.js"></script>
    <script src="../../js/pings.js"></script>
</head>
<h1 style="margin-left: 15px; margin-top: 15px;">Nahrajte súbor</h1>
<form method="POST" enctype="multipart/form-data" class="media-header" style="margin-left: 40px;">
    <input type="file" name="file[]" <?php if(!isset($_GET["download"])) echo 'accept="image/png, image/jpeg"';?>>
    <input type="submit" name="submit">
</form>

<script type="text/javascript">

function selectImage (imgName){    
	imgName = imgName.replace(/ /gi, "_");
    var CKEditorFuncNum = "<?php echo $_GET['CKEditorFuncNum']; ?>";
        var url = '../files/img/' + imgName; // čo má byť v editore v URL
        window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, url); // danie url do URL baru
        window.close();
    }

function selectVideo(fileName){
	fileName = fileName.replace(/ /gi, "_");
    var CKEditorFuncNum = "<?php echo $_GET['CKEditorFuncNum']; ?>";
    var url = '../files/video/' + fileName;
    window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, url);
    window.close();
}
</script>

<?php
if(isset($_POST["submit"])){
    if(isset($_FILES["file"])){
        $file = $_FILES["file"];
        require("uploadCheck.php");
        $uploadCheck = new UploadCheck();
        $file = $uploadCheck->OrganizeArray($file);
        if(isset($_GET["video"])){
            $uploadCheck->setDirFiles("../../files/video/");
            $error = array();
            array_push($error, $uploadCheck->uploadCheck($file[0]));
            $uploadCheck->checkErrors($error);
            $name = $file[0]['name'];
            echo "<script>selectDocument('$name')</script>";
        }
        else{            
            $uploadCheck->setDirFiles("../../files/img/");
            $errors = array();
            $uploadErrors = $uploadCheck->uploadCheck($file);
            $errors = $uploadCheck->checkErrors($uploadErrors);
            var_dump($errors);
            $name = $file[0]['name'];
            if($errors != null && $errors !== "[]"){
                echo "<script>Ping($errors, 'alert');</script>";
            }
            else{
                echo "<script>selectImage('$name')</script>";
            }
        }
    }
}

    if(isset($_GET["video"])){
        $dirPath = "../../files/video";
        $files = scandir($dirPath);
        $files = array_slice($files, 2);        
        echo '<div><h1 style="margin-left: 15px;">Alebo zvoľte z existujúcich</h1>';
        foreach ($files as $file){
            echo "<div class='vid-div'>
                <video width='450' height='auto' controls>
                    <source src='$dirPath/$file'>
                </video>
                <a href='javascript:selectVideo(\"$file\")'>$file</a>
                </div>";
        }
    }
    else{
        $dirPath = "../../files/img/thumbnails";
        $imgFiles = scandir($dirPath);
        $imgFiles = array_slice($imgFiles, 2);
        echo '<div><h1 style="margin-left: 15px;">Alebo zvoľte z existujúcich</h1>';
        foreach ($imgFiles as $image) { // po kliknutí na <a> sa zapne javascript do ktorého sa dá názov obrázka a ten obrázok dá do URL v CKEditore
            echo '<div class="thumb-div">
            <a href="javascript:selectImage(\''.$image.'\')" > 
            <img src="'.$dirPath.'/'.$image. '"class="thumbnail"  id="thumbnail">
            </a>
            </div>';
        }

            ?>
        <div class='range-input'><i class="fas fa-image" style="margin-right: 10px;"></i><input type="range" min="100" max="400" value="200" id="myRange"><i class="fas fa-image" style="font-size: 1.2em; margin-left: 10px"></i></div>
        
        <script>
        // Hýbanie sliderom mení veľkosť obrázkov
        var slider = document.getElementById("myRange");
        var thumbClass = document.getElementsByClassName("thumbnail");
        slider.oninput = function () {
            for (i = 0; i < thumbClass.length; i++)
            thumbClass[i].style = "height: "+slider.value+";";
        }
        </script>
        <?php
        
    echo "</div>";
}
