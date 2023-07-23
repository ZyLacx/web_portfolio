<?php
if(!defined("AllowAccess")){
    header("Location: ../../index.php");
    exit();
}
?>
<div class="camp">
    <div>
        <h1 style="font-size: 2.5em;">Letná škola</h1>
        <p>Každá letná škola je zameraná na konkrétne témy akou je napríklad ekológia, rozvoj kritického myslenia, náboženstvá a filozofie sveta, výchova detí v minulosti, kultúry sveta, zdravý životný štýl…</p>
        <img style="width: 65%; margin: 0 auto;" src="files/leto.png">
    </div>
    <div>
        <h1>Galérie</h1>
        <a href='?stranka=galeria&id=1'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/leto.png'>
            </div>
        </a>
        <a href='?stranka=galeria&id=2'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/thumbnails/IMG_20230706_162935.jpg'>
            </div>
        </a>
        <a href='?stranka=galeria&id=3'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/thumbnails/kvietky.jpg'>
            </div>
        </a>
        <a href='?stranka=galeria&id=4'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/thumbnails/korytnacka v2.jpg'>
            </div>
        </a>
    </div>
</div>