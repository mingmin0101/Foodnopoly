<?php
session_start();
include("pdoInc.php");
 
function getIp(){
    return $_SERVER['REMOTE_ADDR'];
}
 
if(isset($_SESSION["nickname"]) && isset($_POST["msg"]) && $_POST["msg"] != ''){
    $sth = $dbh->prepare('INSERT INTO chat (nickname, msg, ip) VALUES (?, ?, ?)');
    $sth->execute(array($_SESSION["nickname"], $_POST["msg"], getIp()));
}
?>