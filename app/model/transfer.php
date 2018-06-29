<?php

Class Transfer extends Model{

    public static function fileUpload() {

        if(isset($_POST["submit"])){
            $msg = array();
            $error = 0;
            $emailExpediteur = $_POST['emailExpediteur'];
            $emailDestinataire = $_POST['emailDestinataire'];
            print_r($emailExpediteur);
            print_r($emailDestinataire);

            //Vérif si checkbox est true ou false
            $checkbox = isset($_POST['emailCopie']) ? 1 : 0;

            //Verif d'erreur lors d'upload
            if(!empty($_FILES)){
                if(isset($_FILES['fileUpload']['error'])){
                    switch($_FILES['fileUpload']['error']){ //ref : http://php.net/manual/fr/features.file-upload.errors.php
                        case 1:
                            $msg['msg'] = "Votre fichier ne doit pas dépasser 12Mo";
                            $msg['type'] = "error";
                            $error++;
                            break;
                        case 2:
                            $msg['msg'] = "Votre fichier ne doit pas dépasser 12Mo";
                            $msg['type'] = "error";
                            $error++;
                            break;
                        case 3:
                            $msg['msg'] = "Une erreur est survenue lors du téléchargement";
                            $msg['type'] = "error";
                            $error++;
                            break;
                        case 4:
                            $msg['msg'] = "Veuillez sélectionner un fichier";
                            $msg['type'] = "error";
                            $error++;
                            break;
                    }
                    if($error == 0){
                        $ext = substr($_FILES['fileUpload']['name'], strrpos($_FILES['fileUpload']['name'], '.') + 1);
                        $nameGenerated = substr(md5($_FILES['fileUpload']['name']), 0, 5).microtime().'.'.$ext;
        
                        $path = ROOT . '\app\assets\file_uploaded/'. $nameGenerated;
                        $path = $path . basename( $_FILES['fileUpload']['name']);
                        echo "<p>Votre fichier a bien été uploadé !</p>";
                    } else {

                        echo $msg['msg'];
                    }
                }
            } else {
                echo "<p>Veuillez sélectionner un fichier</p>";
            }
            print_r($emailExpediteur);
            
            //Vérif si l'email Expéditeur est bon
            if(empty($emailExpediteur)){
                    echo "<p>L'email de votre destinaire n'est pas valide</p>";
            }
    
            //Vérif si l'email Destinaire est bon
            if(empty($emailDestinataire)){
                    echo "<p>Veuillez entrer le mail de votre destinataire";
            }

            $db = Database::getInstance();
            $sql = 'INSERT INTO `transfer`(email_expediteur, email_destinataire, email_copie) VALUES ('. $emailExpediteur . ',' . $emailDestinataire . ',' . $checkbox . ')';
            $stmt = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $stmt;
           
        } 
      
    }  
}