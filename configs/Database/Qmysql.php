<?php
namespace configs\Database;

use configs\Database\Query;
use PDO;
use PDOException;

class Qmysql extends Query{

    protected $table;
    protected $data;
    protected $config_after=[];
    private static $connection=null;
    private static $transaction=false;
    private static $idTable=null;
    private static $erroSQL="";

    
    private static function getConection(){

        if(self::$connection===null){
            self::$connection=new PDO("mysql:host=$_ENV[MYSQL_HOST]; dbname=$_ENV[MYSQL_DBNAME]; ",$_ENV["MYSQL_USER"],$_ENV["MYSQL_PASSWORD"]);
            self::$connection->exec("SET NAMES 'utf8mb4' COLLATE utf8mb4_unicode_ci");
        }

        return self::$connection;
    } 


    public static function begin(){
        if(!self::$transaction){
            self::getConection()->beginTransaction();
            self::$transaction=true;
        }
    }


    public static function commit(){
        if(self::$transaction){
            self::getConection()->commit();
            self::$transaction=false;
        }
    }
    

    public static function rollBack(){
        if(self::$transaction){
            self::getConection()->rollBack();
            self::$transaction=false;
        }
    }

    
    public function view(){
        return $this->query;
    }


    public function get(){
        $this->clearValues();

        $dataQuery=self::getConection()->prepare($this->query);

        foreach($this->values as $key=>$value){
            $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STR);
        }
        
        $dataQuery->execute();
        $this->data=$dataQuery->fetchAll(PDO::FETCH_ASSOC);


        foreach($this->config_after as $config){
            if(method_exists($this,$config)){
                foreach($this->data as &$data){
                    $data=$this->$config($data);
                }
            }
        }


        return $this->data;
    }


    public function first(){
        $this->clearValues();
        
        $dataQuery=self::getConection()->prepare($this->query);

        foreach($this->values as $key=>$value){
            $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STR);
        }

        $dataQuery->execute();
        $this->data=$dataQuery->fetch(PDO::FETCH_ASSOC);


        if(is_array($this->data)){
            foreach($this->config_after as $config){
                if(method_exists($this,$config)){
                    $this->data=$this->$config($this->data);
                }
            }
        }


        return $this->data;
    }

    public function value(){
        $this->clearValues();
        
        $dataQuery=self::getConection()->prepare($this->query);

        foreach($this->values as $key=>$value){
            $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STR);
        }

        $dataQuery->execute();
        $data=$dataQuery->fetchColumn();

        return $data!==false?$data:null;
    }


    public static function lastId(){
        return self::$idTable;
    }

    public static function msgError(){
        return self::$erroSQL;
    }


    public function run(){
        $this->clearValues();

        try{
            $result=self::getConection()->prepare($this->query);

            foreach($this->values as $key=>$value){
                $result->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STMT);
            }
            

            if($result->execute()){

                if(stripos(trim($this->query),"insert")===0){
                    self::$idTable=self::getConection()->lastInsertId();
                }
                    
                return true;
            }else{
                return false;
            }

        }catch(PDOException $e){

            self::$erroSQL=$e->getMessage();

            return false;
        }

    }


}
?>