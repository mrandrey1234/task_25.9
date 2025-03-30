<?php
session_start();
setcookie("id", "", time() - 3600*24*30*12, "/");
setcookie("hash", "", time() - 3600*24*30*12, "/");
session_destroy();
header("Location: /"); 
exit; 

?>