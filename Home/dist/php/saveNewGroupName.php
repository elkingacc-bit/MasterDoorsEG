<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$GroupName=$_POST['GName'];

$sqlCheckname = "SELECT `typeName` FROM `whtype` WHERE `typeName` = '$GroupName' AND `typeRef` = 'Group'";
$queryCheckname = mysqli_query($link,$sqlCheckname)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckname) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlAddNewGroup = "INSERT INTO `whtype` (`typeName`, `typeRef`) VALUES ('$GroupName', 'Group')";
		mysqli_query($link,$sqlAddNewGroup)or die("ERROR :02-CNS_ANS_S"); 
			
		$action="Add New Group : $GroupName";
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