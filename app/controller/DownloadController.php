<?php

class DownloadController extends Controller
{
    public function download(){
        
    

        $template = $this->twig->loadTemplate('/Page/download.html.twig');
        echo $template->render(array(

        ));
    }
}




