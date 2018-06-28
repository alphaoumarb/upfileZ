<?php

class TransferController extends Controller
{
    public function index()
    {
        
        if(isset($_POST["submit"])){
            $msg = array();
            $error = 0;
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
                        echo "Votre fichier a bien été uploadé";
                    } else {
                        echo "Échec de l'upload.";
                    }
            
                }
            }

            
        }       
        
        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
        ));
    }
}
