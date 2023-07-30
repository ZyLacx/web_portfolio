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
        <img style="width: 65%; margin: 0 auto;" src="files/leto.webp">
    </div>
    <div>
        <h1>Galérie</h1>
        <a href='?stranka=galeria&id=1'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/leto.webp'>
            </div>
        </a>
        <a href='?stranka=galeria&id=2'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/IMG_20230706_162935.webp'>
            </div>
        </a>
        <a href='?stranka=galeria&id=3'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/kvietky.webp'>
            </div>
        </a>
        <a href='?stranka=galeria&id=4'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/korytnacka v2.webp'>
            </div>
        </a>
    </div>
</div>