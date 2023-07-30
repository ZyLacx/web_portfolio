<?php
if(!defined("AllowAccess")){
    header("Location: ../../index.php");
    exit();
}
?>
<div class="camp">
    <div>
        <h1 style="font-size: 2.5em;">Pizza víkend</h1>
        <p>Je pizza naozaj národným jedlom Talianska? Prečo taliani uznávajú Margheritu ako jedinú pravú pizzu? A kto bol Umberto? Toto všetko sa dozviete na pizza víkende okrem prípravy a pečenia pizze.</p>
        <img style="width: 65%; margin: 0 auto;" src="files/img/radio 2.webp">
    </div>
    <div>
        <h1>Galérie</h1>
        <a href='?stranka=galeria&id=2'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/pizza.webp'>
            </div>
        </a>
        <a href='?stranka=galeria&id=6'>
            <div style="width: 100%; display: flex; justify-content: center;">
                <img style="width: 250px;" src='files/img/web.webp'>
            </div>
        </a>
    </div>
</div>