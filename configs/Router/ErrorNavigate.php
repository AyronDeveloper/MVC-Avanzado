<?php
namespace configs\Router;

use configs\Router\Navigate;
use controllers\errorController;

class ErrorNavigate extends Navigate{
    
    public static function error($redirection=null){
        $status=self::getStatusGlobal("status");

        if($status){
            if(self::isApi()){
                errorController::api();
            }else{
                if(!empty($redirection)){
                    header("Location: $redirection",true,301);
                }else{
                    errorController::web();
                }
                
            }
        }

    }
}
?>