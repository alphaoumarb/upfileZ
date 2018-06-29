<?php

Class Transfer extends Model{

    public static function fileUpload(){

        if(isset($_POST["submit"]) && isset($_POST["emailExpediteur"]) && isset($_POST["emailDestinataire"])){

            $msg = array();
            $error = 0;
            $emailExpediteur = $_POST['emailExpediteur'];
            $emailDestinataire = $_POST['emailDestinataire'];

            //Vérif si checkbox est true ou false
            $checkbox = isset($_POST['emailCopie']) ? 1 : 0;

            if(!empty($_FILES)){

                //Verif d'erreur lors d'upload

                $ext = substr($_FILES['fileUpload']['name'], strrpos($_FILES['fileUpload']['name'], '.') + 1);
                $nameGenerated = substr(md5($_FILES['fileUpload']['name']), 0, 5).microtime().'.'.$ext;

                $path = ROOT . '\app\assets\file_uploaded/'. $nameGenerated;
                $path = $path . basename( $_FILES['fileUpload']['name']);

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
                }

            } else {
                $error++;
                print_r($error);
                echo "<p>Veuillez sélectionner un fichier</p>";
            }
            
            //Vérif si l'email Expéditeur est bon
            $pattern = "/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD";
            if(isset($_POST['emailExpediteur'])){
                $emailExpediteur = htmlspecialchars($emailExpediteur);
                if(preg_match($pattern, $emailExpediteur)){
                    echo 'Mail ok Expediteur';//requete pour envoyer mail et enregistrer mail dans bdd//
                } else {
                    echo "<p>L'email de votre destinaire n'est pas valide</p>";
                    $error++;
                }
            }else{
                echo "<p>Votre email n'est pas valide</p>";
                $error++;
            }
    
            //Vérif si l'email Destinaire est bon
            if(isset($_POST['emailDestinataire'])){
                $emailDestinataire = htmlspecialchars($emailDestinataire);
                if(preg_match($pattern, $emailDestinataire)){
                    echo 'Mail ok Destinataire';//requete pour envoyer mail et enregistrer mail dans bdd//
                }else{
                    echo "<p>L'email de votre destinaire n'est pas valide</p>";
                    $error++;
                }
            } else {
                echo "<p>Veuillez entrer le mail de votre destinataire";
                $error++;
            }

            if($error == 0){

            $db = Database::getInstance();
            $sql = 'INSERT INTO `transfer`(email_expediteur, email_destinataire, email_copie) VALUES ('. $emailExpediteur . ',' . $emailDestinataire . ',' . $checkbox . ')';
            $stmt = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $stmt;
            echo 'Votre fichier a bien été envoyé.';


            }else{
                echo "Désolé, il y a eu un problème lors de l'envoi de votre fichier.";
                return false;
            }
        }
      
    }  
}