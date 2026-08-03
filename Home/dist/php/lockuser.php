<?php
session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");


$userID=$_POST['lockUID'];
	
	$sqlGetUN=" SELECT `fullname` FROM `users` WHERE `userid` = $userID";
	$queryUN=mysqli_query($link,$sqlGetUN) or die("ERROR :02-SGUN_ULU_S");
	$UName=mysqli_fetch_array($queryUN);
	$action="Lock User / ".$UName[0];
	$logRef=1;
	$sqlUpdateUser="UPDATE `users` SET `validation` = 6 WHERE `userid` = $userID";
	$queryUpdateUser=mysqli_query($link,$sqlUpdateUser) or die("ERROR :01-SCA_ULU_S");
	
include_once("aduLog.php");
echo 1;
exit();	
	
?>