<?php

class TransferController extends Controller
{
    public function index(){
        unset($_SESSION['messageError']);
        $type = '';

        if(isset($_FILES['fileUpload'])){
            $fileUpload = Transfer::fileUpload();
            $type = $fileUpload['type'];
        }        
        
        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
            'type' => $type
        ));
    }
}


