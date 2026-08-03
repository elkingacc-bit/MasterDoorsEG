<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$subGroupName=$_POST['SGName'];

$sqlCheckname = "SELECT `typeName` FROM `whtype` WHERE `typeName` = '$subGroupName' AND `typeRef` 
= 'Sub Group'";
$queryCheckname = mysqli_query($link,$sqlCheckname)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckname) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlAddNewGroup = "INSERT INTO `whtype` (`typeName`, `typeRef`) VALUES ('$subGroupName', 'Sub Group')";
		mysqli_query($link,$sqlAddNewGroup)or die("ERROR :02-CNS_ANS_S"); 
			
		$action="Add New Sub Group : $subGroupName";
		$logRef=6;
		include_once("aduLog.php");
			echo 1;
			exit();
	}
}
else
{
	echo 9;
	exit();
}
?>