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
	$sqlGetItemName="SELECT `description`, `descriptionname` FROM `stockitems` 
	WHERE `description` = $resGetItemData[descripcode]";
	$queryGetItemName=mysqli_query($link,$sqlGetItemName)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemName= mysqli_fetch_assoc($queryGetItemName);
		
			echo "<option data-value='$resGetItemName[description],$resGetItemData[descripquantity]' value='$resGetItemName[descriptionname]'> QTY = $resGetItemData[descripquantity]";
	}
}
?>
