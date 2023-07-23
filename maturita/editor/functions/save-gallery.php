<?php
require_once "dbConnect.php";
$conn = dbConnect();

$muuri_index = explode(",", $_POST["muuri_index"]);
$ids = explode(",", $_POST["id"]);;
$title = explode(",", $_POST["title"]);
$img = explode(",", $_POST["img"]);

$query = "SELECT id FROM gallery_images";
$result = mysqli_query($conn, $query);
$galleryIds = [];
if(mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($galleryIds, $row["id"]);
    }
    $diff = array_diff($galleryIds, $ids);
    if(!empty($diff)){
        foreach($diff as $id){
            $query = "DELETE FROM gallery_images WHERE id='{$id}';";
            mysqli_query($conn, $query);
        }
    }
    $diff = array_diff($ids, $galleryIds);
    if(!empty($diff)){
        foreach($diff as $id){
            $query = "INSERT INTO gallery_images VALUES ('{$id}', null);";
            mysqli_query($conn, $query);
        }
    }
}
else {
    echo mysqli_error($conn);
}

if($ids[0] !== ""){
    $query = "SELECT * FROM gallery";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $query = "TRUNCATE TABLE gallery;";
        mysqli_query($conn, $query);
    }
    else {
        echo mysqli_error($conn);
    }
    
    foreach($ids as $i => $id){
        $query = "INSERT INTO gallery VALUES ('{$id}', '{$img[$i]}', '{$title[$i]}', '{$muuri_index[$i]}');";
        mysqli_query($conn, $query);   
    }
}

dbClose($conn);