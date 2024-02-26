<?php
session_start();
date_default_timezone_set("Asia/Taipei");

class DB{
    protected $dsn="mysql:host=localhost;charset=utf8;dbname=bq02";
    protected $pdo;
    protected $table;

    public function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,'root','');
    }

    function q($sql){
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function a2s($array){
        foreach($array as $col=>$value){
            $tmp[]="`$col`='$value'";
        }
        return $tmp;
    }
    private function sql_all($sql,$array,$other){
        if(isset($this->table) && !empty($this->table)){
            if(is_array($array)){
                if(!empty($array)){
                    $tmp=$this->a2s($array);
                    $sql=" where ".join(" && ",$tmp);
                }
            }else{
                $sql.=" $array";
            }
            $sql.=$other;
            return $sql;
        }
    }
    protected function math($math,$col,$array='',$other=''){
        $sql="select $math(`$col`) from `$this->table` ";
        $sql=$this->sql_all($sql,$array,$other);
        return $this->pdo->query($sql)->fetchColumn();
    }

    function all($where='',$other=''){
        $sql="select * from `$this->table` ";
        $sql=$this->sql_all($sql,$where,$other);
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
    }
    function find($id){
        $sql="select * from `$this->table` ";
        if(is_array($id)){           
            $tmp=$this->a2s($id);
            $sql.=" where ".join(" && ",$tmp);
        }else if(is_numeric($id)){
            $sql.=" where `id`='$id'";
        }
        $row=$this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    function del($id){
        $sql="delete from `$this->table` ";
        if(is_array($id)){           
            $tmp=$this->a2s($id);
            $sql.=" where ".join(" && ",$tmp);
        }else if(is_numeric($id)){
            $sql.=" where `id`='$id'";
        }
        return $this->pdo->exec($sql);
    }
    function save($array){
        if(isset($array['id'])){
            $sql="update `$this->table` set ";
            if(!empty($array)){
                $tmp=$this->a2s($array);
            }
            $sql.=join(",",$tmp);
            $sql.=" where `id`='{$array['id']}'";
        }else{
            $sql="insert into from `$this->table` ";
            $cols="(`".join("`,`",array_keys($array))."`)";
            $vals="('".join("','",$array)."')";
            $sql=$sql.$cols. " values ".$vals;
        }
        return $this->pdo->exec($sql);
    }
        
        

    function sum($col,$where='',$other=''){}
    function max($col,$where='',$other=''){}
    function min($col,$where='',$other=''){}
    function count($where='',$other=''){}
}
function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";

}
function to($url){
    header("location:$url");
}
