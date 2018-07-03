<?php

class TransferController extends Controller
{
    public function index(){
        unset($_SESSION['messageError']);
        $msg['url'] = '';
        $msg['type'] = '';
        $msg['msg'] = '';

        

        if(isset($_FILES['fileUpload'])){
            $msg = Transfer::fileUpload();

            if(isset($_POST['submit'])){
                try{
                    if($msg['type'] == 'success'){
                        $id = $msg['url'];
                        $linkFile = Transfer::linkFile($id);
                }
            }
                catch(\Exception $e){
                    echo $e->getMessage();
                    die();
                }
            
            }  
                
        }


            
          
        
        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
            'msg' => $msg,

        ));
    }
}




