<?php
session_start();
session_destroy();
include("pdoInc.php"); 
echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';

?>