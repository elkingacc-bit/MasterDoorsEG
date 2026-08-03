<?php
// cancled
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$TicketRID = $_POST['PNTRID'];	

	$sqlGetMachineRef="SELECT `machineref` FROM `breakdowntickets` WHERE `id` = $TicketRID";
	$queryGetMachineRef=mysqli_query($link,$sqlGetMachineRef)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetMachineRef= mysqli_fetch_assoc($queryGetMachineRef);
	
$machineRef = $resGetMachineRef['machineref'];	

	$sqlGetPartNum="SELECT `description`, `partnumber`, `machineref` FROM `warehouse` GROUP BY `partnumber`";
	$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetPartNum= mysqli_fetch_assoc($queryGetPartNum))
	{
		$array=array_map('intval', explode(',', $resGetPartNum['machineref']));
		
		if(in_array($machineRef, $array))
		{
			echo "<option data-value='$resGetPartNum[description]' value='$resGetPartNum[partnumber]'> ";
		}
	}
}
?>
