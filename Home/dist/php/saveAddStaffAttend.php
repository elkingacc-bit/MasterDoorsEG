<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test--> ";
$PoRowId = $_POST['poRID'];
$PoNum = $_POST['poNo'];
$StaffRID = $_POST['staffAttedRID'];
$attendDate = $_POST['selectedDate'];


	$sqlGetPOData="SELECT `custCode` FROM `customerpo` WHERE `poId` = $PoRowId";
	$queryGetPOData=mysqli_query($link,$sqlGetPOData)or die("ERROR :01-AU_AU_S");
	$resGetPOData= mysqli_fetch_assoc($queryGetPOData);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetPOData[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);

	$sqlGetStaffName="SELECT `staffname` FROM `allstaff` WHERE `id`= $StaffRID";
	$queryGetStaffName=mysqli_query($link,$sqlGetStaffName)or die("ERROR :01-AU_AU_S");
	$resGetStaffName= mysqli_fetch_assoc($queryGetStaffName);

	$CustomerName = $resGetCustomer['customername'];
	$staffName = $resGetStaffName['staffname'];

$sqlAddAttendDate = "INSERT INTO `outsidemanpower` ( `staffRId`, `poRowId`, `attendDate`, `datePayment`, `paymentRef`, `ref`) VALUES ($StaffRID, $PoRowId, '$attendDate', '0', 0, 0)";
	mysqli_query($link,$sqlAddAttendDate)or die("ERROR :03-ANJ_GCN_S");
	
	$action="add New Attend for Staff $staffName at PO Number: $PoNum for Customer: $CustomerName";
$logRef=10;	
include_once("aduLog.php");
	
	echo 1;


?>