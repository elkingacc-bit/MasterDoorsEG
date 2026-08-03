<?php
session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
	
	$usps=$_POST['confrim'];	
	include_once("hashfunc.php");
	$newPWD=$hashed_password;
	$validPWD=$_POST['Valid'];
	$userID=$_POST['UID'];
    $verfiyUser = $_SESSION['code'];

	$sqlCheckA=" SELECT `password` FROM `users` WHERE  `codeid` = $verfiyUser";
	$queryCheckA=mysqli_query($link,$sqlCheckA) or die("ERROR :01-SCA_ULU_S");
	$resCheckA=mysqli_fetch_array($queryCheckA);
	$uspsH = $resCheckA['password'];
	if(password_verify($validPWD, $uspsH))
	{
		
	$sqlGetUN=" SELECT `fullname` FROM `users` WHERE  `userid` = $userID";
	$queryUN=mysqli_query($link,$sqlGetUN) or die("ERROR :02-SGUN_ULU_S");
	$UName=mysqli_fetch_array($queryUN);
	$action="Un-Lock User / ".$UName[0]." With new password";
	$logRef=1;
	$sqlUpdateUser="UPDATE `users` SET `password` = '$newPWD', `validation` = 0 WHERE `userid` = $userID";
	$queryUpdateUser=mysqli_query($link,$sqlUpdateUser) or die("ERROR :01-SCA_ULU_S");
	
include_once("aduLog.php");
echo 2;
exit();	
	}
	
	else
	{
		echo 1;
	}
	

?>