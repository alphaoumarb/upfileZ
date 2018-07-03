<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
                            $error++;
                            break;
                        case 2:
                            $msg['msg'] = "Votre fichier ne doit pas dépasser 12Mo";
                            $msg['type'] = 'error';
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
                    $fileUrl = "app/assets/file_uploaded/" . basename($_FILES["fileUpload"]["name"]);
                   /*  print_r($msg); */
                    $target_dir = ROOT . "/app/assets/file_uploaded/";
                    $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                    // Check la taille du fichier
                    if ($_FILES["fileUpload"]["size"] > 2147483648) {
/*                         echo "Votre fichier est trop grand.";
*/                      $msg['msg'] .= "Votre fichier est trop grand.";
                        $msg['type'] = "error";
                        $uploadOk = 0;
                        
                    }

                    // Check si il y a une erreur
                    if ($uploadOk == 0) {
/*                         echo "Désolé, votre fichier n'a pas pu être uploadé.";
*/                      $msg['msg'] = "Désolé, votre fichier n'a pas pu être uploadé.";
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

            if($error == 0){

            $db = Database::getInstance();
            $sql = "INSERT INTO `transfer`(email_expediteur, email_destinataire, email_copie, url_file) VALUES ('$emailDestinataire', '".$emailExpediteur."', '".$checkbox."', '$fileUrl')";
            $stmt = $db->query($sql);
            
/*             Self::sendMailPHP(); */
            $msg['msg'] = 'Votre fichier a bien été envoyé !';
            $msg['type'] = "success";
            }

            $_SESSION['messageError'] = $msg['msg'];
            
        } else {
            $msg['msg'] = "Une erreur s'est produite lors de l'envoie"; 
            $msg['type'] = 'error';
            /* if(empty($emailExpediteur)){
                $msg['msg'] = "Veuillez rentrer votre adresse mail";
                $msg['type'] = "error";
            }
            if(empty($_POST['emailDestinataire'])){
                $msg['msg'] = "Veuillez rentrer";
                $msg['type'] = "error";
            }
            if(empty($_FILES)){
            $msg['msg'] = 'Heyyyy !';
            // Message d'erreur si pas de $_POST */
        }

        return $msg;
    }

    public static function sendMailPHP(){
        
        $mail = new PHPMailer(true);  
                                    // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = false;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'b2pr2018b@outlook.fr';                 // SMTP username
            $mail->Password = 'azertY1234!';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('b2pr2018b@outlook.fr', 'Mailer');
            /* $mail->addAddress('joe@example.net', 'Joe User'); */     // Add a recipient
            $mail->addAddress('b2pr2018b@outlook.fr');               // Name is optional
/*             $mail->addReplyTo('info@example.com', 'Information');
*/          /* $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com'); */

            //Attachments
            /* $mail->addAttachment('/var/tmp/file.tar.gz'); */         // Add attachments
            /* $mail->addAttachment('/tmp/image.jpg', 'new.jpg');     */// Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = "<html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <meta http-equiv='X-UA-Compatible' content='ie=edge'>
               
                <title>UpfileZ / Vous avez reçu un fichier</title>
            </head>
            <body style='background-color: #F8F8F8; font-family: Arial, Helvetica, sans-serif;'>
                <div style='display: flex; flex-direction: column; justify-content: center; height: 500px;'>
                    <section style='margin:auto; text-align: center; width: 300px;'>
                        <div style='background-color: #A243E8; height: 100px; display: flex; flex-direction: column;'>
                            <img style='margin:auto;' src='logo_upfilez.png'>
                        </div>
                        <div style='background-color: #FFF; height: 300px; display: flex; flex-direction: column; justify-content: space-evenly;'>
                            <article>
                                <p style='color: #444;'>Vous avez reçu un fichier de la part de: <p>
                                <span style='color: #A243E8; font-weight: bold;'>b2pr2018@outlook.fr</span> 
                            </article>
                            <article>
                                <a href='#' style='text-decoration: none;'><p style='width: 100px; background-color: #A243E8; color: #FFF; padding: 10px 15px 10px; margin:auto; font-weight: bold;'>Retrouvez le ici</p></a>
                            </article>
                            <article>
                                <p style='color: #444;'>ou à l'adresse suivante:</p>
                                <p style='background-color: #ffd8b9; color: #444; width: 300px; margin:auto; padding: 10px 15px 10px; font-weight: bold;'>URL</p>
                            </article>
                        </div>
                    </section>
                </div> 
            </body>
            </html>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
            
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}