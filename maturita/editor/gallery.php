<?php
define("AllowAccess", TRUE);
include("editor-header.php");
echo "<div class='main-window'>
        <div class='menu-panel'>";
include_once("editor-menu.php");
?>
<script>
var max_id = 1;
var empty = false;
</script>
<?php
echo "</div><div class='content-div'>";
    require_once "functions/dbConnect.php";
    $pdo = dbConnect("gallery");

    if(isset($_GET["id"])){
        echo '<button id="edit-save" onclick="edit(true);">Upraviť</button>';

        $query = "SELECT max(id) FROM gallery";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result) > 0){
            $id = $result[0];
            echo "<script>max_id = ".$id["max(id)"]."</script>";
        }
        else{
            echo "<script>Ping('Error', 'alert')</script>";
        }

        echo "<a class='gallery-back' href='?'>Späť</a>";
        $query = "SELECT media_name FROM gallery_media WHERE gallery_id=:id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":id", $_GET["id"], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(count($result) > 0){
            echo "<div class='grid'>";
            for ($i = 0; $i < count($result); $i++){
                echo "<div class='item no-drag' id='$i'>
                        <div class='item-content'>
                            <img id='{$result[$i][0]}' src='../files/img/{$result[$i][0]}'>
                        </div>
                    </div>";
            }
            echo "</div>";
        }
        else{
            echo '<script>Ping("Prázdna galéria");</script>';
            echo "<div class='grid'>
                <div class='item no-drag' id='add-item'>
                    <div class='item-content'>
                        <a onclick='imageChooseWindow(true)'><i class='fas fa-plus'></i></a>
                    </div>
                </div>
            </div>";
        echo "<script>empty = 'yes';</script>";
        }
    }
    else{
        echo '<button id="edit-save" onclick="edit();">Upraviť</button>';
        $query = "SELECT * FROM gallery ORDER BY id";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows) > 0){
            $query = "SELECT max(id) FROM gallery";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($result) > 0){
                $id = $result[0];
                echo "<script>max_id = ".$id["max(id)"]."</script>";
            }
            else{
                echo "<script>Ping('Error', 'alert')</script>";
            }
        
            echo "<div class='grid'>";
            foreach($rows as $data){
                echo "<div class='item no-drag' id='".$data["id"]."'>
                        <div class='item-content'>
                            <a id='gallery_".$data["id"]."' href='?id=".$data["id"]."'>
                                <img id='".$data["img"]."' src='../files/img/".$data["img"]."'>
                                <p>".$data["title"]."</p>
                            </a>
                        </div>
                    </div>";
            }
            echo "</div>";
        }
        else{
            echo "<div class='grid'>
                <div class='item no-drag' id='add-item'>
                    <div class='item-content'>
                        <a onclick='imageChooseWindow()'><i class='fas fa-plus'></i></a>
                    </div>
                </div>
            </div>";
            echo "<script>empty = true</script>";
        }
    }



/* //! PORIEŠ CANCEL NECH FUNGUJE SPRÁVNE, LEN ZMAPUJ INDEXY PRI EDIT() 
 AKO ROBÍŠ KEĎ DÁVAŠ DO DATABÁZE AJ V TOM LINKU TO JE ČO MÁŠ NA DISCORDE
 A paragrafy si len podeľ nejak do array podľa idčok    
AJ PRI DELETE TI TREBA ULOŽIŤ DO DATABÁZY LEBO SA VLASTNE RESETOVALI ZMENY
A PRI CANCELI DELETE Z GALLERY_AUTOSAVE
KEĎ VYMAŽEŠ GALÉRIU VYMAŽ JU CELÚ AJ S OBRÁZKAMI ČO SÚ V NEJ
 */

?>
</div>
<script src="../js/muuri.min.js"></script>
<script src="../js/web-animations.min.js"></script>
<script>

// ? 0 -> index, 1 -> id, 2 -> title, 3 -> img

var originalGrid = [[],[],[],[]];

var grid = new Muuri(".grid", {
    dragEnabled: true,
    showDuration: 200,
    hideDuration: 200,
    layoutDuration: 200,
    
    dragStartPredicate: function (item, event) {
    // Prevent first last from being dragged. 
    if (item.getElement().classList.contains("add-item") || item.getElement().classList.contains("no-drag")) {
        return false;
    }
    // For other items use the default drag start predicate.
    return Muuri.ItemDrag.defaultStartPredicate(item, event);
    }
});

grid.on("dragStart", function (item, event){
    var dragging = item.getElement();
    dragging.style.cursor = "grabbing";
});

grid.on("dragEnd", function(item, event){
    var dragging = item.getElement();
    dragging.style.cursor = "grab";
});

if(empty === true){
    let btn = document.getElementById("edit-save");
    btn.textContent = "Uložiť";
    btn.setAttribute("onclick", "save()");
}
else if(empty == "yes"){
    let btn = document.getElementById("edit-save");
    btn.textContent = "Uložiť";
    btn.setAttribute("onclick", "save(true)");
}

function selectImage(image, gallery = false, times = 1){
    if(times == 1){
        image = image.replace(/ /gi, '_');
        var item_div = document.createElement("div");
        item_div.className = "item draggable";
        if(gallery){        
            max_id++;
            $('<div class="item-content"><img id='+image+' src="../files/img/'+image+'"></div><div class="remove-gallery" onclick="removeGallery('+max_id+', true)"><i class="fas fa-times"></i></div>').appendTo(item_div);
            item_div.id = max_id;
        }
        else{
            $('<div class="item-content"><img id='+image+' src="../files/img/'+image+'"><p contenteditable="true">Nová galéria</p></div><div class="remove-gallery" onclick="removeGallery('+max_id+')"><i class="fas fa-times"></i></div>').appendTo(item_div);
            item_div.id = max_id;
        }
        
        var add_index = document.getElementsByClassName("item").length;
        var newItems = grid.add([item_div], {index: add_index});
    }
    else{
        for(i = 0; i < times; i++){
            var imageName = image[i]["name"];
            imageName = imageName.replace(/ /gi, '_');
            var item_div = document.createElement("div");
            item_div.className = "item draggable";
            if(gallery){        
                max_id++;
                $('<div class="item-content"><img id='+imageName+' src="../files/img/'+imageName+'"></div><div class="remove-gallery" onclick="removeGallery('+max_id+', true)"><i class="fas fa-times"></i></div>').appendTo(item_div);
                item_div.id = max_id;
            }
            else{
                $('<div class="item-content"><img id='+imageName+' src="../files/img/'+imageName+'"><p contenteditable="true">Nová galéria</p></div><div class="remove-gallery" onclick="removeGallery('+max_id+')"><i class="fas fa-times"></i></div>').appendTo(item_div);
                item_div.id = max_id;
            }
            
            var add_index = document.getElementsByClassName("item").length;
            var newItems = grid.add([item_div], {index: add_index});
        }
    }
    reindex();
}

function edit(gallery = false){
// Cez gallery zisťujem či som v galérii s obrázkami alebo linkami
// ? Musím si uložiť všetko nech to viem obnoviť potom pri canceli
    var originalIndex = grid.getItems().map(item => item.getElement().getAttribute("id"));

    var elementsCount = document.getElementsByClassName("item").length;
    var button = document.getElementById("edit-save");
    var divs = document.getElementsByClassName("item");

    let gridDiv = document.getElementsByClassName("grid");

    
    for(i = 0; i < divs.length; i++){
        divs[i].className += " draggable";
        divs[i].classList.remove("no-drag");
        $(divs[i]).css("cursor", "grab");
        if(gallery){
            $("<div class='remove-gallery' onclick='removeGallery("+divs[i].id+", true)'><i class='fas fa-times'></i></div>").appendTo(divs[i]);
        }
        else{
            $("<div class='remove-gallery' onclick='removeGallery("+divs[i].id+")'><i class='fas fa-times'></i></div>").appendTo(divs[i]);
            let id = `#gallery_${divs[i].id}`;
            var aContents = $(id).contents();
            $(id).replaceWith(aContents);
        }


        // var child = divs.children;
        // var grandchild = child[0].children;

        // originalGrid[0][i] = originalIndex[i];
        // originalGrid[1][i] = divs[i].id;
        // originalGrid[2][i] = grandchild[1].textContent;
        // originalGrid[3][i] = grandchild[0].id;
    }

    // console.log(originalIndex);
    var div = document.createElement("div");
    div.id = "add-item";
    div.className = "add-item";
    
    if(gallery){
        $("<a onclick='imageChooseWindow(true)'><i class='fas fa-plus'></i></a>").appendTo(div);
        button.setAttribute("onclick", "save(true)");
    }
    else{
        $("p").attr("contenteditable", "true");
        $("<a onclick='imageChooseWindow()'><i class='fas fa-plus'></i></a>").appendTo(div);
        button.setAttribute("onclick", "save()");
    }

    var newItem = grid.add([div], {index: -1});
    button.textContent = "Uložiť";

    // $("#edit-save").after("<button onclick='cancel()' id='cancel-edit'>Zrušiť zmeny</button>");
}

setInterval(autosave, 30000);

function autosave(){

}

function save(gallery = false){

    removeButtons(gallery);
    
    var divs = document.getElementsByClassName("item");
    
    $(".remove-gallery").remove();

    for(i = 0; i < divs.length; i++){
        divs[i].className += " no-drag";
        divs[i].classList.remove("draggable");
        $(divs[i]).css("cursor", "pointer");
        if(gallery === false){
            let item_content = divs[i].children;
            let id = divs[i].id;
            let val = `#${id} div`;
            $(val).children().wrapAll('<a id="gallery_'+id+'" href="?id='+id+'"></a>')
        }
    }
    
    
    var formData = new FormData();

    if(gallery){
        index = grid.getItems().map(item => item.getElement().getAttribute("id"));

        let imgArr = "";
        
        for(i = 0; i < index.length; i++){
            let item_content = $("#"+index[i]).children();
            let img = item_content.children();
            
            if(i == index.length - 1)
                imgArr += img[0].id;
            else
                imgArr += img[0].id + "/";
        }

        let url = window.location.href;
        let url_split = url.split("?id=");
        let id = url_split[1];


        formData.append("img", imgArr);
        formData.append("id", id);

        // $.ajax({
        //     url: "functions/save-img-gallery.php",
        //     method: "POST",
        //     data: formData,
        //     contentType: false,
        //     processData: false,
        //     cache: false,
        //     success: function (return_data){
        //         if(return_data != ""){
        //             Ping(return_data, "alert");
        //         }
        //         else{
        //             Ping("Úspešne uložené!");
        //         }
        //     }
        // });
    }
    else{     
        var ids = [];
        var index = [];
        var title = [];
        var img = [];
        var muuri_index = [];
        $("p").attr("contenteditable", "false");
        index = grid.getItems().map(item => item.getElement().getAttribute("id"));
        for(i = 0; i < index.length; i++){
    // Zober a s ID ktoré sa rovná indexu nech ide do databáze v tom poradí
            let div = $("#gallery_"+index[i]);
            let child = div.children();
            ids[i] = index[i];
            title[i] = child[1].textContent;
            img[i] = child[0].id;
            muuri_index[i] = i;
        }

        formData.append("muuri_index", muuri_index);
        formData.append("id", ids);
        formData.append("title", title);
        formData.append("img", img);
    
        // $.ajax({
        //     url: "functions/save-gallery.php",
        //     method: "POST",
        //     data: formData,
        //     contentType: false,
        //     processData: false,
        //     cache: false,
        //     success: function (return_data){
        //         if(return_data != ""){
        //             Ping(return_data, "alert");
        //         }
        //         else{
        //             Ping("Úspešne uložené!");
        //         }
        //     }
        // });
    }
}

function imageChooseWindow(gallery = false){
    reindex();

	$("body").append("<div id='shade'></div>");
	// $("#shade").load("functions/imageChooseWindow.php");
	
    let formData = new FormData();
    formData.append("gallery", gallery);

	$.ajax({
		url: "functions/imageChooseWindow.php",
        method: "POST",
		contentType: false,
		processData: false,
        data: formData,
		cache: false,
		success: function (return_data){
            // Opraví path aby načítalo obrázok na reálnom servery
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

// Zmení z-index nech sa nezobrazujú nad #shade-om
function reindex(){
    var divs = document.getElementsByClassName("item");
    for(i = 0; i < divs.length; i++){
        divs[i].style.zIndex = 0;
    }
}

// function cancel(){
//     grid.remove(grid.getItems(), {removeElements: true});

//     for (i = 0; i < originalGrid[0].length; i++){
//         let item_div = document.createElement("div");
//         item_div.className = "item no-drag";
//         item_div.id = originalGrid[1]
        // ! PODĽA INDEXU TU TAM DAJ HLAVNE

//         grid.add()
//     }

//     // var divs = document.getElementsByClassName("new");
//     // var elem = grid.getItems(divs);
//     // grid.remove(elem, {removeElements: true});

//     // divs = document.getElementsByClassName("item"); 
//     // for(i = 0; i < divs.length; i++){
//     //     divs[i].className += " no-drag";
//     //     divs[i].classList.remove("draggable");
//     // }

//     removeButtons();
// }

// Odstráni tlačítko zrušiť zmeny a z gridu tlačítko na pridávanie
function removeButtons(gallery = false){
    var addButton = document.getElementById("add-item");
    var elem = grid.getItems(addButton);
    grid.remove(elem, {removeElements: true});

    // $("#cancel-edit").remove();
    var button = document.getElementById("edit-save");
    button.textContent = "Upraviť";
    button.setAttribute("onclick", "edit("+gallery+")");
}

function uploadImg(gallery = false){
	let formData = new FormData();
	let file = document.getElementById("uploadFile");   

	// if (file.value != ""){
    //     for(i = 0; i < file.files.length; i++){
	// 		formData.append("file["+i+"]", file.files[i]);
    //     }		
	// 		$.ajax({
	// 			url: "functions/uploadImage.php",
	// 			method: "POST",
	// 			data: formData,
	// 			contentType: false,
	// 			processData: false,
	// 			cache: false,
	// 			success: function (return_data){
	// 				if(return_data !== ""){
	// 					Ping(`Chyba: ${return_data}`, "alert");
	// 				}
	// 				else {
    //                     if(file.files.length == 1){
    //                         selectImage(file.files[0]["name"], gallery);
    //                     }
    //                     else{
    //                         selectImage(file.files, gallery, file.files.length);
    //                     }
	// 				}

	// 			}
	// 		});
	// }
	// else{
	// 	Ping(`Nič ste nevybrali!`, "alert");
	// }

    hideWindow();
}

function removeGallery(id, gallery = false){
// Cez gallery zisťujem či som v galérii s obrázkami alebo linkami

    let removeGallery = document.getElementById(id);
    grid.remove(grid.getItems(removeGallery), {removeElements: true});

    // if(gallery === false){
    //     let formData = new FormData();
    //     formData.append("id", id);
    //     let galleryName = removeGallery.textContent.trim();
    //     $.ajax({
    //         url: "functions/remove-gallery.php",
    //         method: "POST",
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         cache: false,
    //         success: function (return_data){
    //             if(return_data !== ""){
    //                 Ping(`Chyba: ${return_data}`, "alert");
    //             }
    //             else {
    //                 Ping("Vymazali ste galériu "+galleryName+"");
    //             }
    
    //         }
    //     });
    // }
}
</script>