<?php
function dbConnect($db){
    return new PDO("sqlite:$db.db");
}