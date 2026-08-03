<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	
$newPartNum = $_POST['partNo'];	
$newDescCode = $_POST['descCode'];	
$newDescName = $_POST['descName'];
$newQty = $_POST['AsKitQty'];
$AsKitName = $_POST['AsKitName'];
$AsKitRowId = $_POST['AsKitRowID'];
		
$sqlGetAsKitItems = "SELECT `descripcode` FROM `kitscomponents` WHERE `descripcode` = $newDescCode 
AND `assemplyRowId` = $AsKitRowId ";
$queryGetAsKitItems=mysqli_query($link,$sqlGetAsKitItems)or die("ERROR :01-AU_AU_S");


	if(mysqli_num_rows($queryGetAsKitItems) > 0)
	{
		echo 0;
	}
	else
	{	
		
		$sqlAddItemToAsKit = "INSERT INTO `kitscomponents` (`descripcode`, `Quantity`, `assemplyRowId`) VALUES 
		($newDescCode, $newQty, $AsKitRowId)";
		mysqli_query($link,$sqlAddItemToAsKit)or die("ERROR :02-AU_AU_S");
		
		$action="Add New Item To  Assembly Kit Name $AsKitName Item Name : $newDescName 
		with quantity $newQty";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();
		
	}
	
}
else
{
	echo 9;
}
?>
