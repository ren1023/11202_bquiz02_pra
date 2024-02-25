<?php
session_start();
date_default_timezone_set("Asia/Taipei");

class DB{
    protected $dsn="mysql:host=localhost;charset=utf8;dbname=db15";
    protected $table;
    protected $pdo;

    public function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,'root','');
    }

    function q($sql){
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function a2s($array){
        foreach ($array as $col=> $value){
            $tmp[]="`$col`='$value'";
        }
        return $tmp;
    }
    private function sql_all($sql,$array,$other){
        if(isset($this->table) && !empty($this->table)){
            if(isset($array)){
                if(!empty($array)){
                    $tmp=$this->a2s($array);
                    $sql.= " where ".join(" && ",$tmp);
                }
            }else{
                $sql.=" $array";
            }
            $sql.=$other;
            return $sql;
        }
    }
    protected function math($math,$col,$array='',$other=''){
        $sql="select $math(`$col`) from `$this->table`";
        $sql=$this->sql_all($sql,$array,$other);
        return $this->pdo->query($sql)->fetchColumn();
    }


    function all($where='',$other=''){
        $sql="select * from `$this->table` ";
        $sql.=$this->sql_all($sql,$where,$other);
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    }
    function find($id){
        $sql="select * from `$this->table` where ";
        if(is_array($id)){
            $tmp=$this->a2s($id);
            $sql.=join(" && ",$tmp);
        }else if(is_numeric($id)){
            $sql.=" where `id`= $id";
        }
        $row=$this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    function del($id){
        $sql="delete from `$this->table` where ";
        if(is_array($id)){
            $tmp=$this->a2s($id);
            $sql.=join(" && ",$tmp);
        }else if(is_numeric($id)){
            $sql.=" `id`= $id";
        }
        return $this->pdo->exec($sql);
    }
    function save($array){
        if(isset($array['id'])){
            $sql= "update `$this->table` set ";
            if(!empty($array)){
                $tmp=$this->a2s($array);
            }
            $sql.=join(',',$tmp);
            $sql.="where `id`='{$array['id']}'";
        }else{
            $sql="insert into `$this->table` ";
            $cols="(`".join("`,`",array_keys($array))."`)";
            $vals="('".join("','",$array)."')";
            $sql=$sql.$cols." values ".$vals;
        }
        return $this->pdo->exec($sql);
    }

    function sum($col,$where='',$other=''){
        return $this->math('sum',$col,$where,$other);
    }
    function min($col,$where='',$other=''){
        return $this->math('min',$col,$where,$other);
    }
    function max($col,$where='',$other=''){
        return $this->math('max',$col,$where,$other);
    }
    function count($where='',$other=''){
        $sql="select count(*) from `$this->table` ";
        $sql=$this->sql_all($sql,$where,$other);
        return $this->pdo->query($sql)->fetchColumn();
    }

}

function to($url){
    header("location:$url");
}
function dd($array){
    echo "<pre>";
    print_r ($array);
    echo "</pre>";
}

$Total=new DB('total');
$User=new DB('user');
// $t_q=$Total->q('select * from total');
// dd($t_q);
// $t_all=$Total->all();
// dd($t_all);
// $t_find=$Total->find(3);
// $t_find=$Total->find(['date' => date('Y-m-d')]);
// $t_find=$Total->find(['date' => date("Y-m-d")]) ['total'] ;

// dd($t_find);
// $t_del=$Total->del(2);
// dd($t_del);
// $t_save=$Total->save(['total'=>50,'date'=>date('Y-m-d')]);
// dd($t_save);
// $t_update=$Total->save(['id'=>3,'total'=>100]);
// dd($t_update);
// $t_sum=$Total->sum('total');
// dd($t_sum);
// $t_max=$Total->max('total');
// dd($t_max);
// $t_min=$Total->min('total');
// dd($t_min);
// $t_count=$Total->count();
// $t_count=$Total->count(['date'=>date("Y-m-d")]);
// dd($t_count);
// $mytoday=date('Y-m-d');
// echo "$mytoday";


if(!isset($_SESSION['visited'])){
    if($Total->count(['date'=>date('Y-m-d')]) > 0){
        $total=$Total->find(['date'=>date('Y-m-d')]);
        $total['total']++;
        $Total->save($total);
    }else{
        $Total->save(['total'=>1,'date'=>date('Y-m-d')]);
    }
    $_SESSION['visited']=1;
}

