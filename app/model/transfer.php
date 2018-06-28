<?php

Class Transfer extends Model{

    public static function displayLastGeneratedMeme() {
        $db = Database::getInstance();
/*         $sql = 'SELECT mem_pseudo, mem_image, mem_createdAt FROM `meme` ORDER BY mem_createdAt ASC LIMIT 5'; */
        $stmt = $db->prepare('SELECT mem_pseudo, mem_image, mem_createdAt FROM `meme` ORDER BY mem_createdAt ASC LIMIT 5');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

   /*  public static function insertImg() {
        $db = Database::getInstance(); */
       /*  $stmt = $db->prepare('INSERT INTO `meme`(mem_pseudo, mem_image, mem_createdAt) VALUES ( '$_POST['pseudo']' , '$_POST['JECPAKOIMAITRE']' , 'NOW()') '));
        */ /* $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    } */

    /* public static function displayGalleryOfMeme() {
        $db = Database::getInstance();
        $sql = 'SELECT * FROM `meme` ORDER BY mem_createdAt ASC;';
        $stmt = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $stmt;
    } */
    
}