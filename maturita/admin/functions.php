<?php
session_start();

if(isset($_SESSION["loginlock"]) && $_SESSION["loginlock"] == true){
    $time = strtotime(date("H:i:s"));
    $lockTime = $_SESSION["timerTime"];
    if($lockTime - $time <= 0){
        unset($_SESSION["timerTime"], $_SESSION["tries"]);
        $_SESSION["loginlock"] = false;
    }
}

if(isset($_SESSION["loginlock"]) && $_SESSION["loginlock"] == false){
    if (isset($_POST["login"])){
        
        $name = $_POST["username"];
        $pass = $_POST["password"];
    
        $name = trim($name);
        $pass = trim($pass);
        $name = stripcslashes($name);
        $pass = stripcslashes($pass);
        
        if(emptyInput($name, $pass) !== false){
            header("location: index.php?error=empty");
            exit();
        }

        loginUser($name, $pass);
    }    
    else {
        header("Location: index.php");
        exit();
    }
}
else{
    header("location: index.php");
    exit();
}

function emptyInput($name, $pass){
    if(empty($name) || empty($pass)){
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function loginUser($name, $pass){
    // if($name != USERNAME){
    //     header("location: index.php?error=login");
    //     exit();
    // }

    // $checkPassword = password_verify($pass, PASSWORD);
    $checkPassword = true;

    if($checkPassword === false){
        header("location: index.php?error=login");
        exit();
    }
    else if($checkPassword === true){
        $_SESSION["logged"] = true;
        header("location: ../editor/posts.php");
        exit();
    }
}