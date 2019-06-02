<?php
include("pdoInc.php");
 
function getIp(){
    return $_SERVER['REMOTE_ADDR'];
}
 
if(isset($_POST["nickname"]) && isset($_POST["msg"]) && $_POST["nickname"] != '' && $_POST["msg"] != ''){
    $sth = $dbh->prepare('INSERT INTO chat (nickname, msg, ip) VALUES (?, ?, ?)');
    $sth->execute(array($_POST["nickname"], $_POST["msg"], getIp()));
}
?>