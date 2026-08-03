<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{

$custPo = $_POST['customerPO'];
$SuppName = $_POST['supplierName'];	
$SuppCode = $_POST['SupplierCode'];	
$DDate = $_POST['DeliveryD'];
$OrderNote = $_POST['SuppOrderNote'];	

if($OrderNote == "")
{
	$OrderNote = "No Any data added...";
}
	$sqlGetPOId="SELECT `poId` FROM `customerpo` WHERE `PoNum` = '$custPo' ";
	$queryGetPOId=mysqli_query($link,$sqlGetPOId)or die("ERROR :01-AU_AU_S");
	$resGetPOId= mysqli_fetch_assoc($queryGetPOId);
	$PORowId = $resGetPOId['poId'];
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
	= $SuppCode";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlCheckSONo="SELECT `OrderNumber` FROM `supplierorder` WHERE `OrderNumber` LIKE('CMS_SO-%') 
	ORDER BY lpad(`OrderNumber`, 100, 0) DESC LIMIT 1";
	$queryCheckSONo=mysqli_query($link,$sqlCheckSONo)or die("ERROR :03-GNLPR_COR_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckSONo) == 0)
	{
		$SupporderNumber = 'CMS_SO-1';
		
	}
	else
	{
		$resCheckSONo = mysqli_fetch_assoc($queryCheckSONo);
		$currntPR = $resCheckSONo['OrderNumber'];
		$new = 1;
		$PRNum=substr($currntPR, strrpos($currntPR, '-') + 1);
		
		$newPR = ($PRNum + $new);
		$newFullPR = "CMS_SO-".$newPR;
		
		 $SupporderNumber = trim($newFullPR);
	}
	

$sqlAddSuppOrder = "INSERT INTO `supplierorder`(`SuppCode`, `OrderNumber`, `date`, `deliveryDate`, `authUser`
, `orderNotes`, `custPOId`) VALUES ($SuppCode, '$SupporderNumber', NOW(), '$DDate', '$_SESSION[username]', 
'$OrderNote', $PORowId)";	
mysqli_query($link,$sqlAddSuppOrder)or die("ERROR :04-GNLPR_COR_S".mysqli_error($link));


	$action="Add New Supplier Order No: $SupporderNumber for PO: $custPo";
	$logRef=9;	
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
