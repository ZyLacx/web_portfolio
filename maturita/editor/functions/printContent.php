<?php
class printContent {

    private $elementsCount;
    private $numberOfElements;

    private $isTrash;
    private $isDraft;

    private $imgFiles = array();
    private $articleData;

    private $pageMax;
    
    private $displayedFiles = array();
    private $displayedPosts = array();

    private $nextPage = 2;
    private $prevPage = 0;

    private $options = array(10, 15, 20, 25, 30, 40, 50);

    private $deleteError = array();
    
    private $files;

    function __construct($type){
        require_once "dbConnect.php";
        // Dá do premenných zoznam files ktoré sú v súboroch /video a /img
        if(strpos($type, "articles") === false){
            if(strpos($type, "video") === false){
                $dirPath = "../files/$type";
            }
            else{
                $dirPath = "../files/$type";
            }
            $this->files = scandir($dirPath);
            $this->files = array_slice($this->files, 2); // Prvé 2 veci v arrayi budú vždy . a ..

            if(isset($_GET["trash"]) && $_GET["trash"] == true){
                $this->isTrash = true;
                $_SESSION["pageNumber"] = 1;
            }
            else{
                $this->isTrash = false;
            }
        }
        else {
            $this->articleData = $this->getArticles($type);
            if(isset($_GET["trash"]) && $_GET["trash"] == true){
                $this->isTrash = true;
                $_SESSION["pageNumber"] = 1;
            }
            else if(isset($_GET["draft"]) && $_GET["draft"] == true){
                $_SESSION["pageNumber"] = 1;
                $this->isTrash = false;
                $this->isDraft = true;
            }
            else{
                $this->isTrash = false;
            }
        }

        if(strpos($type, "video") === false)
            $this->echoContent($type);
        // else
        //     $this->echoVideos($type);
    }

    function setElementsCount($type){
        if(!isset($_SESSION["elementsCount"][$type])){
            $_SESSION["elementsCount"][$type] = 10;
        }
    }

    function getArticles($type){       
        $pdo = dbConnect("articles");
        $query = "SELECT id, title FROM $type ORDER BY id DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function echoContent($type){
        $this->setElementsCount($type);
        if(strpos($type, "articles") === false)
            $this->pageMax = ceil(count($this->files) / $_SESSION["elementsCount"][$type]);
        else
            $this->pageMax = ceil(count($this->articleData) / $_SESSION["elementsCount"][$type]);
    
        // ? PAGE HANDLER
        // Ak kliknem na tlačítka zmeň stranu 
        if(isset($_GET["page"])){
            if($_GET["page"] == "last"){
                $_SESSION["pageNumber"] = $this->pageMax;
                $this->nextPage = $_SESSION["pageNumber"] + 1;
                $this->prevPage = $_SESSION["pageNumber"] - 1;
            }
            elseif($_GET["page"] == "first"){
                $_SESSION["pageNumber"] = 1;
                $this->nextPage = $_SESSION["pageNumber"] + 1;
                $this->prevPage = $_SESSION["pageNumber"] - 1;
            }
            else{
                if($_GET["page"] > $this->pageMax)
                    $_SESSION["pageNumber"] = 1;
                elseif($_GET["page"] < 1)
                    $_SESSION["pageNumber"] = $this->pageMax;
                else
                    $_SESSION["pageNumber"] = $_GET["page"];
                    $this->nextPage = $_SESSION["pageNumber"] + 1;
                    $this->prevPage = $_SESSION["pageNumber"] - 1;
            }
            // if(isset($_POST["pageNumber"])){
            //     $_SESSION["pageNumber"] = $_POST["pageNumber"];
            //     $this->nextPage = $_SESSION["pageNumber"] + 1;
            //     $this->prevPage = $_SESSION["pageNumber"] - 1; 
            // }
        }
        else{
            $_SESSION["pageNumber"] = 1;
            $this->nextPage = $_SESSION["pageNumber"] + 1;
            $this->prevPage = $_SESSION["pageNumber"] - 1;
        }


        echo "<input type='hidden' name='type' value='$type'>";

        $this->loadList($type);
    }

    function loadList($type){
    // Hlava
        $this->splitPages($type);
        echo "<div class='menu-header'><div class='menu-func'>";
        if($this->isTrash == true)
            echo '<div class="func-buttons"><i class="fas fa-undo-alt"></i><input value="" type="submit" name="recover" alt="Obnoví vymazané súbory" id="" class="func-input"></div>'; // Recovery
        
        if($type === "articles" || $type === "articles_trash")
            echo '<div class="func-buttons"><i class="fas fa-file-signature"></i><input value="" type="submit" name="makedraft" id="" class="func-input"></div>'; // Pridanie do draftov

        echo '<div class="func-buttons"><i class="fas fa-trash-alt trash-icon"></i><input value="" type="submit" name="delete" id="" class="func-input"></div></div>'; // Mazanie
        
        echo '<div class="buttons"><div class="page-controls"><select name="numberOfElements" id="numberOfElements" onchange="this.form.submit()">';
        foreach ($this->options as $option){
            if($_SESSION["elementsCount"][$type] == $option) {
                echo "<option selected='selected' value='$option'>$option</option>";
            }
            else {
                echo "<option value='$option'>$option</option>";
            }
        }
        echo "</select>";   // Koľko bude obrázkov na jednej stránke

        if($this->isTrash == true)
            $this->loadMenuTrash($type);
        else
            $this->loadMenu($type);
        
        echo "</div>";
    // Telo
        if(strpos($type, "articles") === false)
            $this->printImages($type);
        else
            $this->printArticles($type);


    // Päta
        echo "<div class='menu-footer'>";
        if($this->isTrash == true)
            $this->loadFooterTrash($type);
        else
            $this->loadFooter($type);
        echo "</div>";
    }

    // ? Výpis hlavy (normal)
    function loadMenu($type){

        if(strpos($type, "articles") === false){
            echo '<div class="page-buttons"><a href="?page=first" class="page-next"><i class="fas fa-chevron-circle-left"></i></a>'; // Prepínanie strán
            echo '<a href="?page='.$this->prevPage.'" class="page-next"><i class="fas fa-chevron-left"></i></a>';
            echo "<span class='page-max'>".$_SESSION["pageNumber"]." z $this->pageMax</span>";
            echo '<a href="?page='.$this->nextPage.'" class="page-next"><i class="fas fa-chevron-right"></i></a>';
            echo '<a href="?page=last" class="page-next"><i class="fas fa-chevron-circle-right"></i></a></div></div>'; // Ukončenie page-buttons a page-controls div
            // echo '<a href="?video=true">Video</a>';
            echo '<div class="menu-links"><a href="?trash=true">Kôš</a></div>';
        }
        else{
            echo '<div class="page-buttons"><a href="?draft='.$this->isDraft.'&page=first" class="page-next"><i class="fas fa-chevron-circle-left"></i></a>'; // Prepínanie strán
            echo '<a href="?draft='.$this->isDraft.'&page='.$this->prevPage.'" class="page-next"><i class="fas fa-chevron-left"></i></a>';
            echo "<span class='page-max'>".$_SESSION["pageNumber"]." z $this->pageMax</span>";
            echo '<a href="?draft='.$this->isDraft.'&page='.$this->nextPage.'" class="page-next"><i class="fas fa-chevron-right"></i></a>';
            echo '<a href="?draft='.$this->isDraft.'&page=last" class="page-next"><i class="fas fa-chevron-circle-right"></i></a></div></div>'; // Ukončenie page-buttons a page-controls div
            if(isset($_GET["draft"]) && $_GET["draft"] == true)
                echo '<div class="menu-links"><a href="post-edit.php?postId=new">Nový príspevok</a>
                     <a href="?">Príspevky</a>
                     <a href="?trash=true">Kôš</a></div>';
            else
                echo '<div class="menu-links"><a href="post-edit.php?postId=new">Nový príspevok</a>
                    <a href="?draft=true">Koncepty</a>
                    <a href="?trash=true">Kôš</a></div>';                
        }
    }

// ? Výpis hlavy (trash)
function loadMenuTrash($type){

    echo '<div class="page-buttons"><a href="?trash=true&page=first" class="page-next"><i class="fas fa-chevron-circle-left"></i></a>'; // Prepínanie strán
    echo '<a href="?trash=true&page='.$this->prevPage.'" class="page-next"><i class="fas fa-chevron-left"></i></a>';
    echo "<span class='page-max'>".$_SESSION["pageNumber"]." z $this->pageMax</span>";
    echo '<a href="?trash=true&page='.$this->nextPage.'" class="page-next"><i class="fas fa-chevron-right"></i></a>';
    echo '<a href="?trash=true&page=last" class="page-next"><i class="fas fa-chevron-circle-right"></i></a></div></div>'; // Ukončenie page-buttons a page-controls div
    
    if(strpos($type, "articles") === false){
        // echo '<a href="?video=true">Video</a>';
        echo '<div class="menu-links"><a href="?">Späť</a></div>';
    }
    else{
        echo '<div class="menu-links"><a href="post-edit.php?postId=new">Nový príspevok</a>
            <a href="?">Príspevky</a>          
            <a href="?draft=true">Koncepty</a>   
            <a href="?">Späť</a></div>';       
    }
}

// ? Výpis päty (normal)

function loadFooter($type){
    if(strpos($type, "articles") === false){
        echo '<a href="?page=first" class="page-next"><i class="fas fa-chevron-circle-left"></i></a>'; // Prepínanie strán
        echo '<a href="?page='.$this->prevPage.'" class="page-next"><i class="fas fa-chevron-left"></i></a>';
        echo "<span class='page-max'>".$_SESSION["pageNumber"]." z $this->pageMax</span>";
        echo '<a href="?page='.$this->nextPage.'" class="page-next"><i class="fas fa-chevron-right"></i></a>';
        echo '<a href="?page=last" class="page-next"><i class="fas fa-chevron-circle-right"></i></a></span>';
    }
    else{
        echo '</span><a href="?draft='.$this->isDraft.'&page=first" class="page-next"><i class="fas fa-chevron-circle-left"></i></a>'; // Prepínanie strán
        echo '<a href="?draft='.$this->isDraft.'&page='.$this->prevPage.'" class="page-next"><i class="fas fa-chevron-left"></i></a>';
        echo "<span class='page-max'>".$_SESSION["pageNumber"]." z $this->pageMax</span>";
        echo '<a href="?draft='.$this->isDraft.'&page='.$this->nextPage.'" class="page-next"><i class="fas fa-chevron-right"></i></a>';
        echo '<a href="?draft='.$this->isDraft.'&page=last" class="page-next"><i class="fas fa-chevron-circle-right"></i></a></span>';
    }
}

// ? Výpis päty (trash)

function loadFooterTrash($type){
    echo '<a href="?trash=true&page=first" class="page-next"><i class="fas fa-chevron-circle-left"></i></a>'; // Prepínanie strán
    echo '<a href="?trash=true&page='.$this->prevPage.'" class="page-next"><i class="fas fa-chevron-left"></i></a>';
    echo "<span class='page-max'>".$_SESSION["pageNumber"]." z $this->pageMax</span>";
    echo '<a href="?trash=true&page='.$this->nextPage.'" class="page-next"><i class="fas fa-chevron-right"></i></a>';
    echo '<a href="?trash=true&page=last" class="page-next"><i class="fas fa-chevron-circle-right"></i></a></span>';
}

// Zobrazí videá
// function echoVideos($type){
//     echo '<form method="POST">';
//     if($this->isTrash == true){
//         echo '<span class="func-buttons"><i class="fas fa-undo-alt"></i><input value="" type="submit" name="recover" alt="Obnoví vymazané súbory" id="" class="trash"></span>'; // Recovery
//         echo '<a href="?">Obrázky</a>';
//         echo '<a href="?video=true">Späť</a>';
//     }
//     else{
//         echo '<span class="func-buttons"><i class="fas fa-trash-alt trash-icon"></i><input value="" type="submit" name="delete" id="" class="trash"></span>'; // Mazanie
//         echo '<a href="?">Obrázky</a>';
//         echo '<a href="?trash=true&video=true">Kôš</a>';
//     }
    
//     $this->printVideos($type);
//     echo '</form>';
// }

// Rozdelí array na viac častí
    function splitPages($type){
        if(strpos($type, "articles") === false){
            for($i = 0; $i < $this->pageMax; $i++){
                if($i == $this->pageMax - 1){
                    $indent = 0 - count($this->files) % $_SESSION["elementsCount"][$type];
                    if($indent == 0){
                        $this->displayedFiles[$i] = array_slice($this->files, $i * $_SESSION["elementsCount"][$type], $_SESSION["elementsCount"][$type]);
                    }
                    else{
                        $this->displayedFiles[$i] = array_slice($this->files, $indent);
                    }
                }
                else{
                    $this->displayedFiles[$i] = array_slice($this->files, $i * $_SESSION["elementsCount"][$type], $_SESSION["elementsCount"][$type]);
                }
            }
        }
        else{
            for($i = 0; $i < $this->pageMax; $i++){
                if($i == $this->pageMax - 1){
                    $indent = 0 - count($this->articleData) % $_SESSION["elementsCount"][$type];
                    if($indent == 0){
                        $this->displayedPosts[$i] = array_slice($this->articleData, $i * $_SESSION["elementsCount"][$type], $_SESSION["elementsCount"][$type]);
                    }
                    else{
                        $this->displayedPosts[$i] = array_slice($this->articleData, $indent);
                    }
                }
                else{
                    $this->displayedPosts[$i] = array_slice($this->articleData, $i * $_SESSION["elementsCount"][$type], $_SESSION["elementsCount"][$type]);
                }
            }
        }
    }

    function printArticles($type){
        if(!empty($this->displayedPosts)){
            echo "<table>";
            foreach($this->displayedPosts[$_SESSION["pageNumber"]-1] as $index => $post){
                $id = $this->displayedPosts[$_SESSION["pageNumber"]-1][$index]['id'];
                $title = $this->displayedPosts[$_SESSION["pageNumber"]-1][$index]['title'];
                echo "<tr class='table-row'><td>";
                echo '<input type="checkbox" name="names[]" value="'.$this->displayedPosts[$_SESSION["pageNumber"]-1][$index]['id'].'">';
                echo "</td>";
                if($type === "articles"){
                    echo "<td class='article-title'><a href='../index.php?id=$id'>$title</a></td>";
                }
                else {
                    echo "<td class='article-title'><a>$title</a></td>";
                }
                echo "<td class='article-edit'><a href='post-edit.php?postId=$id'><i class='fas fa-edit'></i></a></td>";
                echo "</tr>";
                /*echo '<td class="article-cat">';
                    echo $postData[$i]["categories"];
                echo "</td>";*/
            }
            echo "</table>";
        }
        else{
            echo "<script>Ping('Prázdny priečinok')</script>";
        }
    }

    function printImages($type){
        echo '<table>';
        foreach($this->displayedFiles[$_SESSION["pageNumber"]-1] as $file){
            echo '<tr class="table-row">';
            echo '<td><input type="checkbox" name="names[]" value="'. $file .'"></td>';
            echo '<td align="center"><img class="table-img" src="../files/'.$type.'/'.$file.'" alt=""></td>';
            echo '<td align="center" class="img-name">'. $file .'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    // function printVideos($type){
    //     foreach($this->files as $file){
    //         echo '<div>
    //                 <input type="checkbox" name="names[]" value="'. $file .'">
    //                 <video controls>
    //                     <source src="../files/'.$type.'/'.$file.'">
    //                     Váš prehliadač nepodporuje HTML video
    //                 </video>
    //             </div>';            
    //     }
    // }
}