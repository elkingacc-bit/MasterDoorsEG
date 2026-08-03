<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetAllPO="SELECT `PoNum`, `custCode`,`projectName` FROM `customerpo`, `job` WHERE (`jobidref` = `jobId` 
	AND `jobtype` = 'Doors' AND `jobref` = 3 AND `poId` NOT IN (SELECT `custPOId` FROM `supplierorder`)) 
	OR (`jobidref` = `jobId` AND `jobidref` = `jobId` AND `jobtype` = 'Automatic' AND `poId` NOT IN 
	(SELECT `custPOId` FROM `supplierorder`)) ";
	$queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO))
	{
		$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
		= $resGetAllPO[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
		echo "<option data-value='$resGetAllPO[custCode]' 
				value='$resGetAllPO[PoNum]'> $resGetAllPO[projectName] $resGetCustomer[customername]";
	}
}
else
{
	echo 9;
}
?>
