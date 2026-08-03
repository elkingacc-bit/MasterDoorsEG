<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$custOrderRID=$_POST["COdRID"];
$userRId=$_POST["Userid"];
$pass=$_POST["pasVal"];

	$sqlCkeckUs="SELECT `password` FROM `users` WHERE `userid` = $userRId";
	$queryCheckUs=mysqli_query($link,$sqlCkeckUs)or die("ERROR :01-SCU_CLI_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckUs) == 0)
	{
		echo 0;
	}
	else
	{
	$resCheckUs=mysqli_fetch_assoc($queryCheckUs);
	$uspsH=$resCheckUs['password'];
		
		if(password_verify($pass, $uspsH))
		{
			
			$sqlGetOrderData="SELECT `custCode`,`jobidref`, `orderType` FROM `customerpo` WHERE `poId` = $custOrderRID";
			$queryGetOrderData=mysqli_query($link,$sqlGetOrderData)or die("ERROR :02-AU_AU_S");
			$resGetOrderData= mysqli_fetch_assoc($queryGetOrderData);
			
			$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetOrderData[custCode]";
			$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :03-AU_AU_S");
			$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
			
			$sqlGetJobData="SELECT `projectName`,`salesman`,`vatstatus` FROM `job` 
			WHERE `jobId` = $resGetOrderData[jobidref]";
			$queryGetJobData=mysqli_query($link,$sqlGetJobData)or die("ERROR :04-AU_AU_S");
			$resGetJobData= mysqli_fetch_assoc($queryGetJobData);
			
			$sqlCheckSuppOrder="SELECT `SOId` FROM `supplierorder` WHERE `custPOId` = $custOrderRID";
			$queryCheckSuppOrder=mysqli_query($link,$sqlCheckSuppOrder)or die("ERROR :05-AU_AU_S");
			
			if(mysqli_num_rows($queryCheckSuppOrder) > 0)
	 		{
				$resCheckSuppOrder= mysqli_fetch_assoc($queryCheckSuppOrder);
				
				$sqlDeleteSuppOrder = "DELETE FROM `supplierorder` WHERE `SOId` = $resCheckSuppOrder[SOId]";
				mysqli_query($link,$sqlDeleteSuppOrder)or die("ERROR :06-AU_AU_S");
				
				$sqlDeleteSuppOrderItems = "DELETE FROM `supporderitems` WHERE `SOIdRef` = $resCheckSuppOrder[SOId]";
				mysqli_query($link,$sqlDeleteSuppOrderItems)or die("ERROR :07-AU_AU_S");
				
				$sqlDeleteSuppItemsDetails = "DELETE FROM `suppitemdetails` WHERE `supporderId` = $resCheckSuppOrder[SOId]";
				mysqli_query($link,$sqlDeleteSuppItemsDetails)or die("ERROR :08-AU_AU_S");
			
			}
			
			$sqlDeleteCustPO = "DELETE FROM `customerpo` WHERE `poId` = $custOrderRID";
			mysqli_query($link,$sqlDeleteCustPO)or die("ERROR :09-AU_AU_S");
			
			$sqlDeleteCustDelivery = "DELETE FROM `custorderdeliver` WHERE `poRowId` = $custOrderRID";
			mysqli_query($link,$sqlDeleteCustDelivery)or die("ERROR :10-AU_AU_S");
			
			$sqlDeleteStock = "DELETE FROM `warehouse` WHERE `poIdRef` = $custOrderRID";
			mysqli_query($link,$sqlDeleteStock)or die("ERROR :11-AU_AU_S");
			
			$sqlUpdateJob = "UPDATE `job` SET `jobref` = 5  WHERE `jobId` = $resGetOrderData[jobidref]";
			mysqli_query($link,$sqlUpdateJob)or die("ERROR :12-AU_AU_S");
			
			
			$action="Delete all PO Related date for project: $resGetJobData[projectName]";
			$logRef=5;	
			include_once("aduLog.php");


	echo 1;
	exit();	 
		}
		else
		{
			echo 0;
		}
	}


}
else
{
	echo 9;
	exit();
}

?>