<?php

class TransferController extends Controller
{
    public function index(){
        $message = ''; $type = ''; $id = ''; $path = '';    

        if(isset($_FILES['fileUpload'])){
            $msg = Transfer::fileUpload();
             
            $message = $msg['msg'];
            $type = $msg['type'];
            $path = $msg['urlfile'];

            try{
                if($msg['type'] == 'success'){
                    $id = $msg['url'];
                    $linkFile = Transfer::linkFile($id);
                    $path = $path . $id ;
/*                     print_r($path);
 */                }
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
        $id = $this->route["params"]["id"];

        $template = $this->twig->loadTemplate('/Page/download.html.twig');
        echo $template->render(array(
            'id'        => $path
        ));
    
    }
}




