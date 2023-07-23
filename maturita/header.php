<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&display=swap" rel="stylesheet"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Škola pod Poľanou</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://kit.fontawesome.com/93fe4c4cda.js" crossorigin="anonymous"></script>
</head>
<?php
if(!defined("AllowAccess")){
    header("Location: index.php");
    exit();
}
?>
<body>    
    <header>
        <div class="editor-panel" id="editor-panel">
            <div>
                <div class="editor-links">
                    <a href="editor/posts.php"><i class="fas fa-clone"></i>Príspevky</a><a href="editor/media.php"><i class="fas fa-photo-video"></i>Médiá</a><a href="editor/gallery.php"><i class="fas fa-images"></i>Galéria</a><a href="admin/logout.php?logout=true"><i class="fas fa-user"></i> Odhlásiť sa</a>
                </div>
                <div class="editor-panel-button">
                    <a id="slide-btn" onclick="menuSlide('down')">
                        <i class="fas fa-angle-up"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="header-bg">
            <a href="index.php?stranka=domov"><img src="files/logo-biela.png" alt="logo" class="logo"></a>
        </div>
    </header>
    <nav id='navigation'>
        <div class="menu-bg"> 
            <input type="checkbox" id="check">
            <label for="check" class="menubtn"><img src="files/menu.png"></label>
       
        <ul class="nav-links">
            <li class="nav-link">
                <div><a href="index.php?stranka=tabory">tábory</a></div>
            </li>
            <li class="nav-link">
                <div><a href="index.php?stranka=galeria">galéria</a></div>
            </li>
            <li class="nav-link">
                <div><a href="index.php?stranka=o-nas">o nás</a></div>
            </li>
            <li class="nav-link">
                <div><a href="index.php?stranka=kontakt">kontakt</a></div>
            </li>
        </ul>
        </div>
    </nav>

    <script>
        function menuSlide(dir){
            var menu = document.getElementById("editor-panel");
            if(dir == "up"){
                menu.style.top = "-42px";
                document.getElementById("slide-btn").setAttribute("onclick", "menuSlide('down')");
            }
            else{
                menu.style.top = "0px";
                document.getElementById("slide-btn").setAttribute("onclick", "menuSlide('up')");
            }
        }        
    </script>
