<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetAllPO="SELECT `PoNum`, `poId`, `custCode`, `jobId` ,`projectName`, `jobtype` FROM `customerpo`, 
	`job` WHERE (`jobidref` = `jobId` AND `jobref` = 3 AND `customerpo`.`poRef` !=2 AND `orderType` 
	= 'Doors') OR (`jobidref` = `jobId` AND `jobref` = 3 AND `customerpo`.`poRef` !=2 AND `orderType`
	 = 'Stock')";
	$queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	while($resGetAllPO= mysqli_fetch_assoc($queryGetAllPO))
	{
		$sqlCheckOffer="SELECT `id` FROM `stockoffers` WHERE `ref` = 1 AND `jobref` 
		= $resGetAllPO[jobId]";
		$queryCheckOffer=mysqli_query($link,$sqlCheckOffer)or die("ERROR :21-AU_AU_S");
			if(mysqli_num_rows($queryCheckOffer) > 0)
			{
				$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
				= $resGetAllPO[custCode]";
				$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :03-AU_AU_S");
				$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
		
				echo "<option data-value='$resGetAllPO[poId],$resGetAllPO[jobtype]' 
					value='$resGetAllPO[PoNum]'> $resGetAllPO[projectName] $resGetCustomer[customername]";
			}
			else 
			{
			$sqlCheckOffer="SELECT `jobproref` FROM `offerproperties` WHERE `jobproref` = 1 AND `jobidref` 
			= $resGetAllPO[jobId]";
			$queryCheckOffer=mysqli_query($link,$sqlCheckOffer)or die("ERROR :21-AU_AU_S");
				if(mysqli_num_rows($queryCheckOffer) > 0)
				{
					$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
					= $resGetAllPO[custCode]";
					$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :03-AU_AU_S");
					$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
			
					echo "<option data-value='$resGetAllPO[poId],$resGetAllPO[jobtype]' 
						value='$resGetAllPO[PoNum]'> $resGetAllPO[projectName] $resGetCustomer[customername]";
				}
			}
	}//while
}
else
{
	echo 9;
}
?>
