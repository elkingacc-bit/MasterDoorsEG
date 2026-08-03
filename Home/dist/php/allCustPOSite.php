<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))//`projectName``jobId`
{ 
	$sqlGetAllPO="SELECT `PoNum`, `poId`, `custCode`, `orderType`, `jobidref` FROM `customerpo`
	WHERE (`customerpo`.`poRef` = 1) OR (`customerpo`.`poRef` IS NULL) ";
	$queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO))
	{
		$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` 
		= $resGetAllPO[jobidref]";
		$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :02-AU_AU_S");
		$resGetProject= mysqli_fetch_assoc($queryGetProject);
		
		$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
		= $resGetAllPO[custCode]";
		$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :03-AU_AU_S");
		$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
		
		if($resGetAllPO['orderType'] != 'Stock')
		{
		echo "<option data-value='$resGetAllPO[poId]' 
				value='$resGetAllPO[PoNum] $resGetProject[projectName]'> $resGetCustomer[customername] 
				$resGetAllPO[orderType]";
		}
	}
}
else
{
	echo 9;
}
?>
