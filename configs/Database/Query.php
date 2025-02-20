<?php
namespace configs\Database;

class Query{

    protected $table;
    protected $query;
    protected $values=[];


    public static function select(){

        $self=new static();


        $calledClass=get_called_class();
        $self->table=(new $calledClass())->table;


        $num_args=func_num_args();
        $setSelect="";


        if($num_args>0){
            $get_args=implode(", ",func_get_args());
            $setSelect=$get_args;
        }else{
            $setSelect="*";
        }

        $self->query="SELECT $setSelect FROM {$self->table}";

        return $self;

    }


    public function join($table,$oneColumn,$compardador,$twoColumn){

        $this->query.=" JOIN $table ON $oneColumn $compardador $twoColumn ";

        return $this;
    }


    public function leftJoin($table,$oneColumn,$compardador,$twoColumn){
        
        $this->query.=" LEFT JOIN $table ON $oneColumn $compardador $twoColumn ";

        return $this;
    }


    public function rightJoin($table,$oneColumn,$compardador,$twoColumn){
        $this->query.=" RIGHT JOIN $table ON $oneColumn $compardador $twoColumn ";

        return $this;
    }
    
    
    private function verifyWhere(){
        return strpos($this->query,"WHERE")!==false?" AND ":" WHERE ";
    }


    public function where($column,$condicion,$adicional=null){
 
        $setWhere="";

        if($adicional!=null || $adicional!=""){

            array_push($this->values,$adicional);

            $setWhere="$column $condicion ? ";
        }else{

            array_push($this->values,$condicion);

            $setWhere="$column = ? ";
        }


        $this->query.=$this->verifyWhere().$setWhere;

        return $this;
    }


    public function whereIn($column,$values){

        $setWhere="";

        if(is_array($values)){

            $placeholders=implode(", ",array_fill(0,count($values),"?"));

            foreach($values as $val){
                array_push($this->values,$val);
            }

            $setWhere="$column IN( $placeholders )";

        }else{
            $setWhere="$column IN( $values )";
        }

        $this->query.=$this->verifyWhere().$setWhere;

        return $this;
    }


    public function whereNotIn($column,$values){

        $setWhere="";

        if(is_array($values)){

            $placeholders=implode(", ",array_fill(0,count($values),"?"));

            foreach($values as $val){
                array_push($this->values,$val);
            }

            $setWhere="$column NOT IN( $placeholders )";

        }else{
            $setWhere="$column NOT IN( $values )";
        }

        $this->query.=$this->verifyWhere().$setWhere;

        return $this;
    }


    public function whereBetween($column,$value1,$value2){

        array_push($this->values,$value1);
        array_push($this->values,$value2);

        $setWhere="$column BETWEEN ? AND ? ";

        $this->query.=$this->verifyWhere().$setWhere;

        return $this;
    }


    public function whereNotBetween($column,$value1,$value2){

        array_push($this->values,$value1);
        array_push($this->values,$value2);

        $setWhere="$column NOT BETWEEN ? AND ? ";

        $this->query.=$this->verifyWhere().$setWhere;

        return $this;
    }


    public function orderBy($column,$order=null){

        //return strpos($this->query,"ORDER BY")!==false?" AND ":" WHERE ";

        if(!empty($order)){
            $order=strtoupper($order);
        }else{
            $order="";
        }


        if(strpos($this->query," ORDER BY")!==false){
            $this->query.=", $column $order ";
        }else{
            $this->query.=" ORDER BY $column $order ";
        }


        return $this;
    }


    public function groupBy($column){

        $this->query.=" GROUP BY $column ";

        return $this;
    }


    public static function insert($values){

        $self=new static();

        $calledClass=get_called_class();
        $self->table=(new $calledClass())->table;


        $assoc="";
        $columns=array_keys($values);

        if(!array_is_list($values)){
            $assoc="(".implode(", ",$columns).")";
        }

        $placeholders=implode(", ",array_fill(0,count($values),"?"));


        $self->query="INSERT INTO {$self->table} $assoc VALUES ($placeholders)";

        $self->values=array_map(fn($val)=>$val===null?null:$val,array_values($values));

        return $self;
    }

    public static function update($values){

        $self=new static();

        $calledClass=get_called_class();
        $self->table=(new $calledClass())->table;


        $columns=array_keys($values);
        $set=implode(", ",array_map(fn($col)=>"$col = ?",$columns));


        $self->query="UPDATE {$self->table} SET $set";

        $self->values=array_values($values);


        return $self;
    }
    
    
    public static function delete(){

        $self=new static();

        $calledClass=get_called_class();
        $self->table=(new $calledClass())->table;


        $self->query="DELETE FROM {$self->table} ";

        return $self;
    }

}
?>