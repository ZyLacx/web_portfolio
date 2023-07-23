<?php
define("AllowAccess", TRUE);
include_once("editor-header.php");
include_once("editor-menu.php");
// ? Variables
$post_layout = "";
$post_thumbnail = "";
$post_actual_id = "";
$postData = [];
$new = false;

if(isset($_GET["postId"])){
	$postId = $_GET["postId"];
	if($postId == "new"){
		$new = true;
		$post_layout = "";
		$post_thumbnail = "";
		$post_actual_id = "";
	}
	else{
		require_once "functions/dbConnect.php";
		$pdo = dbConnect("articles");
		$query = "SELECT * FROM articles WHERE id=$postId;";
		$stmt = $pdo->prepare($query);
		$stmt->execute();
		$postData = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
		if (count($postData) > 0){
			$post_layout = $postData["thumbnail_style"];
			$post_thumbnail = $postData["thumbnail"];
			$post_actual_id = $postData["id"];
		}
        else {
            echo "<script>Ping('Nastala chyba', 'alert')</script>";
		}
	}
}
else {
	// Ak nebolo zadané id tak ho daj na new
	$new = true;
	echo '<script>window.history.pushState("", "Editor", "?postId=new");</script>';
}

require "functions/articles.php";
$objArticle = new Articles();


// ? Upload a save
$titleError = false;
if (isset($_POST["save"])){
	if(!empty($_POST["title"])){
		// uploadSave("save", $objArticle);
		echo "<script>Ping('Unavailable!', 'alert')</script>";
	}
	else{
		$titleError = true;
		echo "<script>Ping('Príspevok musí mať názov!', 'alert')</script>";
	}
}

if(isset($_POST["upload"])){
	if(!empty($_POST["title"])){
		// uploadSave("upload", $objArticle);
		echo "<script>Ping('Unavailable!', 'alert')</script>";
	}
	else {
		$titleError = true;
		echo "<script>Ping('Príspevok musí mať názov!', 'alert')</script>";
	}
}

echo '<div class="main-window">
			<div class="menu-panel">';
?>
		</div>
		<form action="" method="POST" style="margin: 0;"  class="editor-area">
		<div>
			<input type="text" name="title" id="title" placeholder="Názov príspevku..." maxlength="60" class="<?php if($titleError == true) echo "title-error title-input"; else echo "title-input";?>" value="<?php if($new == false) echo $postData["title"]?>">
			<textarea id="editor" name="editor" class="editor"><?php
				if($new == false){
					echo $postData["content"];
				}
				?></textarea>
		</div>
		<div class="side-panel">
			<h1>Nastavenia príspevku</h1>
			<div class="post-settings">
				<div>
					<span>Pridať náhľadový obrázok</span><input type="checkbox" onclick="checkBox()" id="checkbox">
					<div id="thumbnail-settings">
					</div>
				</div>
				<div id="description">
					<span>Popis príspevku</span><i class="fas fa-question-circle"></i><div class="hint">Ak toto pole ostane prázdne použije sa ako popis prvých 450 znakov, po nich tri bodky.</div>
					<textarea name="description" cols="30" rows="6"><?php
						if($new == false){
							echo trim($postData["desc"]);
						}
					?></textarea>
				</div>
				<!-- <div id="categories">
					<span>Kategórie</span><i class="fas fa-question-circle"></i>
					<textarea name="tags" cols="30" rows="5"></textarea>
				</div> -->
			</div>
			<div class="post-edit-btns">
				<span class="post-submit"><span class="post-submit-icon"><i class="fas fa-file-upload"></i></span><input type="submit" value="Uložiť ako koncept" class="article-save" id="save" name="save" title="Uloží príspevok ako koncept" alt="Uloží príspevok ako koncept"></span>
				<span class="post-submit"><span class="post-submit-icon"><i class="fas fa-cloud-upload-alt"></i></span><input type="submit" value="Vytvoriť príspevok" class="article-upload" id="upload" name="upload" title="Uloží a nahrá príspevok" alt="Uloží a nahrá príspevok"></span>
				<input type="hidden" id="post-layout" name="post-layout" value="<?php echo $post_layout ?>">
				<input type="hidden" id="post-thumbnail" name="post-thumbnail" value="<?php echo $post_thumbnail ?>">
				<input type="hidden" id="autosave-id" name="autosave-id" value=""> <!-- // ? Keď sa autosavene post tak sem sa uloží ID v articles_draft  -->
			</div>

<?php
// ? Upload save funkcia
function uploadSave($type, $objArticle){
// SAVE if is in autosave-id -> update else insert, UPLOAD -> autosave-id = delete path
	$objArticle->setTitle($_POST["title"]);
	$objArticle->setDescription($_POST["editor"]);
	$objArticle->setThumbnailLayout($_POST["post-layout"]);
	$objArticle->setThumbnailImg($_POST["post-thumbnail"]);
	if(isset($_GET["postId"]) && $_GET["postId"] == "new"){
		if($type == "upload") {
			// $objArticle->upload_new($_POST["autosave-id"]);
		}
		elseif($type == "save") {
			// $objArticle->save_new($_POST["autosave-id"]);
		}
		else {
			echo '<div>Chyba: problém s funkciou uploadSave()</div>';
		}
	}
	else{
		$data = explode(":", $_GET["postId"]);
		$post_id = $data[0];
		$post_table = $data[1];
		if($type == "upload"){
			// $objArticle->setId($_POST["autosave-id"]);
			// $objArticle->upload($post_id, $post_table);
		}
		elseif($type == "save"){
			// $objArticle->setId($_POST["autosave-id"]);
			// $objArticle->save($post_id, $post_table);
		}
		else{
			echo '<div>Chyba: problém s funkciou uploadSave()</div>';
		}
	}

	if($type == "save"){
		header("Location: posts.php?success=saved&draft=true");
	}
	else{
		header("Location: posts.php?success=uploaded");
	}
	exit();
}

?>		

		</div>
	</div>
</form>



<script src="ckeditor/ckeditor.js"></script>
<script src="../js/jquery-3.5.1.min.js"></script>
	
<script>


CKEDITOR.replace( 'editor', {
		extraPlugins: "image2, videoembed",
		filebrowserImageBrowseUrl: 'functions/upload.php',
		filebrowserBrowseMethod: "form"
	});
	
CKEDITOR.on('dialogDefinition', function(e) {
		dialogName = e.data.name;
		dialogDefinition = e.data.definition;
		if(dialogName == 'image2') {
			var infoTab = dialogDefinition.getContents('info');
			
			infoTab.remove("lock");
		}
	});


if($("#post-layout").val() !== "" || $("#post-thumbnail").val() !== ""){	
	$("#checkbox").prop('checked', true);
	var parentDiv = document.getElementById("thumbnail-settings");
	showOptions(parentDiv);

	let style = $("#post-layout").val();
	$("#"+style).attr("class", "selected");

	selectImage($("#post-thumbnail").val());
}

function checkBox(){
	var isChecked = $('#checkbox').is(':checked');
	var parentDiv = document.getElementById("thumbnail-settings");
	if(isChecked == true){
		showOptions(parentDiv);
	}
	else{
		hideOptions(parentDiv);
		$("#post-thumbnail").val(null);
		$("#post-layout").val(null);
	}	
}

// ? Daj do autosave-id inputu aké je id postu po načítaní stránky

let url_arr = window.location.href.split("postId=");
$("#autosave-id").val(url_arr[1]);

// setInterval(autoSave, 300000);

// function autoSave(){
// 	let formData = new FormData();
// 	let editor_content = CKEDITOR.instances["editor"].getData();
// 	let title = $("#title").val();
// 	let thumbnail_img = $("#post-thumbnail").val();
// 	let post_layout = $("#post-layout").val();
// 	let article_info = $("#autosave-id").val();
// 	let article_id;
// 	let article_table;

// 	if(article_info == "new"){
// 		article_id = "new";
// 		article_table = "articles_draft";
// 	}
// 	else{
// 		let vars = article_info.split(":");
// 		article_id = vars[0];
// 		article_table = vars[1];
// 	}

// 	formData.append("title", title);
// 	formData.append("description", editor_content);
// 	formData.append("post-layout", post_layout);
// 	formData.append("thumbnail-img", thumbnail_img);
// 	formData.append("article_id", article_id);
// 	formData.append("table", article_table);

// 	$.ajax({
// 		url: "functions/autosave.php",
// 		method: "POST",
// 		data: formData,
// 		contentType: false,
// 		processData: false,
// 		cache: false,
// 		success: function (return_data){
// 			let return_info = return_data.split(":::");
// 			let id = return_info[0];
// 			let error = return_info[1];
// 			$("#autosave-id").val(id+":"+article_table);
// 		}
// 	});
// }

// ! SAVE => replace by ID, url :trash :draft -> z kade mám brať id, 

function showOptions(parentDiv){
	// * titles
	var titleArray = [document.createTextNode("Obrázok vľavo"),
		document.createTextNode("Obrázok vpravo"),
		document.createTextNode("Obrázok nad príspevkom"),
		document.createTextNode("Obrázok pod príspevkom"),
		document.createTextNode("Obrázok obklopený textom (vľavo)"),
		document.createTextNode("Obrázok obklopený textom (vpravo)")];
	
	var maindiv = document.createElement("div");
	maindiv.id = "maindiv";
	// *  customize DOM elements
	var imgOptions = ["functions/images/left.png", "functions/images/right.png", "functions/images/up.png", "functions/images/down.png", "functions/images/fancyLeft.png", "functions/images/fancyRight.png"]
	var values = ["left", "right", "up", "down", "fancyLeft", "fancyRight"];
	var elements = [];
	var i;
	// * Vytvorí elementy a dá ich do divov
	for(i = 0; i < titleArray.length; i++){
		// var subdiv = document.createElement("div");
		let h3 = document.createElement("h3");
		let img = document.createElement("img");
		let a = document.createElement("a");
		a.id = values[i];
		a.addEventListener("click", selectLayout, false);
		a.layout = values[i];
		img.src = imgOptions[i];
		h3.appendChild(titleArray[i]);
		a.appendChild(img);
		a.appendChild(h3);
		maindiv.appendChild(a);
	}
	var imgDiv = document.createElement("div");
	imgDiv.className = "layout-img-div";
	var button = document.createElement("div");
	var button_txt = document.createTextNode("Vybrať obrázok");
	button.addEventListener("click", imageChooseWindow);
	button.appendChild(button_txt);
	button.className = "choose-img-btn";
	var chosenImgDiv = document.createElement("div");
	chosenImgDiv.id = "chosen-img-div";
	imgDiv.appendChild(button);
	imgDiv.appendChild(chosenImgDiv);
	parentDiv.appendChild(maindiv);
	parentDiv.appendChild(imgDiv);
}

function hideOptions(){
	$("#thumbnail-settings").empty();
}

function selectLayout(evt){
	let reset_selected = document.getElementsByClassName("selected")[0];
	if(reset_selected === undefined){
		console.log(reset_selected);
	}
	else{
		reset_selected.className = "";
	}
	let selected = document.getElementById(evt.currentTarget.layout);
	selected.className = "selected";
	$("#post-layout").val(evt.currentTarget.layout);
}

function imageChooseWindow(){
	$("body").append("<div id='shade'></div>");
	// $("#shade").load("functions/imageChooseWindow.php");
	
	$.ajax({
		url: "functions/imageChooseWindow.php",
		contentType: false,
		processData: false,
		cache: false,
		success: function (return_data){
			var echoData = return_data.replace(/src='..\/..\//gi, "src='../");
			$("#shade").append(echoData);

			// Hýbanie sliderom mení veľkosť obrázkov
			var slider = document.getElementById("size-slider");
			var thumbClass = document.getElementsByClassName("thumbnail");
			slider.oninput = function () {
				for (i = 0; i < thumbClass.length; i++)
					thumbClass[i].style = "height: "+slider.value+";";
			}
		}
	});
}

function hideWindow(){
	$("#shade").remove();
}

function uploadImg(){
	let formData = new FormData();
	let file = document.getElementById("uploadFile");
	if (file.files[0] !== undefined){
		Ping(`Unavailable`, "alert");
		// var fileName = file.files[0]["name"].replace(" ", "_");
		// 	formData.append("file[0]", file.files[0]);
		
		// 	$.ajax({
		// 		url: "functions/uploadImage.php",
		// 		method: "POST",
		// 		data: formData,
		// 		contentType: false,
		// 		processData: false,
		// 		cache: false,
		// 		success: function (return_data){
		// 			if(return_data !== ""){
		// 				Ping(`Chyba: ${return_data}`, "alert");
		// 			}
		// 			else {
		// 				selectImage(fileName);
		// 			}

		// 		}
		// 	});
	}
	else{
		Ping(`Nič ste nevybrali!`, "alert");
	}
}

function removeImg(){
	$("#chosen-img-div").empty();
	$("#post-thumbnail").val(null);
	$(".choose-img-btn").text("Vybrať obrázok");
}

function selectImage(name){
	hideWindow();
	name = name.replace(/ /gi, "_");
	let path = "../files/img/thumbnails/"+name;
	if($(".choose-img-btn").text() == "Vybrať obrázok"){
		let img = "<div><img id='chosen-img' src="+path+"><div class='remove' onclick='removeImg()'><i class='fas fa-times'></i></div></div>";
		$("#chosen-img-div").append(img);
		$(".choose-img-btn").text("Zmeniť obrázok");
	}
	else{
		$("#chosen-img").attr("src", path);
	}
	$("#post-thumbnail").val(name);
}

</script>