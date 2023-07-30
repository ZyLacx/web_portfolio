<?php
if(!defined("AllowAccess")){
    header("Location: ../../index.php");
    exit();
}
?>
<div class="camp">
    <div>
        <h1 style="font-size: 2.5em;">Hľadanie parohov</h1>
        <p>Prechádzky lesmi prinášajú veľa pokladov… viac neprezradíme</p>
        <img style="width: 65%; margin: 0 auto;" src="files/img/Fox.webp">
    </div>
    <div>
        <h1>Galérie</h1>
        <a href='?stranka=galeria&id=1'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/parohy.webp'>
            </div>
        </a>
    </div>
</div>