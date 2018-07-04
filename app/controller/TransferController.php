<?php

class TransferController extends Controller
{
    public function index(){
        $message = ''; $type = '';        

        if(isset($_FILES['fileUpload'])){
            $msg = Transfer::fileUpload();
            $message = $msg['msg'];
            $type = $msg['type'];

            /* if(isset($_POST['submit'])){
                try{
                    if($msg['type'] == 'success'){
                        $id = $msg['url'];
                        $linkFile = Transfer::linkFile($id);
                    }
                }
                catch(\Exception $e){
                    echo $e->getMessage();
                    
                }
            
            
            } */  
                
        } 

        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
            'message'   => $message,
            'type'      => $type
        ));
    }

    
}




