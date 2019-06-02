<?php
session_start();
include("pdoInc.php");
 
if(
    isset($_POST['getNewMsgOnly']) &&
    (int)$_POST['getNewMsgOnly'] == 0 ||
    !isset($_SESSION['lastMsgTime_longpoll'])){
    $lastMsgTime = 0;
}
else {
    $lastMsgTime = $_SESSION['lastMsgTime_longpoll'];
}
 
$sth = $dbh->prepare(
    'SELECT * FROM (SELECT * from chat WHERE time > ? ORDER BY time DESC LIMIT 500) AS OAO ORDER BY time'
);
session_write_close();
ini_set('session.use_cookies', false);
session_cache_limiter(false);
error_reporting(0);
while(true){
    session_start();
    $sth->execute(array($lastMsgTime));
    $flag = 0;
    if($sth->rowCount() > 0){
        $flag = 1;
        while($row = $sth->fetch(PDO::FETCH_ASSOC)){
            echo
                htmlspecialchars($row["nickname"]).
                "(" . $row["time"] . "): ".
                htmlspecialchars($row["msg"])."\n";
            $_SESSION['lastMsgTime_longpoll'] = $row["time"];
        }
        $lastMsgTime = $_SESSION['lastMsgTime_longpoll'];
    }
    $sth->closeCursor();
    session_write_close();
    if(1 == $flag){
        break;
    }
    sleep(1);
}
?>