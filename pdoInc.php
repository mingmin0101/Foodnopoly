<?php
//架站用來連接MYSQL
// $db_server = "localhost";
// $db_user = "id9473568_mingmin0101_2019web_hw6";
// $db_passwd = "web105306";
// $db_name = "id9473568_2019web_hw6";

//本機用來連接MYSQL
$db_server = "localhost"; //本機
$db_user  = "root"; //最高權限的使用者
$db_passwd = ""; //預設無密碼
$db_name  = "web_project";    //Database的名字
    

try {
    $dsn = "mysql:host=$db_server;dbname=$db_name";
    $dbh = new PDO($dsn, $db_user, $db_passwd);
}
catch (Exception $e){
    die('無法對資料庫連線');
}
 
$dbh->exec("SET NAMES utf8");
?>