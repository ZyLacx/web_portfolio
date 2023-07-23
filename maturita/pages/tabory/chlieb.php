<?php
if(!defined("AllowAccess")){
    header("Location: ../../index.php");
    exit();
}
?>
<div class="camp">
    <div>
        <h1 style="font-size: 2.5em;">Chlebový víkend</h1>
        <p>„Chlieb náš každodenný…“</p>
        <p>Pečenie chleba v starej peci</p>
        <img style="width: 65%; margin: 0 auto;" src="files/chlieb.png">
    </div>
    <div>
        <h1>Galérie</h1>
        <a href='?stranka=galeria&id=3'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/thumbnails/disconnected.jpg'>
            </div>
        </a>
    </div>
</div>