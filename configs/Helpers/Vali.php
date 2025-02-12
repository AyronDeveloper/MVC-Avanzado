<?php
namespace configs\Helpers;

class Vali{
    private static $name="";
    private static $var;
    private static $result;
    private static $message;
    private static $count=0;


    private function voidMessage($msg){
        $msg=trim($msg);

        return empty($msg)?true:false;
    }


    public static function required($name,$value, $error=null, $message=null){

        self::$name="";
        self::$var="";
        self::$result=true;
        self::$message="";

        $value=trim($value);
        self::$name=$name;

        if(!empty($value)){
            self::$var=$value;
            self::$message=self::voidMessage($message)?"valited":$message;
        }else{
            self::$result=false;
            self::$message=self::voidMessage($error)?"required error":$error;
        }

        $_SESSION["formValidation"][self::$name]=self::$var;
        $_SESSION["formValidation"][self::$name."Result"]=self::$result;
        $_SESSION["formValidation"][self::$name."Men"]=self::$message;

        return new self;
    }

    public static function isString($error=null,$message=null){

        if(self::$result){
            $regex='/^[A-Za-zñÑáéíóúÁÉÍÓÚ]+$/';
            if(preg_match($regex,self::$var)){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isString error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isNumber($error=null,$message=null){

        if(self::$result){
            if(is_numeric(self::$var)){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isNumber error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isInteger($error=null,$message=null){
        
        if(self::$result){  
            if(filter_var(self::$var,FILTER_VALIDATE_INT)!==false){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isInteger error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isFloat($error=null,$message=null){
        
        
        if(self::$result){
            if(is_numeric(self::$var) && strpos(self::$var,".")!==false){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isFloat error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function lenMin($min,$error=null,$message=null){
        
        if(self::$result){
            if(strlen(self::$var)>=$min){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"lenMin error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function lenMax($max,$error=null,$message=null){
        
        if(self::$result){
            if(strlen(self::$var)<=$max){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"lenMax error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function differentTo($different,$error=null,$message=null){
        
        if(self::$result){
            $different=trim($different);
            if($different!=self::$var){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"differentTo error":$error;
            }

            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function equalTo($equal,$error=null,$message=null){
        
        if(self::$result){
            $equal=trim($equal);
            if($equal==self::$var){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"equalTo error":$error;
            }
            
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isEmail($error=null,$message=null){
        
        if(self::$result){
            if(filter_var(self::$var,FILTER_VALIDATE_EMAIL)){
                self::$message=self::voidMessage($message)?"valited":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isEmail error":$error;
            }
            
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function validateDate($error=null,$message=null){
        
        if(self::$result){

            function dateVerify($fecha){
                $resultDate=false;

                $formatDate="/^\d{4}-\d{2}-\d{2}$/";

                if(preg_match($formatDate,$fecha)){
                    $dateArray=explode("-",$fecha);
        
                    $anio=intval($dateArray[0]);
                    $mes=intval($dateArray[1]);
                    $dia=intval($dateArray[2]);

                    if(checkdate($mes, $dia, $anio)){
                        $resultDate=true;
                    }
                }

                return $resultDate;
            }

            if(dateVerify(self::$var)){
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"validateDate Error":$error;
            }
            
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isURL($error=null,$message=null){
        
        if(self::$result){
            if(filter_var(self::$var,FILTER_VALIDATE_URL)!==false){
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isURL Error":$error;
            }
        }

        return new self;
    }

    public static function notUse($prohi,$error=null,$message=null){
        
        if(self::$result){
            $patron="/[";

            foreach($prohi as $caracter){
                if(strpos($caracter,"-")!==false){
                    $patron.=$caracter;
                }else{
                    $patron.=preg_quote($caracter,"/");
                }
            }

            $patron.="]/";

            if(!preg_match($patron,self::$var)){
                self::$result=true;
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$result=false;
                self::$var="";
                self::$message=self::voidMessage($error)?"notUse Error":$error;
            }
                
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isColor($error=null,$message=null){
        
        if(self::$result){
            $hexColor="/^#[0-9A-Fa-f]{6}$/";
            if(preg_match($hexColor,self::$var)){
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"isColor error":$error;
            }
            
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }

        return new self;
    }

    public static function isBoolean($name,$bool,$error=null,$message=null){
        self::$name="";
        self::$var="";
        self::$result=true;
        self::$message="";

        self::$name=$name;

        if($bool=="true" || $bool=="false" || $bool=="1" || $bool=="0"){
            self::$var=$bool;
            if(filter_var(self::$var,FILTER_VALIDATE_BOOLEAN)!==null){
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$result=false;
                self::$message=self::voidMessage($error)?"isBoolean error":$error;
            }
        }else{
            self::$result=false;
            self::$message=self::voidMessage($error)?"isBoolean error":$error;
        }

        $_SESSION["formValidation"][self::$name]=self::$var;
        $_SESSION["formValidation"][self::$name."Result"]=self::$result;
        $_SESSION["formValidation"][self::$name."Men"]=self::$message;

        return new self;
    }

    public static function isArray($name,$array,$error=null,$message=null){
        self::$name="";
        self::$var="";
        self::$result=true;
        self::$message="";

        self::$name=$name;

        if(is_array($array)){
            self::$var=$array;
            self::$message=self::voidMessage($message)?"validated":$message;
        }else{
            self::$result=false;
            self::$message=self::voidMessage($error)?"isArray error":$error;
        }
        
        $_SESSION["formValidation"][self::$name]=self::$var;
        $_SESSION["formValidation"][self::$name."Result"]=self::$result;
        $_SESSION["formValidation"][self::$name."Men"]=self::$message;

        return new self;
    }

    public static function uploadFile($name,$file,$error=null,$message=null){
        self::$name="";
        self::$var="";
        self::$result=true;
        self::$message="";


        self::$name=$name;

        if($file["error"]==0){
            self::$var=$file;
            self::$message=self::voidMessage($message)?"validated":$message;
        }else{
            self::$result=false;
            self::$message=self::voidMessage($error)?"uploadFile Error":$error;
        }
            
        $_SESSION["formValidation"][self::$name]=self::$var;
        $_SESSION["formValidation"][self::$name."Result"]=self::$result;
        $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        return new self;
    }

    public static function sizeFile($limit,$size,$UA,$error=null,$message=null){
        if(self::$result==true){
            $sizeResult=false;

            $byte=1024;

            if($UA=="KB"){
                $sizeFile=self::$var["size"]/$byte;
            }elseif($UA=="byte"){
                $sizeFile=self::$var["size"];
            }else{
                self::$result=false;
                self::$message="Error UA";
                
                
                $_SESSION["formValidation"][self::$name]="";
                $_SESSION["formValidation"][self::$name."Result"]=self::$result;
                $_SESSION["formValidation"][self::$name."Men"]=self::$message;
                
                return new self;
            }


            if($limit=="min"){
                if($size<=$sizeFile){
                    $sizeResult=true;
                }
            }elseif($limit=="max"){
                if($size>=$sizeFile){
                    $sizeResult=true;
                }
            }


            if($sizeResult){
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"sizeFile Error":$error;
            }
                
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }
        return new self;
    }

    public static function typeFile($mime,$error=null,$message=null){
        if(self::$result==true){
            $typeResult=false;

            if(in_array(self::$var["type"],$mime)){
                $typeResult=true;
            }

            if($typeResult){
                self::$message=self::voidMessage($message)?"validated":$message;
            }else{
                self::$var="";
                self::$result=false;
                self::$message=self::voidMessage($error)?"typeFile Error":$error;
            }
                
            $_SESSION["formValidation"][self::$name]=self::$var;
            $_SESSION["formValidation"][self::$name."Result"]=self::$result;
            $_SESSION["formValidation"][self::$name."Men"]=self::$message;
        }
        return new self;
    }
    
    
    
    
    //create
    public static function create($name,$result,$valor=null,$message=null){
        $_SESSION["formValidation"][$name]=$valor;
        $_SESSION["formValidation"][$name."Result"]=$result;
        
        if($message!=null){
            $_SESSION["formValidation"][$name."Men"]=$message;
        }
    }



    public static function results($value=null){
        if(self::$result==false){
            self::$count++;
        }
        if($value=="var"){
            return self::$var;
        }
        elseif($value=="result"){
            return self::$result;
        }
        elseif($value=="message"){
            return self::$message;
        }
        else{
            return array("var"=>self::$var,"result"=>self::$result,"message"=>self::$message);
        }
    }

    public static function errors(){
        $result=false;

        if(self::$count==0) $result=true;

        return $result;
    }


    //clear
    public static function clear(){
        unset($_SESSION["formValidation"]);
    }
    //clearSelect
    public static function clearSelect($name){
        unset($_SESSION["formValidation"][$name]);
        unset($_SESSION["formValidation"][$name."Men"]);
        unset($_SESSION["formValidation"][$name."Result"]);
    }
    //clearExcep
    public static function clearExcep($name){
        foreach($_SESSION["formValidation"] as $session=>$valor){
            if($session!==$name){
                unset($_SESSION["formValidation"][$session]);
                unset($_SESSION["formValidation"][$session."Men"]);
                unset($_SESSION["formValidation"][$name."Result"]);
            }
        }
    }

    public static function success($name){
        if(isset($_SESSION["formValidation"][$name."Result"]) && $_SESSION["formValidation"][$name."Result"]==true){
            return true;
        }
    }
    
    public static function failed($name){
        if(isset($_SESSION["formValidation"][$name."Result"]) && $_SESSION["formValidation"][$name."Result"]==false){
            return true;
        }
    }
    //value
    public static function value($name,$aditional=null){
        if(isset($_SESSION["formValidation"][$name])){
            return $_SESSION["formValidation"][$name];
        }

        if(!empty($aditional)){
            return $aditional;
        }

    }

    public static function valueArray($name,$indice){
        if(isset($_SESSION["formValidation"][$name]) && $_SESSION["formValidation"][$name]!=false){
            return $_SESSION["formValidation"][$name][$indice];
        }
    }

    public static function showMessage($name){
        if(isset($_SESSION["formValidation"][$name."Men"])){
            return $_SESSION["formValidation"][$name."Men"];
        }
    }
}
?>