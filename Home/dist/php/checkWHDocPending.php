<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

	$sqlGetDocSerial = "SELECT `whref`, `poIdRef` FROM `warehouse` WHERE `whref` = 0";	
	$queryGetDocSerial = mysqli_query($link,$sqlGetDocSerial)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
	if(mysqli_num_rows($queryGetDocSerial) > 0)
	{
		$res =  0;
		$resGetNewDocSerial = mysqli_fetch_assoc($queryGetDocSerial);
		
	$sqlGetOrderType = "SELECT `PoNum` FROM `customerpo` WHERE `poId` = $resGetNewDocSerial[poIdRef] ";
	$queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetOrderType= mysqli_fetch_assoc($queryGetOrderType);
	
		$poNum = $resGetOrderType['PoNum'];
		
		echo $poNum;
	}
	else 
	{
		
		echo 1;
	}	
	
	exit();
	
?>
