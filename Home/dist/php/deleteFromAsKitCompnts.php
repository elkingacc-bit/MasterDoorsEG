<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$AsKitItemId=$_POST['ItemRId'];
$AsKitRowId=$_POST['KitRowIdDelete'];

$sqlGetAsKitItems = "SELECT  `descripcode`, `Quantity`, `assemplyRowId` FROM `kitscomponents` 
WHERE `id` = $AsKitItemId";
$queryGetAsKitItems=mysqli_query($link,$sqlGetAsKitItems)or die("ERROR :01-AU_AU_S");
$resGetAsKitItems = mysqli_fetch_assoc($queryGetAsKitItems);

$sqlGetAsKitName = "SELECT  `kitName` FROM `assemblykits` WHERE `id` = $AsKitRowId";
$queryGetAsKitName=mysqli_query($link,$sqlGetAsKitName)or die("ERROR :02-AU_AU_S");
$resGetAsKitName = mysqli_fetch_assoc($queryGetAsKitName);
$AsKitName = $resGetAsKitName['kitName'];

$sqlGetItemName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` 
= $resGetAsKitItems[descripcode]";
$queryGetItemName=mysqli_query($link,$sqlGetItemName)or die("ERROR :03-AU_AU_S");
$resGetItemName = mysqli_fetch_assoc($queryGetItemName);
$itemName = $resGetItemName['descriptionname'];
$partNo = $resGetItemName['partnumber'];

		$sqlDeleteItemToAsKit = "DELETE FROM  `kitscomponents` WHERE `id` = $AsKitItemId";
		mysqli_query($link,$sqlDeleteItemToAsKit)or die("ERROR :04-CNS_ANS_S"); 
			
		$action="Delete Item ($PartNum | $ItemName)for Assembly Kit Name: $AsKitName ";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();
}
else
{
	echo 9;
	exit();
}
?>