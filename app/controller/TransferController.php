<?php

class TransferController extends Controller
{
    public function index(){
        $message = ''; $type = ''; $id = ''; $path=''; $file='';

        if(isset($_FILES['fileUpload'])){
            $msg = Transfer::fileUpload();
             
            $message = $msg['msg'];
            $type = $msg['type'];
            $path = $msg['urlfile'];
            $file = $msg['file'];

            try{
                if($msg['type'] == 'success'){
                    $id = $msg['url'];
                    $linkFile = Transfer::linkFile($id);
                    $path = $msg['url'];
                }
            }
            catch(\Exception $e){
                echo $e->getMessage(); 
            }        
        } 

        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
            'id'        => $path,
            'message'   => $message,
            'type'      => $type
        ));
    }

    public function download(){
        $id = $this->route['params']['id'];

        $linkFile = Transfer::linkFile($id);  
        
        $template = $this->twig->loadTemplate('/page/download.html.twig');
        echo $template->render(array(
            'file'        =>  $linkFile

        ));
    
    }
}




