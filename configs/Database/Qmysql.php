<?php
namespace configs\Database;

use configs\Database\Query;
use PDO;
use PDOException;

class Qmysql extends Query{

    protected $table;
    private static $connection=null;
    private static $transaction=false;
    private static $idTable=null;

    
    private static function getConection(){

        if(self::$connection===null){
            self::$connection=new PDO("mysql:host=$_ENV[MYSQL_HOST]; dbname=$_ENV[MYSQL_DBNAME]; ",$_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"]);
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

    
    public function viewQuery(){
        return $this->query;
    }


    public function get(){

        $dataQuery=self::getConection()->prepare($this->query);

        foreach($this->values as $key=>$value){
            $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STMT);
        }
        
        $dataQuery->execute();

        $data=$dataQuery->fetchAll(PDO::FETCH_ASSOC);


        return $data;
    }


    public function first(){
        
        $dataQuery=self::getConection()->prepare($this->query);

        foreach($this->values as $key=>$value){
            $dataQuery->bindValue($key+1,$value,$value===null?PDO::PARAM_NULL:PDO::PARAM_STMT);
        }

        $dataQuery->execute();

        $data=$dataQuery->fetch(PDO::FETCH_ASSOC);


        return $data;
    }


    public static function lastId(){
        return self::$idTable;
    }


    public function run(){

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
            error_log("Error: ".$e->getMessage());

            return false;
        }

    }
}
?>