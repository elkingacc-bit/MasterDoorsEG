<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test - > ";
$jobRowId = $_POST['HWJobId'];
$newItemRowId = $_POST['HWItemRowId'];
$custCode = $_POST['HWCustCode'];
$custName = $_POST['HWCustName'];
$partNum = $_POST['HWPartNo'];
$descCode = $_POST['HWCode'];
$descName = $_POST['HWname'];
$QTY = $_POST['HWQTY'];
$hwPrice = $_POST['HWPrice'];
$totalPrice = $_POST['HWItemTPrice'];
$ItemName = $_POST['hwItemName'];
 

$sqlGetItemRef = "SELECT `itemRef`, `itemqty` FROM `itemoffer` WHERE `id` = $newItemRowId ";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);

	$itemRef = $resGetItemRef['itemRef'];
	$itemQTY = $resGetItemRef['itemqty'];

$sqlCheckData = "SELECT `offproId` FROM `offerproperties` WHERE `descripcode` = $descCode 
 AND `ioidref` = $newItemRowId";
$queryCheckData = mysqli_query($link,$sqlCheckData)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryCheckData) > 0)
{
	echo 0;
}
else
{
	$QTY = ($QTY * $itemQTY);
	
	//$totalPrice = ($hwPrice * $QTY);
	//echo "test -> ";
    if($hwPrice == 0 || $hwPrice == "")
    {
        $sqlGetItemPrice = "SELECT `sales` FROM `lookupstock` WHERE `descriptioncode` = $descCode ";
    	$queryGetItemPrice = mysqli_query($link,$sqlGetItemPrice)or die("ERROR :02-ANJ_GCN_S");
    	$resGetItemPrice = mysqli_fetch_assoc($queryGetItemPrice);
    	
    	$hwPrice = $resGetItemPrice['sales'];
    	$totalPrice = round($hwPrice * $QTY );
    }
	else
	{
		$hwPrice = $hwPrice;
		$totalPrice = ($hwPrice * $QTY);
	}
	
	if($hwPrice == 0)
	{
	     $sqlGetPrice = "SELECT `overprice`, `salesfactor` FROM `stockitems` WHERE `description` = $descCode ";
    	$queryGetPrice = mysqli_query($link,$sqlGetPrice)or die("ERROR :02-ANJ_GCN_S");
    	$resGetPrice = mysqli_fetch_assoc($queryGetPrice);
    	if($resGetPrice['overprice'] > 0 && $resGetPrice['salesfactor'] > 0)
    	{
    	    $hwPrice = round($resGetPrice['overprice'] + ($resGetPrice['overprice'] * $resGetPrice['salesfactor']));
    	}
	}
	
	 //echo $totalPrice;
	
	$sqlAddItemData = "INSERT INTO `offerproperties`(`descripcode`, `descripquantity`, `unitPrice`, 
	`totalprice`, `jobidref`, `offerItemRef`, `jobproref`, `ioidref`) VALUES 
	($descCode, $QTY, '$hwPrice', '$totalPrice', $jobRowId, '$itemRef', 0, $newItemRowId)";
	mysqli_query($link,$sqlAddItemData)or die("ERROR :02-ANJ_GCN_S");

$sqlGetJobNum = "SELECT `localref`, `offerValue`, `projectName` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);

$offerVal = $resGetJobNum['offerValue'];
$jobLocalRef = $resGetJobNum['localref'];
$Poject = $resGetJobNum['projectName'];
  
$offerVal = ($offerVal + $totalPrice);

 $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal', `offerStatus` = 'Proccessing' 
 WHERE `jobId` = $jobRowId";
 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
	
	$action="Add New HW for item Ref: $itemRef ($partNum | $descName) Customer - $custName Project: $Poject 
	Job No: $jobLocalRef";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;
}


?>