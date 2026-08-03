<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	
$itemRowId = $_POST['uintRID'];	
$newPartNum = $_POST['partNo'];	
$newDescCode = $_POST['descCode'];	
$newDescName = $_POST['descName'];
$newQty = $_POST['AsKitQty'];
$AsKitName = $_POST['AsKitName'];
		
$sqlGetAsKitItems = "SELECT `descripcode`, `Quantity` FROM `kitscomponents` WHERE `id` = $itemRowId";
$queryGetAsKitItems=mysqli_query($link,$sqlGetAsKitItems)or die("ERROR :01-AU_AU_S");
$resGetAsKitItems = mysqli_fetch_assoc($queryGetAsKitItems);

	if($newDescCode == $resGetAsKitItems['descripcode'] || 
	   $newQty == $resGetAsKitItems['Quantity']  )
	{
		echo 2;
	}
	else
	{
		$sqlGetItemName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` 
		= $resGetAsKitItems[descripcode]";
		$queryGetItemName=mysqli_query($link,$sqlGetItemName)or die("ERROR :02-AU_AU_S");
		$resGetItemName = mysqli_fetch_assoc($queryGetItemName);
		$OldItemName = $resGetItemName['descriptionname'];
		$oldPartNo = $resGetItemName['partnumber'];
		
		
		$sqlEditItem = "UPDATE `kitscomponents` SET `descripcode` = $newDescCode , `Quantity` = $newQty 
		WHERE `id` = $itemRowId";
		mysqli_query($link,$sqlEditItem)or die("ERROR :04-AU_AU_S");
		
		$action="Edited Assembly Kit Name $AsKitName Componants Change Items $OldItemName to $newDescName 
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
