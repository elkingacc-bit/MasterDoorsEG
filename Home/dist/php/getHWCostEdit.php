<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$oferProRowId = $_POST['hwTableRowId'];

	$sqlGetItemCode = "SELECT `descripcode` FROM `offerproperties` WHERE `offproId` = $oferProRowId";
	$queryGetItemCode = mysqli_query($link,$sqlGetItemCode)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemCode = mysqli_fetch_assoc($queryGetItemCode);
	
	$itemCode = $resGetItemCode['descripcode'];

	$sqlGetItemCost = "SELECT `cost` FROM `lookupstock` WHERE `descriptioncode` = $itemCode";
	$queryGetItemCost = mysqli_query($link,$sqlGetItemCost)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemCost = mysqli_fetch_assoc($queryGetItemCost);
	
	echo $resGetItemCost['cost'];
	
?>