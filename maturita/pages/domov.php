<?php
if(!defined("AllowAccess")){
    header("Location: ../index.php");
    exit();
}
require_once "editor/functions/dbConnect.php";
$pdo = dbConnect("./editor/articles");

if(isset($_GET["id"])){
    $id = $_GET["id"];
    $query = "SELECT title, content FROM articles WHERE id=$id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0){
        $postData = $result[0];
        echo "<div class='post'>";
        // if(isset($_SESSION["logged"]))
        echo "<div class='edit-post'><a href='editor/post-edit.php?postId=$id'><i class='fas fa-pen-square'></i></a></div>";
        echo "<h1>".$postData["title"]."</h1>";
        fixDescription($postData["content"]);
        echo "</div>";
    }
    else{
        echo "Vyskytla sa chyba s databázou";
    }
}
else{
    $query = "SELECT * FROM articles ORDER BY id DESC;";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $postData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Throwable $e) {
        echo $e->getMessage();
    }
    
    if (count($postData) == 0){
        echo "Vyskytla sa chyba s databázou";
        exit;
    }
    
    // ? Vypíše posty
    for($i = 0; $i < count($postData); $i++){
        switch($postData[$i]["layout"]){
            case "up":
                print_post_up($postData[$i]);
            break;
            case "down":
                print_post_down($postData[$i]);
            break;
            case "left":
                print_post_left($postData[$i]);
            break;
            case "right":
                print_post_right($postData[$i]);
            break;
            case "fancyLeft":
                print_post_fancyL($postData[$i]);
            break;
            case "fancyRight":
                print_post_fancyR($postData[$i]);
            break;
            default:
                print_post_no_thumb($postData[$i]);
            break;
        }
        echo "<hr class='post-hr'/>";
    }
}

function print_post_up($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <a href='?id=".$postData["id"]."'>
            <img src='files/img/".$postData['thumbnail']."' class='thumb'>
            <h1>".$postData["title"]."</h1>
            <p>$description</p>
        </a>
        </div>";
}

function print_post_down($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <a href='?id=".$postData["id"]."'>
            <h1>".$postData["title"]."</h1>
            <p>$description</p>
            <img src='files/img/".$postData['thumbnail']."' class='thumb'>
        </a>
        </div>";
}

function print_post_left($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <a href='?id=".$postData["id"]."'>
            <div class='side-layout-div'>
                <div class='thumb-side thumb-left'>
                    <img src='files/img/".$postData['thumbnail']."'>
                </div>
                <div class='side-layout-text'>
                    <h1>".$postData["title"]."</h1>
                    <p>$description</p>
                </div>
            </div>
        </a>
        </div>";
}

function print_post_right($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <a href='?id=".$postData["id"]."'>
            <div class='side-layout-div'>
                <div class='side-layout-text'>
                    <h1>".$postData["title"]."</h1>
                    <p>$description</p>
                </div>
                <div class='thumb-side thumb-right'>
                    <img src='files/img/".$postData['thumbnail']."'>
                </div>
            </div>
        </a>
        </div>";
}

function print_post_fancyL($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <img src='files/img/".$postData['thumbnail']."' class='thumb-fancyL thumb-fancy'>
        <a href='?id=".$postData["id"]."'>
            <h1>".$postData["title"]."</h1>
            <p>$description</p>
        </a>
        </div>";
}

function print_post_fancyR($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <img src='files/img/".$postData['thumbnail']."' class='thumb-fancyR thumb-fancy'>
        <a href='?id=".$postData["id"]."'>
            <h1>".$postData["title"]."</h1>
            <p>$description</p>
        </a>
        </div>";
}

function print_post_no_thumb($postData){
    $description = getDescription($postData);
    echo "<div class='post-div'>
        <a href='?id=".$postData["id"]."'>
            <h1>".$postData["title"]."</h1>
            <p>$description</p>
        </a>
        </div>";
}

// Zistí koľko je znakov v description a vypíše prvých 450 + ...
function getDescription($postData){
    $description = $postData["content"];
    if (!empty($postData["desc"])) {
        $description = $postData["desc"];
    }
    $descFull = strip_tags($description);
    if(strlen($descFull) > 450){
        $return_desc = substr($descFull, 0, 450);
        $return_desc .= "...";
    }
    else{
        $return_desc = $descFull;
    }
    
    return $return_desc;
}

// V editore používam path ../files ale tu potrebujem files/
function fixDescription($desc){
    $pattern = array('/src="..\/files/');
    $replacement = array('src="files');
    echo preg_replace($pattern, $replacement, $desc);
}
?>