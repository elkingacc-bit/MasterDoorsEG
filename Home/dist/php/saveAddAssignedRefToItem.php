<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test ->  ";
$jobRowId = $_POST['JobRowIdRef'];
$itemRowId = $_POST['AsgnItemRID'];
$GroupRef = $_POST['AsgnRef'];

	$sqlGetJobNum = "SELECT `localref`, `offerValue`, `projectName` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :01-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);
	
	$offerVal = $resGetJobNum['offerValue'];
	$jobLocalRef = $resGetJobNum['localref'];
	$Project = $resGetJobNum['projectName'];
	
	$sqlGetItemQTY = "SELECT `itemqty` FROM `itemoffer` WHERE `id` = $itemRowId ";
	$queryGetItemQTY = mysqli_query($link,$sqlGetItemQTY)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemQTY = mysqli_fetch_assoc($queryGetItemQTY);

	 $itemQTY = $resGetItemQTY['itemqty'];
	
	$sqlGetAllHW = "SELECT `descripcode`, `descripquantity`, `unitPrice`, `totalprice`, `jobidref`, 
	`jobproref`, `offerItemRef`, `ioidref` FROM `offerproperties` WHERE `offerItemRef` = '$GroupRef' 
	AND `jobidref` = $jobRowId GROUP BY `descripcode`";
	$queryGetAllHW = mysqli_query($link,$sqlGetAllHW)or die("ERROR :02-ANJ_GCN_S");
	while($resGetAllHW = mysqli_fetch_assoc($queryGetAllHW))
	{
		
		$unitPrice = $resGetAllHW['unitPrice'];
		
		if($unitPrice == 0)
		{
			$sqlGetPrice = "SELECT `overprice`, `salesfactor` FROM `stockitems` 
			WHERE `description` = $resGetAllHW[descripcode] ";
			$queryGetPrice = mysqli_query($link,$sqlGetPrice)or die("ERROR :02-ANJ_GCN_S");
			$resGetPrice = mysqli_fetch_assoc($queryGetPrice);
			if($resGetPrice['overprice'] > 0 && $resGetPrice['salesfactor'] > 0)
			{
				$hwPrice = round($resGetPrice['overprice'] + 
				($resGetPrice['overprice'] * $resGetPrice['salesfactor']));
			}
		}
		
		$sqlGetOldItemQTY = "SELECT `itemqty` FROM `itemoffer` WHERE `id` = $resGetAllHW[ioidref] ";
		$queryGetOldItemQTY = mysqli_query($link,$sqlGetOldItemQTY)or die("ERROR :02-ANJ_GCN_S");
		$resGetOldItemQTY = mysqli_fetch_assoc($queryGetOldItemQTY);
		
		$oneDoorHW = ($resGetAllHW['descripquantity'] / $resGetOldItemQTY['itemqty']);
		
		$totalHWQTY = ($oneDoorHW * $itemQTY);
		$totalHWPrice = ($totalHWQTY * $unitPrice );
		
		$sqlAddHWStock = "INSERT INTO `offerproperties`(`descripcode`, `descripquantity`, `unitPrice`, 
		`totalprice`, `jobidref`, `jobproref`, `offerItemRef`, `ioidref`) VALUES ($resGetAllHW[descripcode], 
		$totalHWQTY, '$unitPrice', '$totalHWPrice', 
		$jobRowId, 0 , '$GroupRef', $itemRowId)";
		mysqli_query($link,$sqlAddHWStock)or die("ERROR :02_1-ANJ_GCN_S");
	} 
	
	$sqlGetTotalHW = "SELECT SUM(`totalprice`) AS HWTotal FROM `offerproperties` WHERE `ioidref` = $itemRowId";
	$queryGetTotalHW = mysqli_query($link,$sqlGetTotalHW)or die("ERROR :03-ANJ_GCN_S");
	$resGetTotalHW = mysqli_fetch_assoc($queryGetTotalHW);
	
	$totalHWVal = $resGetTotalHW['HWTotal'];
	$newOfferVal = round($offerVal + $totalHWVal);
	
	$sqladdHWRef = "UPDATE `itemoffer` SET `itemRef` = '$GroupRef' WHERE `id` = $itemRowId";
	mysqli_query($link,$sqladdHWRef)or die("ERROR :04-ANJ_GCN_S");
	
	$sqlUpdateOfferVal = "UPDATE `job` SET `offerValue` = '$newOfferVal' WHERE `jobId` = $jobRowId";
	mysqli_query($link,$sqlUpdateOfferVal)or die("ERROR :05-ANJ_GCN_S");
	
	
	$action="Assign Group HW ($GroupRef) For Job No: $jobLocalRef  Project: $Project";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
?>

