<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$ItemRowId= $_POST['ItemRowIdStock'];

	$sqlGetItemData="SELECT `descripcode`, `descripquantity` FROM `offerproperties` WHERE `ioidref` = 
	$ItemRowId AND `jobproref` = 1";
	$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetItemData= mysqli_fetch_assoc($queryGetItemData))
	{
	$sqlGetPartNum="SELECT `description`, `partnumber` FROM `stockitems` 
	WHERE `description` = $resGetItemData[descripcode]";
	$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetPartNum= mysqli_fetch_assoc($queryGetPartNum);
		
			echo "<option data-value='$resGetPartNum[description],$resGetItemData[descripquantity]' value='$resGetPartNum[partnumber]'> QTY = $resGetItemData[descripquantity]";
	}
} 
?>
