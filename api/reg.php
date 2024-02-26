<?php include_once "db.php";
unset($_POST['pw2']);
$res=$User->save($_POST);