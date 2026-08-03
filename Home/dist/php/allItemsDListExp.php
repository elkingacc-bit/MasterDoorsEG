<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$poNum= $_POST['PoNumGet'];
	$poRID= $_POST['PoIdGet'];
	$sqlGetOrderType = "SELECT `orderType`, `jobidref` FROM `customerpo` WHERE `poId` = $poRID";
	$queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetOrderType= mysqli_fetch_assoc($queryGetOrderType);
	$oderType = $resGetOrderType['orderType'];
	$jobRefRID = $resGetOrderType['jobidref'];
	if($oderType == "Doors")
	{
	$sqlGetItemData="SELECT `id`, `itemtype`, `itemname` FROM `itemoffer` WHERE `jobref` = $jobRefRID 
	AND `ref` != 3";
	$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
		while($resGetItemData= mysqli_fetch_assoc($queryGetItemData))
		{
			
			echo "<option data-value='$resGetItemData[id]' value='$resGetItemData[itemtype]'>
			$resGetItemData[itemname] ";
		}
	}
	
}
?>
