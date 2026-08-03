<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test--> ";
$attRowId = $_POST['attendRID'];
$PoNum = $_POST['PoNumber'];
$StaffName = $_POST['StaffN'];
$CustName = $_POST['CustName'];


$sqlRemoveAttendDate = "DELETE FROM  `outsidemanpower` WHERE `id` = $attRowId";
	mysqli_query($link,$sqlRemoveAttendDate)or die("ERROR :03-ANJ_GCN_S");
	
	$action="Remove Attend for Staff $staffName at PO Number: $PoNum for Customer: $CustomerName";
$logRef=10;	
include_once("aduLog.php");
	
	echo 1;


?>