<?php

class Articles {
    private $autosave_id;
    private $title;
    private $desc;
    private $pdo;
    private $error;
    private $return_table;
    private $thumb_img;
    private $layout;
    private $id;
    
    public function __construct()
    {
        require_once 'functions/dbConnect.php';
        $this->pdo = dbConnect("articles");
    }

    function setId($id) {$this->autosave_id = $id;}
    function getId() {return $this->id;}
    function setTitle($title) {$this->title = $title;}
    function getTitle() {return $this->title;}
    function setDescription($desc) {$this->desc = $desc;}
    function getDescription() {return $this->desc;}
    function setThumbnailImg($img) {$this->thumb_img = $img;}
    function setThumbnailLayout($layout) {$this->layout = $layout;}
    function getError() {return $this->error;}

    public function upload_new($autosave_id){
        if($autosave_id == "new"){
            $query = "INSERT INTO articles VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        else{
            $var_array = explode(":", $autosave_id);
            $id = $var_array[0];

            $query = "INSERT INTO articles VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $query = "DELETE FROM articles_draft WHERE id='$id'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
    }

    public function save_new($autosave_id){
        if($autosave_id == "new"){
            $query = "INSERT INTO articles_draft VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        else{
            $var_array = explode(":", $autosave_id);
            $id = $var_array[0];

            $query = "UPDATE articles_draft SET title='$this->title', description='$this->desc', thumbnail='$this->thumb_img', thumbnail_style='$this->layout'; WHERE id='$id'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
    }

    public function save($id, $table) {
        $var_array = explode(":", $this->autosave_id);
        $autosave_id = $var_array[0];
        $autosave_table = $var_array[1];

        if($table == "articles"){
            // Ak je to už savenuté v autosave tak sa to uložilo do draftov tak to len updatni
            if($autosave_table == "articles_draft"){
                $query = "UPDATE articles_draft SET title='$this->title', description='$this->desc', thumbnail='$this->thumb_img', thumbnail_style='$this->layout' WHERE id='$autosave_id'";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }
            // Keď sa to neuložilo tak sprav nový zápis
            else{
                $query = "INSERT INTO articles_draft VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }
        }
        else if($table == "articles_trash"){
            // Ak je to z koša tak ho vlož do draftu a vymaž z koša, ak je autosavnutý tak je to jedno lebo ukladá do trashu
            $query = "INSERT INTO articles_draft VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $query = "DELETE FROM articles_trash WHERE id='$id'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        else{
            $query = "UPDATE articles_draft SET title='$this->title', description='$this->desc', thumbnail='$this->thumb_img', thumbnail_style='$this->layout' WHERE id='$id'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
    }    

    public function upload($id, $table) {
        $var_array = explode(":", $this->autosave_id);
        $autosave_id = $var_array[0];
        $autosave_table = $var_array[1];

        if($table == "articles"){
            if($autosave_table == "articles_draft"){
                $query = "UPDATE articles SET title='$this->title', description='$this->desc', thumbnail='$this->thumb_img', thumbnail_style='$this->layout' WHERE id='$id'";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();

                $query = "DELETE FROM articles_draft WHERE id='$autosave_id'";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }
            else{
                print_r($id);
                $query = "UPDATE articles SET title='$this->title', description='$this->desc', thumbnail='$this->thumb_img', thumbnail_style='$this->layout' WHERE id='$id'";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(); 
            }
        }
        else{
            $query = "INSERT INTO articles VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $query = "DELETE FROM $table WHERE id='$id'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }

    }

    public function autosave($id, $table){
        if($id == "new"){
            $query = "INSERT INTO articles_draft VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $query = "SELECT MAX(id) FROM `articles_draft`";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else if($table == "articles"){
            $query = "INSERT INTO articles_draft VALUES (null, '$this->title', '$this->desc', '$this->thumb_img', '$this->layout');";
                $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            $query = "SELECT MAX(id) FROM `articles_draft`;";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $query = "UPDATE $table SET title='$this->title', description='$this->desc', thumbnail='$this->thumb_img', thumbnail_style='$this->layout' WHERE id='$id'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $id;
        }
    }       
}