<?php
namespace configs\Database;

class Query{

    protected $table;
    protected $query="";
    protected $values=[];


    protected function clearValues(){
    foreach($this->values as $i=>$val){
        if(is_string($val)){
            $val=urldecode($val);
            $val=trim($val);
            $val=preg_replace('/\s+/'," ",$val);
        }
        $this->values[$i]=$val;
    }
}


    public static function select(){

        $self=new static();


        $calledClass=get_called_class();
        $self->table=(new $calledClass())->table;


        $num_args=func_num_args();
        $selectText="";


        if($num_args>0){
            $get_args=implode(", ",func_get_args());
            $selectText=$get_args;
        }else{
            $selectText="*";
        }

        $self->query="SELECT $selectText FROM {$self->table}";

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
    
    
    protected function verifyWhere(){
        return strpos($this->query,"WHERE")!==false?" AND ":" WHERE ";
    }

    protected function verifySelect(){

        $posFrom=strpos($this->query,"FROM");

        $selectFrom=trim(substr($this->query,6,$posFrom-6));


        return strlen($selectFrom)==0?false:true;
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


    public function limit($limit){

        $this->query.=" LIMIT $limit ";

        return $this;
    }


    public function offset($offset){

        $this->query.=" OFFSET $offset ";

        return $this;
    }


    public function min($column,$as=null){

        $qAs="";
        if(!empty($as)){
            $qAs=" AS $as";
        }

        $part1=substr($this->query,0,6);

        $coma=$this->verifySelect()?", ":" ";

        $min=" MIN($column)$qAs$coma ";

        $part2=substr($this->query,6);

        $this->query=$part1.$min.$part2;

        return $this;
    }


    public function max($column,$as=null){

        $qAs="";
        if(!empty($as)){
            $qAs=" AS $as";
        }

        $part1=substr($this->query,0,6);

        $coma=$this->verifySelect()?", ":" ";

        $max=" MAX($column)$qAs$coma ";

        $part2=substr($this->query,6);

        $this->query=$part1.$max.$part2;

        return $this;

    }
    

    public function count($column,$as=null){

        $qAs="";
        if(!empty($as)){
            $qAs=" AS $as";
        }

        $part1=substr($this->query,0,6);

        $coma=$this->verifySelect()?", ":" ";

        $count=" COUNT($column)$qAs$coma ";

        $part2=substr($this->query,6);

        $this->query=$part1.$count.$part2;

        return $this;

    }


    public function avg($column,$as=null){

        $qAs="";
        if(!empty($as)){
            $qAs=" AS $as";
        }

        $part1=substr($this->query,0,6);

        $coma=$this->verifySelect()?", ":" ";

        $avg=" AVG($column)$qAs$coma ";

        $part2=substr($this->query,6);

        $this->query=$part1.$avg.$part2;

        return $this;

    }


    public function sum($column,$as=null){

        $qAs="";
        if(!empty($as)){
            $qAs=" AS $as";
        }

        $part1=substr($this->query,0,6);

        $coma=$this->verifySelect()?", ":" ";

        $SUM=" SUM($column)$qAs$coma ";

        $part2=substr($this->query,6);

        $this->query=$part1.$SUM.$part2;

        return $this;

    }

    public function distinct(){

        $part1=substr($this->query,0,6);

        $distinct=" DISTINCT ";

        $part2=substr($this->query,6);

        $this->query=$part1.$distinct.$part2;

        return $this;
    }


    public function having($having){

        $this->query.=" HAVING $having ";

        return $this;
    }


    public static function raw($raw){
        $self=new static();

        $calledClass=get_called_class();
        $self->table=(new $calledClass())->table;
        

        $self->query.=" $raw ";

        return $self;
    }
    
    
    public function addRaw($raw){
        

        $this->query.=" $raw ";

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