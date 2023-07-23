<?php
// Window s obrázkami
$multiple = "";
if(isset($_POST["gallery"])){
    $multiple = "multiple";
    $gallery = $_POST["gallery"];
}
else{
    $gallery = false;
}

		echo '<div class="window" id="imageChooseWindow">
				<div class="top-bar">
                    <a class="window-close" onClick="hideWindow();"><i class="fas fa-times-circle"></i></a>
                    <input type="range" min="100" max="400" value="200" id="size-slider">
				</div>
                <div class="img-choose-content">
                    <h1>Nahrajte súbor</h1>
                        <input style="margin-left: 15px;" '.$multiple.' type="file" name="files[]" id="uploadFile" accept="image/png, image/jpeg">
                        <button onclick="uploadImg('.$gallery.')" id="img-upload">Nahrať na server</button>';
	
		$dirPath = "../../files/img/thumbnails";
       	$imgFiles = scandir($dirPath);
        $imgFiles = array_slice($imgFiles, 2);

        echo '<h1>Alebo zvoľte z existujúcich</h1>';
        foreach ($imgFiles as $image) { // po kliknutí na <a> sa zapne javascript do ktorého sa dá názov obrázka a ten obrázok dá do URL v CKEditore
            echo "<div class='thumb-div'>
                <a href='javascript:selectImage(\"$image\", $gallery)'> 
                    <img src='../../files/img/thumbnails/$image' class='thumbnail'  id='thumbnail'>
                </a>
            </div>";
        }
			echo '</div></div>';
?>