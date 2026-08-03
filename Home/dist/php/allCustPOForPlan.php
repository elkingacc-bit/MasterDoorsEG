<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetAllPO="SELECT `PoNum`, `poId`,`projectName`, `jobtype`,`custCode` FROM `customerpo`, 
	`job` WHERE (`jobidref` = `jobId` AND `jobref` = 3 AND `customerpo`.`poRef` IS NULL AND `orderType` 
	= 'Doors') OR (`jobidref` = `jobId` AND `jobref` = 3 AND `customerpo`.`poRef` IS NULL AND `orderType`
	 = 'Automatic')";
	$queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO))
	{
		$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
		= $resGetAllPO[custCode]";
		$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :03-AU_AU_S");
		$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
				
		echo "<option data-value='$resGetAllPO[poId]' value='$resGetAllPO[projectName]'> 
		 $resGetCustomer[customername]";
		
	}//while 
}
else
{
	echo 9;
}
?>
