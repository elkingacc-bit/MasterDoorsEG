<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	
	$TicketRID = $_POST['INTRID'];	

	$sqlGetMachineRef="SELECT `machineref` FROM `breakdowntickets` WHERE `id` = $TicketRID";
	$queryGetMachineRef=mysqli_query($link,$sqlGetMachineRef)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetMachineRef= mysqli_fetch_assoc($queryGetMachineRef);
	
$machineRef = $resGetMachineRef['machineref'];	

	$sqlGetItems="SELECT `descriptionname`, `description` FROM `stockitems` WHERE `description` IS NOT NULL
	 ORDER BY `descriptionname` ASC";
	$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetItems= mysqli_fetch_assoc($queryGetItems))
	{
		$sqlGetPartNum="SELECT `machineref` FROM `warehouse` WHERE `description` = $resGetItems[description]";
		$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
		$resGetPartNum= mysqli_fetch_assoc($queryGetPartNum);
		
		$array=array_map('intval', explode(',', $resGetPartNum['machineref']));
		
		if(in_array($machineRef, $array))
		{
			echo "<option data-value='$resGetItems[description]' value='$resGetItems[descriptionname]'> ";
		}
	}
}
?>
