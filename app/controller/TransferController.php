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
}
