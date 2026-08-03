<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$poRID= $_POST['PoIdGetStk'];
	
	$sqlGetOrderType = "SELECT `orderType`, `jobidref` FROM `customerpo` WHERE `poId` = $poRID";
	$queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetOrderType= mysqli_fetch_assoc($queryGetOrderType);
	
	$sqlGetOfferd="SELECT `descripcode`, `descripqty` FROM `stockoffers` WHERE `jobref` = 
	$resGetOrderType[jobidref] AND `ref` = 1";
	$queryGetOfferd=mysqli_query($link,$sqlGetOfferd)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetOfferd= mysqli_fetch_assoc($queryGetOfferd))
	{

	$sqlGetItemName="SELECT `description`, `descriptionname` FROM `stockitems` 
	WHERE `description` = $resGetOfferd[descripcode]";
	$queryGetItemName=mysqli_query($link,$sqlGetItemName)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemName= mysqli_fetch_assoc($queryGetItemName);
	
		echo "<option data-value='$resGetItemName[description],$resGetOfferd[descripqty]' 
		value='$resGetItemName[descriptionname]'> QTY = $resGetOfferd[descripqty]  ";
	}
}
?>
