<?php include_once "db.php";

$res=$User->find($_POST);
if($res){
    $_SESSION['user']=$_POST['acc'];
}
echo $res;
