<?php
define("AllowAccess", TRUE);
require_once "editor-header.php";
echo "<div class='main-window'>
        <div class='menu-panel'>";
include_once("editor-menu.php");

echo "</div>";

?>
    <div class="content-div">
        <form class="form-list" action="" method="post">
        <?php
        require("functions/printContent.php");
        if(isset($_GET["trash"]) && $_GET["trash"] == true){
            echo "<h2>Príspevky - Kôš</h2>";
            $list = new printContent("articles_trash");
        }
        else if(isset($_GET["draft"]) && $_GET["draft"] == true){
            echo "<h2>Príspevky - Koncepty</h2>";
            $list = new printContent("articles_draft");
        }
        else{
            echo "<h2>Príspevky</h2>";
            $list = new printContent("articles");
        }
        
        if(isset($_GET["empty"])){
            echo '<script>Ping("Nič ste nevybrali", "alert")</script>';
        }

        if(isset($_GET["success"])){
            if(isset($_GET["success"]) && $_GET["success"] == "saved"){
                echo '<script>Ping("Príspevok bol úspešne uložený")</script>';
            }
            else{
                echo '<script>Ping("Príspevok bol úspešne uverejnený")</script>';
            }
        }
        ?>
        
        </form>
    </div>
</div>