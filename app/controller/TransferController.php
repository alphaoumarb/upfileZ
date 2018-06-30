<?php

class TransferController extends Controller
{
    public function index()
    {
        $fileUpload = Transfer::fileUpload();
        
        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
        ));    
    }

    public function mail()
    {
        $sendSwiftMail = Transfer::sendMailPHP();
        
        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
        ));   
    }
}


