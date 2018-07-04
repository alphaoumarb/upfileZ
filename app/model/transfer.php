<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

Class Transfer extends Model{

    public static function fileUpload(){
        

        if(isset($_POST['submit'])){
            
            $msg = array();
            $error = 0;

            $emailExpediteur = $_POST['emailExpediteur'];
            $emailDestinataire = $_POST['emailDestinataire'];
            $checkbox = isset($_POST['emailCopie']) ? 1 : 0; //Vérif si checkbox est true ou false

            if(!empty($_FILES)){

                if(isset($_FILES['fileUpload']['error'])){
                    switch($_FILES['fileUpload']['error']){ //ref : http://php.net/manual/fr/features.file-upload.errors.php
                        case 1:
                            $msg['msg'] = "Votre fichier ne doit pas dépasser 12Mo";
                            $msg['type'] = 'error';
                            $msg['url'] = '';
                            $error++;
                            break;
                        case 2:
                            $msg['msg'] = "Votre fichier ne doit pas dépasser 12Mo";
                            $msg['type'] = 'error';
                            $msg['url'] = '';
                            $error++;
                            break;
                        case 3:
                            $msg['msg'] = "Une erreur est survenue lors du téléchargement";
                            $msg['type'] = "error";
                            $msg['url'] = '';
                            $error++;
                            break;
                        case 4:
                            $msg['msg'] = "Veuillez sélectionner un fichier";
                            $msg['type'] = "error";
                            $msg['url'] = '';
                            $error++;
                            break;
                    }
                    $fileUrl = basename($_FILES["fileUpload"]["name"]);
                    $target_dir = ROOT . "/app/assets/file_uploaded/";
                    $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);

                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    
                    // Check la taille du fichier
                    if ($_FILES["fileUpload"]["size"] > 2147483648) {
                        $msg['msg'] = "Votre fichier est trop grand.";
                        $msg['type'] = "error";
                        $uploadOk = 0;
                    }

                    // Check si il y a une erreur
                    if ($uploadOk == 0) {
                        $msg['msg'] = "Désolé, votre fichier n'a pas pu être uploadé.";
                        $msg['type'] = "error";
                        
                    // Si Ok on upload
                    } else {
                        move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file);
                    }
                }

            }
                
            //Vérif mail Expéditeur            
            if (empty($emailExpediteur)){
                $msg['msg'] .= "Veuillez rentrer votre adresse mail";
                $msg['type'] = "error";
                $error++;
            } else if(!is_a_mail($emailExpediteur)){
                $msg['msg'] .= "Votre email n'est pas valide";
                $msg['type'] = "error";
                $error++;
            }

            //Vérif mail Destinataire
            if (empty($emailDestinataire)){
                $msg['msg'] .= "Veuillez rentrer l'adresse mail de votre destinataire";
                $msg['type'] = "error";
                $error++;
            } else if(!is_a_mail($emailDestinataire)){
                $msg['msg'] .= "L'adresse mail de votre destinataire n'est pas valide";
                $msg['type'] = "error";
                $error++;
            }

            //All good
            if($error == 0){

            $db = Database::getInstance();
            $sth = $db->prepare("INSERT INTO `transfer`(
                                    email_expediteur, 
                                    email_destinataire, 
                                    email_copie, 
                                    url_file) VALUES (
                                    :email_expediteur, 
                                    :email_destinataire, 
                                    :email_copie, 
                                    :url_file)"
                                );

            $sth->bindValue(':email_expediteur', $emailExpediteur, PDO::PARAM_STR);
            $sth->bindValue(':email_destinataire', $emailDestinataire, PDO::PARAM_STR);
            $sth->bindValue(':email_copie', $checkbox, PDO::PARAM_BOOL);
            $sth->bindValue(':url_file', $fileUrl, PDO::PARAM_STR);
            $sth->execute();
            $id = $db->lastInsertId();

            $msg['msg'] = 'Votre fichier a bien été envoyé !';
            $msg['type'] = "success";
            $msg['url'] = $id;
            $msg['urlfile'] = $target_dir;
            $msg['file'] = $target_file;
            Transfer::sendMailPHP($emailExpediteur, $emailDestinataire, $checkbox, $msg, $id);

            }
            
        } else {
            $msg['msg'] = "Une erreur s'est produite lors de l'envoi"; 
            $msg['type'] = 'error';
        }
        return $msg;
    }

    public static function sendMailPHP($emailExpediteur, $emailDestinataire, $checkbox, $msg, $id){
        
        $mail = new PHPMailer(true);  
                                    // Passing `true` enables exceptions
        try {
            //Server settings
            $linkdownload = "http://localhost/upfilez/download/";
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = false;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'b2pr2018c@outlook.fr';                 // SMTP username
            $mail->Password = 'azertY1234!';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('b2pr2018c@outlook.fr', 'Mailer');
            $mail->addAddress($emailDestinataire);               // Name is optional
/*             $mail->addReplyTo('info@example.com', 'Information');*/
            if($checkbox == 1){
                $mail->addCC('ludovic.r@codeur.online');
            }

            //Attachments
            $mail->addAttachment('file:///C:/wamp64/www/upfilez/logo_upfilez.png', 'logo_upfilez');       // Add attachments

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Vous avez reçu un fichier de la part de ' .$emailExpediteur;
            $mail->Body    = "<html lang='fr'>
            <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            
            <title>UpfileZ / Vous avez reçu un fichier</title>
            </head>
            
            
            <table cellspacing='0' cellpadding='10' border='0' width='100%'>
            
            <tr>
            <td><div style='background-color:
            #A243E8
            ; height: 100px; display: flex; flex-direction: column;'>
            <img style='margin:auto;' src='cid:logo_upfilez' alt='logo_upfilez'>
            </div>
            </td>
            </tr>
            
            <tr>
            <td align='center'><p style='font-family:helvetica; font-size:25px; color:#A243E8'>$emailExpediteur</p></td>
            </tr>
            
            <tr>
            <td align='center'><p style='font-family:helvetica; font-size:25px;'>vous a envoyé un fichier</p></td>
            </tr>
            
            <tr>
            <td align='center'><a href=".$linkdownload . $id . "><button style='height:50px; border:none; color:#fff; padding: 10px 15px 10px;background-color:
            #A243E8'>Récupérez votre fichier</button></a></td>
            </tr>
            
            </table>
            
            </html>";
            $mail->AltBody = 'Vous avez reçu un fichier de la part de ' .$emailExpediteur.'.Récupérez votre fichier à l\'adresse suivante :'.$linkdownload . $id .'';

            $mail->send();
            $msg['msg'] = "Votre fichier a été envoyé !";
            
        } catch (Exception $e) {
            $msg['msg'] = "Le message n'a pas pu être envoyé. Error: ". $mail->ErrorInfo;
        }
    }

    public static function linkFile($id){

        $db = Database::getInstance();
        $sql = "SELECT * FROM `transfer` WHERE id = :id";
        $sth = $db->prepare($sql);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        
        return $sth->fetch();

        $msg['url'] = $id;

    }
}