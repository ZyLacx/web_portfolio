<?php
if(!defined("AllowAccess")){
    header("Location: ../index.php?gallery");
    exit();
}
require_once "editor/functions/dbConnect.php";
$pdo = dbConnect("./editor/gallery");

if(isset($_GET["id"])){
    $query = "SELECT media_name FROM gallery_media WHERE gallery_id=:id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(":id", $_GET["id"], PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll();
    if(count($images) > 0){
        echo "<div class='grid'>";
        foreach($images as $i => $img){
            $img = $img[0];
            $index = $i + 1;
            echo "<div class='item'>
                    <noscript>
                        <a href='files/img/$img' target='_blank'>
                            <div class='item-content'>
                                <img src='files/img/thumbnails/$img'>
                            </div>
                        </a>
                    </noscript>
                    <script>
                        document.write(`<div class='item-content' style='cursor: pointer;' title='Otvoriť na celú obrazovku' onclick='openModal($index);'><img src='files/img/thumbnails/$img' ></div>`)
                    </script>
                </div>";
            }
        
        echo "</div>";
    }
    else {
        echo "Prázdna galéria";
    }
    // Modal
    echo "<div id='myModal' class='modal'>
            <span class='close cursor' onclick='closeModal()'>&times;</span>
            <div class='modal-content'>
                <div id='slideCount' class='numbertext'>0</div>";
        foreach($images as $i => $img){
            echo "<div class='mySlides'>
                    <img src='files/img/$img[0]'>
                </div>";
        }
    
        echo "<a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
            <a class='next' onclick='plusSlides(1)'>&#10095;</a>";
    
        echo "<div id='modalNavigation'>";
        foreach($images as $i => $img){
            echo "<img class='demo' src='files/img/thumbnails/$img[0]' onclick='currentSlide($i)'>";
        }
        echo "</div>";
    
        echo "</div></div>";
}
else{
    $query = "SELECT * FROM gallery ORDER BY id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $dbData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class='grid'>";
    foreach($dbData as $data){
        echo "<div class='item'>
                <a href='?stranka=galeria&id=".$data["id"]."'>
                    <div>
                        <img src='files/img/thumbnails/".$data["img"]."'>
                    </div>
                    <p>".$data["title"]."</p>
                </a>
        </div>";
    }
    echo "</div>";
}

?>

<script>

// if(window.innerWidth > 720){
//     var hoverZone = window.innerHeight - (window.innerHeight / 100) * 15; 
//     document.body.addEventListener("mousemove", (event) => {
//             var thumbnails = document.getElementsByClassName("demo");
//             if(event.y > hoverZone){
//                 for(var i = 0; i < thumbnails.length; i++)
//                 {
//                     thumbnails[i].style.transform = "translate(0%, -20%)";
//                 }
//             }
//             else {
//                 for(var i = 0; i < thumbnails.length; i++)
//                 {
//                     if(thumbnails[i].style.transform == "translate(0%, -20%)"){
//                         thumbnails[i].style.transform = "translate(0%, 0%)";
//                     }
//                 }
//              }
//         });
// }

var slideIndex = 1;
showSlides(slideIndex);

function openModal(index) {
    document.getElementById("myModal").style.display = "block";
    slideIndex = index;
    showSlides(slideIndex);
}

// Close the Modal
function closeModal() {
  document.getElementById("myModal").style.display = "none";
}


// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex + n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(n);
}

function showSlides(n) {
    var i;
    slideIndex = n;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var counter = document.getElementById("slideCount");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    if (slideIndex > slides.length) {
        slideIndex = 0
    }
    if (slideIndex < 0) {
        slideIndex = slides.length
    }
    if (slideIndex != 0) {
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
    }
    counter.innerHTML = slideIndex + " / " + slides.length;
}
</script>



 