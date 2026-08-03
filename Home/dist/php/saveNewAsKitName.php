<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$AssKitName=$_POST['AsKName'];

$sqlCheckname = "SELECT `kitName` FROM `assemblykits` WHERE `kitName` = '$AssKitName' ";
$queryCheckname = mysqli_query($link,$sqlCheckname)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckname) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlAddNewAsKit = "INSERT INTO `assemblykits` (`kitName`) VALUES ('$AssKitName')";
		mysqli_query($link,$sqlAddNewAsKit)or die("ERROR :02-CNS_ANS_S"); 
			
		$action="Add New Assemply Kit Name: $AssKitName";
		$logRef=12;
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