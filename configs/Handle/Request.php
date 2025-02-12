<?php
namespace configs\Handle;

class Request{
    private static $parametersGet;
    private static $parametersPost;
    private static $parametersPut;
    private static $parametersDelete;
    private static $parametersFiles;


    public static function get($param=null){

        if(count($_GET)>0){
            self::$parametersGet=$_GET;
    
            if(empty($param)){
                return self::$parametersGet;
            }else{
                return self::$parametersGet[$param];
            }
        }

    }

    public static function post($param=null){
        
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="POST"){

            if(!array_key_exists("PUT",$_POST) && !array_key_exists("DELETE",$_POST)){
                self::$parametersPost=$_POST;
    
                if(empty($param)){
                    return self::$parametersPost;
                }else{
                    return self::$parametersPost[$param];
                }
            }
        }

    }

    public static function put($param=null){
        $request_method=$_SERVER["REQUEST_METHOD"];
        
        if($request_method=="PUT"){

            $_PUT=json_decode(file_get_contents("php://input"),true);
            self::$parametersPut=$_PUT;
    
            if(empty($param)){
                return self::$parametersPut;
            }else{
                return self::$parametersPut[$param];
            }

        }elseif($request_method=="POST" && isset($_POST["PUT"]) && $_POST["PUT"]=="_PUT"){

            $put=$_POST;
            unset($put["PUT"]);

            self::$parametersPut=$put;
    
            if(empty($param)){
                return self::$parametersPut;
            }else{
                return self::$parametersPut[$param];
            }

        }
        
    }

    public static function delete($param=null){
        $request_method=$_SERVER["REQUEST_METHOD"];
        
        if($request_method=="DELETE"){
            $_DELETE=json_decode(file_get_contents("php://input"),true);
            self::$parametersDelete=$_DELETE;
    
            if(empty($param)){
                return self::$parametersDelete;
            }else{
                return self::$parametersDelete[$param];
            }

        }elseif($request_method=="POST" && isset($_POST["DELETE"]) && $_POST["DELETE"]=="_DELETE"){

            $delete=$_POST;
            unset($delete["DELETE"]);

            self::$parametersDelete=$delete;

            if(empty($param)){
                return self::$parametersDelete;
            }else{
                return self::$parametersDelete[$param];
            }

        }
    }

    public static function files($param=null,$arg=null){

        if(count($_FILES)>0){
            self::$parametersFiles=$_FILES;

            if(empty($param)){
                return self::$parametersFiles;
            }else{
                if(empty($arg)){
                    return self::$parametersFiles[$param];
                }else{
                    return self::$parametersFiles[$param][$arg];
                }
            }
        }

    }


    public static function uploadFile($file,$direction){
        if($file["error"]==0){
            $name=$file["name"];
            $tmp=$file["tmp_name"];

            if(!file_exists($direction)){
                if(!mkdir($direction,0777,true)){
                    return false;
                }
            }

            if(substr($direction,-1)!=="/"){
                $direction=$direction."/";
            }

            $result_save=false;
            if(move_uploaded_file($tmp,$direction.$name)){
                $result_save=true;
            }
            
            return $result_save;
        }

        return false;
    }


    public static function method($method){

        $method=strtoupper($method);

        $input="";

        if($method=="PUT"){
            $input="<input type='hidden' name='PUT' value='_PUT'>";
        }
        elseif($method=="DELETE"){
            $input="<input type='hidden' name='DELETE' value='_DELETE'>";
        }

        echo $input;

    }
    

    public static function call($url,$method="GET",$data=[],$headers=[]){
        $method=strtoupper($method);

        $response="";

        $ch=curl_init();
        

        function fixHeader($header,$default){
            if(empty($header)){

                $header=[];

                $header=array_merge($default,$header);
            }
            return $header;
        }

        
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        switch($method){

            case "GET":

                curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);

                
                break;
        
            case "POST":
                
                curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));

                $header_post=["Content-Type: application/x-www-form-urlencoded"];
                $headers=fixHeader($headers,$header_post);

                break;
        
            case "PUT":

                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");

                curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
                
                $header_put=["Content-Type: application/json"];
                $headers=fixHeader($headers,$header_put);

                break;
    
            case "DELETE":

                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");

                curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
                
                $header_delete=["Content-Type: application/json"];
                $headers=fixHeader($headers,$header_delete);

                break;
            
                
        }

        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

        $res=curl_exec($ch);

        if(curl_errno($ch)){
            echo "Error al hacer la solicitud: " .curl_error($ch);
            curl_close($ch);
            return;
        }

        curl_close($ch);
        
        $response=json_decode($res,true);

        return $response;
    }

}
?>