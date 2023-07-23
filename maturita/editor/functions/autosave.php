<?php
require("articles.php");
$objArticle = new Articles();
if($_POST["title"] === "")
    $objArticle->setTitle("Príspevok");
else
    $objArticle->setTitle($_POST["title"]);

$objArticle->setDescription($_POST["description"]);
$objArticle->setThumbnailLayout($_POST["post-layout"]);
$objArticle->setThumbnailImg($_POST["thumbnail-img"]);

$return_id = $objArticle->autosave($_POST["article_id"], $_POST["table"]);
echo $return_id;
echo ":::";

if($objArticle->getError() !== null){
    echo "$objArticle->getError()";
}

$objArticle->closeCon();