<?php
require_once("process.php");
session_start ();
$dades = array("IP" =>$_SERVER['REMOTE_ADDR'], "user"=>$_SESSION["email"],"time"=> date('m/d/Y h:i:s a', time()), "status"=>"logoff");
estat($dades,"conexions.json");
session_destroy();
header("location:index.php")
?>
