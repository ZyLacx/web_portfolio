<?php
if(!defined("AllowAccess")){
    header("Location: ../../index.php");
    exit();
}
?>
<div class="camp">
    <div>
        <h1 style="font-size: 2.5em;">Jablkový víkend</h1>
        <p>„Jedno jablko denne a lekár si k vám nenájde cestu.“</p>
        <p>Oberanie jabĺk, sušenie, varenie kompótu, omáčky, pečenie jablkových koláčov, ale i netradičné spracovanie jabĺk čaká na deti, ktoré sa neboja pracovať, variť a piecť. Ktorej bohyni venoval Paris zlaté jablko, na čo prišiel Newton, keď mu spadlo jablko na hlavu a iné zaujímavosti sa dozviete pod našim stromom poznania.</p>
        <img style="width: 65%; margin: 0 auto;" src="files/jablko.webp">
    </div>
    <div>
        <h1>Galérie</h1>
        <a href='?stranka=galeria&id=7'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/jablko.webp'>
            </div>
        </a>
        <a href='?stranka=galeria&id=1'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/audio.webp'>
            </div>
        </a>
    </div>
</div>