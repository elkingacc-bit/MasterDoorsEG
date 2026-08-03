<?php

// canceled
session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$descrip=$_POST['itemCode'];
$QTY = $_POST['QTY'];
$Location = $_POST['location'];

if($Location == "")
{
	$Location = "N/A";
}

	$getItemsInfo="SELECT `descriptionname`, `partnumber`, `amount`, `salesfactor`,
    `price`,`location` FROM `warehouse`, `stockitems` WHERE 
	`warehouse`.`description` = $descrip AND `stockitems`.`description` = $descrip LIMIT 1";
	$queryItemsInfo=mysqli_query($link,$getItemsInfo)or die("ERROR :01-GII_SAIQ_S");
	$resItemsInfo=mysqli_fetch_assoc($queryItemsInfo);
	
	$item = $resItemsInfo['descriptionname'];
	$partNum = $resItemsInfo['partnumber'];
	$salesFact = $resItemsInfo['salesfactor'];
	$price = $resItemsInfo['price'];
	$cost = $resItemsInfo['amount'];
	
	
		$sqlLocatLookUP="UPDATE `lookupwh` SET `location` = '$Location' WHERE `descriptionCode` = $descrip";
		mysqli_query($link,$sqlLocatLookUP)or die("ERROR :04-SLL_SAIQ_U");
		$sqlLocatWH="UPDATE `warehouse` SET `location` = '$Location' WHERE `description` = $descrip";
		mysqli_query($link,$sqlLocatWH)or die("ERROR :05-SLWH_SAIQ_U");

$action="Add New Quantity for Description- $item {$QTY}";
		$logRef=3;
		include_once("aduLog.php");
		
$sqlSaveNewQunt="INSERT INTO `warehouse`(`description`, `date`,`income`, `export`,`amount`, `salesfactor`,
 `price`, `partnumber`,`location`, `responsible`) VALUES
($descrip, NOW(), $QTY, 0,'$cost', '$salesFact', '$price', '$partNum', '$Location', '$respons')";
mysqli_query($link,$sqlSaveNewQunt)or die("ERROR :01-SNQ_SRI_I");

	$getItemsStock="SELECT `stock`, `cost`, `totalcost` FROM `lookupwh` WHERE `descriptionCode` = $descrip ";
	$queryItemsStock=mysqli_query($link,$getItemsStock)or die("ERROR :02-GIS_SAIQ_S");
	$resItemsStock=mysqli_fetch_assoc($queryItemsStock);
	
	$cStock = ($QTY + $resItemsStock['stock']);
	$itemCost = ($QTY * $resItemsStock['cost']);
	$tCost = ($itemCost + $resItemsStock['cost']);
	
$sqlAddInLookUP="UPDATE `lookupwh` SET `stock` = $cStock ,`totalcost` = '$tCost',`location` = '$Location' 
WHERE `descriptionCode` = $descrip";
mysqli_query($link,$sqlAddInLookUP)or die("ERROR :03-AILU_SRI_U");
		
		echo 1;
		exit();
	

?>
