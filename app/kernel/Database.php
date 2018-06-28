<?php

require_once(ROOT.'\app\config.php');

class Database  {
    
    private static $db = null;
    
    /**
     * Créer une connexion à la base de données.
     * @return un objet PDO
     */     
    public static function getInstance() {
        if (self :: $db == null){
            try {
                $db = new PDO('mysql:host='.constant("server_name").';charset=UTF8;dbname='.constant("db_name"), constant("user"), constant("password"));
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
            }
            catch (PDOException $e) {
                echo 'Echec de la connexion : ' . $e->getMessage();
            }
        }
        return $db;            
    }
}

$db = Database::getInstance();