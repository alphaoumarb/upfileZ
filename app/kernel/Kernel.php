<?php

class Kernel {

    public static function autoload($class){
        if(file_exists(ROOT.'/app/kernel/'.$class.'.php')){
            require_once ROOT.'/app/kernel/'.$class.'.php';
        } else if(file_exists(ROOT.'/app/controller/'.$class.'.php')){
            require_once ROOT.'/app/controller/'.$class.'.php';
        } else if(file_exists(ROOT.'/app/model/'.$class.'.php')){
            require_once ROOT.'/app/model/'.$class.'.php';
        }

        //echo $class.'<br>';
    }

    public static function run(){
        // Autoload
        spl_autoload_register(array('Kernel', 'autoload'));

        // Analyser la requête
        $request = isset($_GET['request']) ? $_GET['request'] : ''; //?request dans htaccess
        $route = Router::analyse($request);

        // Instancier le contrôleur et executer l'action
        $class = $route['controller']."Controller";

        if(class_exists($class)){
            $controller = new $class($route);
            $method = array($controller, $route['action']);

            if(is_callable($method)){
                call_user_func($method);
            }
        }

        // Gestion des erreurs
    }

}