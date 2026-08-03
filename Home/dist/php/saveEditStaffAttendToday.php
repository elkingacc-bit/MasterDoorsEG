<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test--> ";
$OldCustName = $_POST['CustNameOld'];
$OldPoNum = $_POST['PoNumberOld'];
$attendRowId = $_POST['attendRIDEdit'];
$newPoNum = $_POST['newPoNum'];
$newPoRowId = $_POST['NewCustPoRowId'];
$StaffName = $_POST['StaffNOld'];
$Pelanty = $_POST['pelantyVal'];
$Reword = $_POST['rewordVal'];
$DateVal = $_POST['attendDateVal'];
$oldDateVal = $_POST['OldSelctDate'];

/*$sqlCheckData="SELECT `attendDate` FROM `outsidemanpower` WHERE `attendDate` = '$DateVal' AND 
`poRowId` = ";
$queryCheckData=mysqli_query($link,$sqlCheckData)or die("ERROR :01-AU_AU_S");
	
	if(mysqli_num_rows($queryCheckData) == 0)
	{
		echo 2;
	}
	else
	{
*/ 
	$sqlGetPOData="SELECT `custCode` FROM `customerpo` WHERE `poId` = $newPoRowId";
	$queryGetPOData=mysqli_query($link,$sqlGetPOData)or die("ERROR :01-AU_AU_S");
	$resGetPOData= mysqli_fetch_assoc($queryGetPOData);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetPOData[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);

	$CustomerName = $resGetCustomer['customername'];

	$sqlEditAttendDate = "UPDATE `outsidemanpower` SET  `poRowId` =  $newPoRowId, `attendDate` = '$DateVal',
	`penalty` = '$Pelanty',	`Reward` = '$Reword'  WHERE `id` = $attendRowId";
	mysqli_query($link,$sqlEditAttendDate)or die("ERROR :03-ANJ_GCN_S");
	
	$action="Edit Attend date for Staff $StaffName From PO Number: $OldPoNum for Customer: $OldCustName  
	Date: $oldDateVal | to PO Number : $newPoNum for Customer : $CustomerName Pelanty = $Pelanty 
	and Reward = $Reword And Date = $DateVal";
	
$logRef=10;	
include_once("aduLog.php");
	
	echo 1;
	exit();
	//}
?>