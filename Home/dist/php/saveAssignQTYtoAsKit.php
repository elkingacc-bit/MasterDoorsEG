<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$AsKitId=$_POST['AsKitRID'];
$AsKitName=$_POST['AsKitName'];
$ItemCode=$_POST['descCode'];
$ItemName=$_POST['descName'];
$PartNum=$_POST['partNo'];
$RequQty=$_POST['AsKitQty'];

$sqlCheckDuplicated = "SELECT `descripcode` FROM `kitscomponents` WHERE `descripcode` = $ItemCode 
AND `assemplyRowId` = $AsKitId";
$queryCheckDuplicated = mysqli_query($link,$sqlCheckDuplicated)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckDuplicated) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlAddItemToAsKit = "INSERT INTO `kitscomponents`( `descripcode`, `Quantity`, `assemplyRowId`) 
		VALUES ($ItemCode, $RequQty, $AsKitId)";
		mysqli_query($link,$sqlAddItemToAsKit)or die("ERROR :02-CNS_ANS_S"); 
			
		$action="Add Item ($PartNum | $ItemName)for Assembly Kit Name: $AsKitName ";
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