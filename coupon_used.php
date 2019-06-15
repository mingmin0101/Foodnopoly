<?php
session_start();
include("pdoInc.php");
 
if(isset($_SESSION["account"]) && $_POST["id"] != ''){
    // UPDATE 表名称 SET 列名称 = 新值 WHERE 列名称 = 某值
    $sth = $dbh->prepare('UPDATE couponrecord SET status = "invalid" WHERE id = ?');
    $sth->execute(array($_POST["id"]));
}
?>