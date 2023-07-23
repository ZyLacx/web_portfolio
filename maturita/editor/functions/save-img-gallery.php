<?php
require_once "dbConnect.php";
$conn = dbConnect();

$id = $_POST["id"];
$images = $_POST["img"];

$query = "SELECT * FROM gallery_images WHERE id='{$id}'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) <= 0){
    $query = "INSERT INTO gallery_images VALUES ('{$id}', '{$images}')";
    mysqli_query($conn, $query);
}
else{
    $query = "UPDATE gallery_images SET images='{$images}' WHERE id='{$id}';";
    mysqli_query($conn, $query);
}

dbClose($conn);