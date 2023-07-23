<?php
session_start();
define("AllowAccess", TRUE);
    require "header.php";
    
    echo "<div class='wrapper' >";

    if (isset($_GET['stranka'])){

        $page = trim($_GET['stranka']);
    
    
    switch($page){
        case "kontakt":
        case "contact":
            include("pages/kontakt.php");
            break;
        case "about":
        case "o-nas":
        case "onas":
            include("pages/o-nas.php");
            break;
        case "tabory":
            include("pages/tabory.php");
            break;
        case "home":
        case "domov":
            include("pages/domov.php");
            break;
        case "gallery":
        case "galeria":
            include("pages/galeria.php");
            break;
        default:
            include("pages/404.php");
            break;
        }
    }
    else{
        include("pages/domov.php");
    }
    
    echo "</div>";

    require "footer.php";
?>