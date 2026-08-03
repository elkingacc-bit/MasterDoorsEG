<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

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
$Handling = $_POST['handlDir'];
$doorNum = $_POST['doorNo'];
$doorRal = $_POST['jItemRal'];


if($itemSF == 0 || $itemSF == "")
{
	$itemSF = 0;
}
else
{
	$itemSF =round(($itemSF / 100), 2);
}

$sqlCheckData = "SELECT `id` FROM `itemoffer` WHERE `itemtype` = '$itemType' AND `itemm2` = '$itemM2' AND 
`jobref` = $jobRowId";
$queryCheckData = mysqli_query($link,$sqlCheckData)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryCheckData) > 0)
{
	echo 0;
}
else
{
	//$sqlGetItemRef = "SELECT `itemRef` FROM `itemoffer` WHERE `jobref` = $jobRowId AND `itemname` != 
	//'$itemName'  AND CHAR_LENGTH(`itemRef`) = 1 ORDER BY `itemRef` DESC LIMIT 1";
	$sqlGetItemRef = "SELECT `itemRef` FROM `itemoffer` WHERE `jobref` = $jobRowId AND 
	CHAR_LENGTH(`itemRef`) = 1 ORDER BY `itemRef` DESC LIMIT 1";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :02-ANJ_GCN_S");
	
	if(mysqli_num_rows($queryGetItemRef) == 0)
	{
		$itemRef = 'A'; 
	}
	else
	{
		$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
		$itemRef = chr(ord($resGetItemRef['itemRef'])+1);
	}
	
	$sqlAddItemData = "INSERT INTO `itemoffer`(`itemtype`,`itemname`, `itemhight`, `itemwidth`, `itemdepth`,
	 `itemm2`, `msquerprice`, `shipping`, `installation`, `margin`, `itemqty`, `totalprice`, `jobref`,
	 `itemRef`, `handling`, `doorNumber`, `FRMin`, `remarks`, `Overlap`, `itemRal`, `ref`) VALUES ('$itemType'
	 ,'$itemName', '$itemHight', '$itemWidth',$itemDepth, '$itemM2', '$itemM2Price', '$itemShipp',
	 '$itemInstall', '$itemSF', $itemQTY, '$itemTotalPrice', $jobRowId, '$itemRef', '$Handling', '$doorNum' 
	 , $itemFRMin, '$itemRemarks', '$itemOverlop', '$doorRal', 0)";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :03-ANJ_GCN_S");

$sqlGetJobNum = "SELECT `localref`, `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];

$offerVal = ($offerVal + $itemTotalPrice);

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Add New Item For Customer - $CustName Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
	exit();
}


?>