<?php
session_start();
if(!$_SESSION["logged"]){
    header("location: ../../admin/");
    exit();
}

require_once "dbConnect.php";

$type = $_POST["type"];
switch($type){
    case "articles":
        $path = "../posts.php";
        break;
    case "articles_draft":
        $path = "../posts.php?draft=true";
        break;
    case "articles_trash":
        $path = "../posts.php?trash=true";
        break;
    case "trash/img":
        $path = "../media.php?trash=true";
        break;
    case "img":
        $path = "../media.php";
        break;
}

if(isset($_POST["delete"]) || isset($_POST["recover"])){
    if(empty($_POST["names"])){
        header("Location: $path&empty=true");
        exit();
    }
    else{
        if(isset($_POST["recover"])){
            if(strpos($type, "articles") === false){
                // recoverMedia($type, $path);
            }
            else{
                // recoverPosts($path);
            }
        }
        else{
            if(strpos($type, "articles") === false){
                if(strpos($type, "trash") === false)
                    moveMedia($type, $path);
                else {
                    // deleteMedia($type, $path);
                }
            }
            else{
                if($type == "articles_trash"){
                    // deleteArticles($path);
                }
                else{
                    moveArticles($type, $path);
                }
            }
        }
    }
}

if(isset($_POST["makedraft"])){
    if(empty($_POST["names"])){
        header("Location: $path&empty=true");
        exit();
    }
    else{
        makeDraft($type, $path);
    }
}


// Ak zmením koľko sa má zobrazovať na jednej strane tak nech je page 1
if(isset($_POST["numberOfElements"]) && $_POST["numberOfElements"]!==$_SESSION["elementsCount"][$type]){
    $_SESSION["elementsCount"][$type] = $_POST["numberOfElements"];
    $_SESSION["pageNumber"] = 1;
}

function deleteMedia($type, $path){
    foreach ($_POST["names"] as $media){
        if(strpos($type, "video") === false){
            unlink("../files/$type/" . $media);
            unlink("../files/$type/thumbnails/" . $media);
        }
        else{
            unlink("../files/$type/" . $media);
        }
    }
    header("Location: $path");
    exit();
}

function deleteArticles($path){
    $conn = dbConnect();
    foreach ($_POST["names"] as $id){
        $query = "DELETE FROM articles_trash WHERE id=$id;";
        $result = mysqli_query($conn, $query);
    }
    dbClose($conn);
    header("Location: $path");
    exit();
}

function moveMedia($type, $path){
    foreach ($_POST["names"] as $media){
        if(strpos($type, "video") === false){
            $defaultPath = "../files/$type/" . $media;
            $trashPath = "../files/trash/$type/" . $media;
            rename($defaultPath, $trashPath);
            $defaultPath = "../files/$type/thumbnails/" . $media;
            $trashPath = "../files/trash/$type/thumbnails/" . $media;
            rename($defaultPath, $trashPath);
        }
        else{
            $defaultPath = "../files/$type/" . $media;
            $trashPath = "../files/trash/$type/" . $media;
            rename($defaultPath, $trashPath);
        }
    }
    header("Location: $path");
    exit();
}

function moveArticles($type, $path){
    $conn = dbConnect();
    foreach($_POST["names"] as $id){
        $query = "SELECT * FROM $type WHERE id='{$id}';";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
        $query = "INSERT INTO articles_trash (id, title, description, thumbnail, thumbnail_style, del_date)
        VALUES (null,
            '{$data["title"]}',
            '{$data["description"]}',
            '{$data["thumbnail"]}',
            '{$data["thumbnail_style"]}',
            CURRENT_TIMESTAMP);";
        
        mysqli_query($conn, $query);
        $query = "DELETE FROM $type WHERE id='{$id}';";
        mysqli_query($conn, $query);
    }
    dbClose($conn);
    header("Location: $path");
    exit();
}

function recoverPosts($path){
    $conn = dbConnect();
    foreach($_POST["names"] as $id){
        $query = "SELECT * FROM articles_trash WHERE id='{$id}';";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
        $query = "INSERT INTO articles_draft (id, title, description, thumbnail, thumbnail_style)
        VALUES (null,
            '{$data["title"]}',
            '{$data["description"]}',
            '{$data["thumbnail"]}',
            '{$data["thumbnail_style"]}');";
        
        mysqli_query($conn, $query);
        $query = "DELETE FROM articles_trash WHERE id='{$id}';";
        mysqli_query($conn, $query);
            
    }
    dbClose($conn);
    header("Location: $path");
    exit();
}

function makeDraft($type, $path){
    $conn = dbConnect();
    foreach($_POST["names"] as $id){
        $query = "SELECT * FROM $type WHERE id='{$id}';";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
        $query = "INSERT INTO articles_draft (id, title, description, thumbnail, thumbnail_style)
        VALUES (null,
            '{$data["title"]}',
            '{$data["description"]}',
            '{$data["thumbnail"]}',
            '{$data["thumbnail_style"]}');";

        mysqli_query($conn, $query);
        $query = "DELETE FROM $type WHERE id='{$id}';";
        mysqli_query($conn, $query);
    }
    
    dbClose($conn);
    header("Location: $path");
    exit();
}

function recoverMedia($type, $path){
    foreach ($_POST["names"] as $media){            
        if(strpos($type, "video") === false){
            $trashPath = "../files/trash/img/" . $media;
            $defaultPath = "../files/img/" . $media;
            rename($trashPath, $defaultPath);
            $trashPath = "../files/trash/img/thumbnails/" . $media;
            $defaultPath = "../files/img/thumbnails/" . $media;
            rename($trashPath, $defaultPath);
        }
        else{
            $trashPath = "../files/trash/video/" . $media;
            $defaultPath = "../files/video/" . $media;
            rename($trashPath, $defaultPath);
        }
    }
    header("Location: $path");
    exit();
}

