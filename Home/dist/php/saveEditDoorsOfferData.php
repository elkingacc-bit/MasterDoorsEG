<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$ItemRowId = $_POST['ItemTableId'];
$jobRowId = $_POST['tRowId'];
$CustCode = $_POST['jCustCode'];
$CustName = $_POST['jCustname'];
$itemType = $_POST['jItemType'];
$itemName = $_POST['jItemName'];
$itemHight = $_POST['jItemH'];
$itemWidth = $_POST['jItemW'];
$itemDepth = $_POST['jItemDepth'];
$itemM2 = $_POST['jItem2'];
$itemM2Price = $_POST['jM2Price'];
$itemQTY = $_POST['jItemQty'];
$itemTotalPrice = $_POST['jItemTotalPrice'];
$itemFRMin = $_POST['jItemFRMin'];
$itemRemarks = $_POST['jItemRemk'];
$itemOverlop = $_POST['jItemOverlap'];
$itemSF = $_POST['jMargin'];
$itemInstall = $_POST['jInstall'];
$itemShipp = $_POST['jShipping'];
$ItemRal = $_POST['jItemRal'];
$ItemHandel = $_POST['handlDir'];
$ItemDoorNo = $_POST['doorNo'];
// echo "test -> "; 
if($itemSF == 0 || $itemSF == "")
{
	$itemSF = 0;
}
else
{
	$itemSF =round(($itemSF / 100), 2);
}
$sqlCheckData = "SELECT `id` FROM `itemoffer` WHERE `itemtype` = '$itemType' AND `itemm2` = '$itemM2' AND 
`jobref` = $jobRowId AND `id` != $ItemRowId";
$queryCheckData = mysqli_query($link,$sqlCheckData)or die("ERROR :01-ANJ_GCN_S");

//if(mysqli_num_rows($queryCheckData) > 0)
//{
//	echo 0;
//}
//else
//{
	
	$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);
	
	$offerVal = $resGetJobNum['offerValue'];
	$jobLocalRef = $resGetJobNum['localref'];
	
	
	
	$sqlGetItemVal = "SELECT `totalprice`, `itemqty` FROM `itemoffer` WHERE `id` = $ItemRowId";
	$queryGetItemVal = mysqli_query($link,$sqlGetItemVal)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemVal = mysqli_fetch_assoc($queryGetItemVal);
	$itemTotalPriceOld = $resGetItemVal['totalprice'];
	$itemQTYOld = $resGetItemVal['itemqty'];
	
	$sqlGetOldTotalHW = "SELECT SUM(`totalprice`) AS HWTotal FROM `offerproperties` 
	WHERE `ioidref` = $ItemRowId";
	$queryGetOldTotalHW = mysqli_query($link,$sqlGetOldTotalHW)or die("ERROR :07-ANJ_GCN_S");
	$resGetOldTotalHW = mysqli_fetch_assoc($queryGetOldTotalHW);
	
	$totalHWOldVal = $resGetOldTotalHW['HWTotal'];
	 
	 
	$sqlGetHW = "SELECT `offproId`, `descripquantity`, `unitPrice`, `othercost`, `overhead` 
	FROM `offerproperties` WHERE `ioidref` = $ItemRowId";
	$queryGetHW = mysqli_query($link,$sqlGetHW)or die("ERROR :07-ANJ_GCN_S");
	while($resGetHW = mysqli_fetch_assoc($queryGetHW))
	{
		$hwQTY = $resGetHW['descripquantity'];
		$hwPrice = $resGetHW['unitPrice'];
		//$hwOtherCost = $resGetHW['othercost'];
		 //$hwOverhead = round(($resGetHW['overhead'] / 100) , 2);
		//$hwOverHeadVal = round(($hwPrice + $hwOtherCost) * $hwOverhead);
		//$hwFilnalPrice = round(($hwPrice + $hwOtherCost) + $hwOverHeadVal);
		$oneDoorHW = ($hwQTY / $itemQTYOld);
		$newHWQTY = ($oneDoorHW * $itemQTY);
		$newHWTotalPrice = ($newHWQTY * $hwPrice);
		
		$sqlGetUpdateHW = "UPDATE `offerproperties` SET `descripquantity` = $newHWQTY,  
		`totalprice` = '$newHWTotalPrice' WHERE `offproId` = $resGetHW[offproId]";
		mysqli_query($link,$sqlGetUpdateHW)or die("ERROR :08-ANJ_GCN_S");
	}
	
	
	$sqlGetTotalHW = "SELECT SUM(`totalprice`) AS HWTotal FROM `offerproperties` WHERE `ioidref` = $ItemRowId";
	$queryGetTotalHW = mysqli_query($link,$sqlGetTotalHW)or die("ERROR :07-ANJ_GCN_S");
	$resGetTotalHW = mysqli_fetch_assoc($queryGetTotalHW);
	
	if($resGetTotalHW['HWTotal'] == "")
	{
		$totalHWVal = 0;
	}
	else
	{
		$totalHWVal = $resGetTotalHW['HWTotal'];
	}
	 
	$oldItemTval = ($itemTotalPriceOld + $totalHWOldVal);	
	$NetofferVal = ($offerVal - $oldItemTval);
	 
	 $newItemTPrice = ($itemTotalPrice + $totalHWVal);
	
	 $NewofferVal = ($NetofferVal + $newItemTPrice);
	
	$sqlEditItemData = "UPDATE `itemoffer` SET `itemtype` = '$itemType', `itemname` = '$itemName', `itemhight`
	 = '$itemHight', `itemwidth` = '$itemWidth', `itemdepth` = $itemDepth, `itemm2` = '$itemM2', `msquerprice`
	 = '$itemM2Price', `shipping` = '$itemShipp', `installation` = '$itemInstall', `margin` = '$itemSF',
	 `itemqty` = $itemQTY, `totalprice` = '$itemTotalPrice', `handling` = '$ItemHandel', 
	 `doorNumber` = '$ItemDoorNo', `FRMin` = $itemFRMin , `remarks` = '$itemRemarks', `Overlap` = 
	 '$itemOverlop', `itemRal` = '$ItemRal' WHERE `id` =  $ItemRowId";
	mysqli_query($link,$sqlEditItemData)or die("ERROR :03-ANJ_GCN_S");

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$NewofferVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
$action="Edit Item data: $itemName For Customer - $CustName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit(); 
//}


?>