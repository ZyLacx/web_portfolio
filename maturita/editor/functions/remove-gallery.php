<?php
require_once "dbConnect.php";
$conn = dbConnect();

$id = $_POST["id"];
$query = "DELETE FROM gallery WHERE id='{$id}'";
mysqli_query($conn, $query);

$query = "DELETE FROM gallery_images WHERE id='{$id}'";
mysqli_query($conn, $query);

dbClose($conn);