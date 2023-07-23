<?php
if(isset($_POST["logout"])){
    require_once "../admin/logout.php";
}
if(!defined("AllowAccess")){
    header("Location: posts.php");
    exit();
}
?>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/editor.css">
    <link rel="stylesheet" href="../css/all.min.css"><link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&display=swap" rel="stylesheet"> 
    <script src="../js/all.min.js"></script>
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/pings.js"></script>
</head>
<div class="header">
    <a href='../index.php' class="admin-nav"><span class="header-text">Hlavná stránka</span><span><i class="fas fa-home"></i></span></a>
    <hr/>
    <a href="posts.php" class="admin-nav"><span class="header-text">Príspevky</span><span><i class="fas fa-copy"></i></span></a>
    <hr/>
    <a href="media.php" class="admin-nav"><span class="header-text">Média</span><span><i class="fas fa-photo-video"></i></span></a>
    <hr/>
    <a href="gallery.php" class="admin-nav"><span class="header-text">Galéria</span><span><i class="fas fa-images"></i></span></a>
    <hr/>
    <!-- <a href="/editor/settings.php" class="admin-nav">Nastavenia</a> -->
    <form action="../admin/logout.php" method="POST">
        <span class='logout-span'><span><i class="fas fa-sign-out-alt"></i></span><input type="submit" name="logout" class="logout" value="Odhlásiť"></span>
    </form>
</div>