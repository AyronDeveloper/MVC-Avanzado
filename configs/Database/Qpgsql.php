<?php
namespace configs\Database;

use configs\Database\Query;
use PDO;
use PDOException;

class Qpgsql extends Query{

    protected $table;
    protected static $connection=null;
    protected static $transaction=false;

    private static function getConection(){

        if(self::$connection===null){
            self::$connection=new PDO("pgsql:host=$_ENV[PG_HOST];port=$_ENV[PG_PORT];dbname=$_ENV[PG_DBNAME];",$_ENV["PG_USER"], $_ENV["PG_PASSWORD"]);
            self::$connection->exec("SET NAMES 'utf8'");
            
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


    public function get($query=null){

        if($query=="query"){
            return $this->query;
        }else{
            //$dataQuery=$this->connection->prepare($this->query);
            $dataQuery=self::getConection()->prepare($this->query);

            foreach($this->values as $key=>$value){
                $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STMT);
            }
            
            $dataQuery->execute();
    
            $data=$dataQuery->fetchAll(PDO::FETCH_ASSOC);
    
    
            return $data;
        }
    }


    public function first($query=null){

        if($query=="query"){
            return $this->query;
        }else{
            //$dataQuery=$this->connection->prepare($this->query);
            $dataQuery=self::getConection()->prepare($this->query);

            foreach($this->values as $key=>$value){
                $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STMT);
            }

            $dataQuery->execute();
    
            $data=$dataQuery->fetch(PDO::FETCH_ASSOC);
    
    
            return $data;
        }
    }


    public function run($query=null){

        if($query=="query"){
            return $this->query;
        }else{

            try{
                $result=self::getConection()->prepare($this->query);
    
                foreach($this->values as $key=>$value){
                    $result->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STMT);
                }
                
                return $result->execute();
            }catch(PDOException $e){
                error_log("Error: ".$e->getMessage());
                return false;
            }
        }
    }
    
}
?>