<?php
require_once "dbConnect.php";
$pdo = dbConnect("gallery");

// TODO from gallery_images where gallery id = id
$query = "SELECT * FROM gallery WHERE id='{$_GET["id"]}'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Modal
    echo "<div id='myModal' class='modal'>
            <span class='close cursor' onclick='closeModal()'>&times;</span>
            <div class='modal-content'>
                <div id='slideCount' class='numbertext'>0</div>";

    $img_array = explode("/", $row["images"]);

        foreach($img_array as $i => $img){
            echo "<div class='mySlides'>
                    <img src='files/img/$img'>
                </div>";
        if($i > 2){
            break;
            }
        }

        echo "<a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
            <a class='next' onclick='plusSlides(1)'>&#10095;</a>";

        echo "<div id='modalNavigation'>";
        foreach($img_array as $i => $img){
            echo "<img class='demo' src='files/img/thumbnails/$img' onclick='currentSlide($i)'>";
            if($i > 2){
            break;
            }
        }
        echo "</div>";

        echo "</div>
            </div>";
?>