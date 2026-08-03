<?php
if(empty($_SESSION['id']))
{
@session_start();
}
$respons=$_SESSION['username'];
$insertLog="INSERT INTO `logreport` (`datetime`, `action`, `responsibility`, `logref`) 
VALUES (NOW(), '$action', '$respons',$logRef) ";

mysqli_query($link,$insertLog)or die("ERROR :100-IL_ALog_I");
?>