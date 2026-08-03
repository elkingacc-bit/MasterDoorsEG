<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$poRID= $_POST['PoIdGetStk'];
	$sqlGetOrderType = "SELECT `orderType`, `jobidref` FROM `customerpo` WHERE `poId` = $poRID ";
	$queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetOrderType= mysqli_fetch_assoc($queryGetOrderType);
	
	$sqlGetOfferd="SELECT `descripcode`, `descripqty` FROM `stockoffers` WHERE `jobref` = 
	$resGetOrderType[jobidref] AND `ref` = 1";
	$queryGetOfferd=mysqli_query($link,$sqlGetOfferd)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetOfferd= mysqli_fetch_assoc($queryGetOfferd))
	{

	$sqlGetPartNum="SELECT `description`, `partnumber` FROM `stockitems` WHERE `description` = 
	$resGetOfferd[descripcode]";
	$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetPartNum= mysqli_fetch_assoc($queryGetPartNum))
	
	echo "<option data-value='$resGetPartNum[description],$resGetOfferd[descripqty]' 
	value='$resGetPartNum[partnumber]'> QTY = $resGetOfferd[descripqty]  ";
	}
} 
?>
