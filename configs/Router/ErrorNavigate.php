<?php
namespace configs\Router;

use configs\Router\Navigate;
use controllers\errorController;

class ErrorNavigate extends Navigate{
    
    public static function error($redirection=null){
        if(self::getStatusGlobal()){
            if(!empty($redirection)){
                header("Location: $redirection",true,301);
            }else{
                errorController::index();
            }
        }
    }
}
?>