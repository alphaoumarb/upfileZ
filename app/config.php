<?php 
define("server_name", "localhost");
define("db_name", "wetransferlike");
define("user", "root");
define("password", "");
/* 
try{
    $db = new PDO('mysql:host='.constant("server_name").';charset=UTF8;dbname='.constant("db_name"), constant("user"), constant("password"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die("Error :".$e->getMessage());
} */