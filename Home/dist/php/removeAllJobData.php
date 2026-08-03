<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{

$jobRowID=$_POST["DJobRowId"];

			$sqlGetJobData="SELECT `localref`, `projectName`, `customer`, `jobtype` FROM `job` WHERE `jobId` = $jobRowID";
			$queryGetJobData=mysqli_query($link,$sqlGetJobData)or die("ERROR :01-AU_AU_S");
			$resGetJobData= mysqli_fetch_assoc($queryGetJobData);
			
			$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetJobData[customer]";
			$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
			$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
			
			
			$sqlGetOrderData="SELECT `poId` FROM `customerpo` WHERE `jobidref` = $jobRowID";
			$queryGetOrderData=mysqli_query($link,$sqlGetOrderData)or die("ERROR :08-AU_AU_S");
			if(mysqli_num_rows($queryGetOrderData) > 0)
			{
				$resGetOrderData= mysqli_fetch_assoc($queryGetOrderData);
				
				$custOrderRID = $resGetOrderData['poId'];
				
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
			}
			
			
				if($resGetJobData['jobtype'] == 'Doors')
				{
					$sqlDeleteItems = "DELETE FROM `itemoffer` WHERE `jobref` = $jobRowID";
					mysqli_query($link,$sqlDeleteItems)or die("ERROR :03-AU_AU_S");
					
					$sqlDeleteProp = "DELETE FROM `offerproperties` WHERE `jobidref` = $jobRowID";
					mysqli_query($link,$sqlDeleteItems)or die("ERROR :04-AU_AU_S");
					
					$sqlUpdateJob = "DELETE FROM `job` WHERE `jobId` = $jobRowID";
					mysqli_query($link,$sqlUpdateJob)or die("ERROR :10-AU_AU_S");
					
					$sqlDeletePolicy = "DELETE FROM `offerpolicy` WHERE `jobRowId` = $jobRowID";
					mysqli_query($link,$sqlDeletePolicy)or die("ERROR :11-AU_AU_S");
					
					$action="Delete all Offer Related date for project: $resGetJobData[projectName] Offer No: $resGetJobData[jobtype] 
					| Customer: $resGetCustomer[customername]";
					$logRef=5;	
					include_once("aduLog.php");
					
					echo 1;
					exit();
				}
				
				else if($resGetJobData['jobtype'] == 'Automatic')
				{
					$sqlDeleteAuto = "DELETE FROM `autodoorsoffer` WHERE `jobid` = $jobRowID";
					mysqli_query($link,$sqlDeleteAuto)or die("ERROR :05-AU_AU_S");
					
					$sqlUpdateJob = "DELETE FROM `job` WHERE `jobId` = $jobRowID";
					mysqli_query($link,$sqlUpdateJob)or die("ERROR :10-AU_AU_S");
					
					$sqlDeletePolicy = "DELETE FROM `offerpolicy` WHERE `jobRowId` = $jobRowID";
					mysqli_query($link,$sqlDeletePolicy)or die("ERROR :11-AU_AU_S");
					
					$action="Delete all Offer Related date for project: $resGetJobData[projectName] Offer No: $resGetJobData[jobtype] 
					| Customer: $resGetCustomer[customername]";
					$logRef=5;	
					include_once("aduLog.php");
					
					echo 1;
					exit();
					
				}
				
				else if($resGetJobData['jobtype'] == 'Maintenance')
				{
					$sqlDeleteMaint = "DELETE FROM `maintoffers` WHERE `jobid` = $jobRowID";
					mysqli_query($link,$sqlDeleteMaint)or die("ERROR :06-AU_AU_S");
					
					$sqlUpdateJob = "DELETE FROM `job` WHERE `jobId` = $jobRowID";
					mysqli_query($link,$sqlUpdateJob)or die("ERROR :10-AU_AU_S");
					
					$sqlDeletePolicy = "DELETE FROM `offerpolicy` WHERE `jobRowId` = $jobRowID";
					mysqli_query($link,$sqlDeletePolicy)or die("ERROR :11-AU_AU_S");
					
					$action="Delete all Offer Related date for project: $resGetJobData[projectName] Offer No: $resGetJobData[jobtype] 
					| Customer: $resGetCustomer[customername]";
					$logRef=5;	
					include_once("aduLog.php");
					
					echo 1;
					exit();
					
				}
				
				else if($resGetJobData['jobtype'] == 'Stock')
				{
					$sqlDeleteStock = "DELETE FROM `stockoffers` WHERE `jobref` = $jobRowID";
					mysqli_query($link,$sqlDeleteStock)or die("ERROR :07-AU_AU_S");
					
					$sqlUpdateJob = "DELETE FROM `job` WHERE `jobId` = $jobRowID";
					mysqli_query($link,$sqlUpdateJob)or die("ERROR :10-AU_AU_S");
					
					$sqlDeletePolicy = "DELETE FROM `offerpolicy` WHERE `jobRowId` = $jobRowID";
					mysqli_query($link,$sqlDeletePolicy)or die("ERROR :11-AU_AU_S");
					
					$action="Delete all Offer Related date for project: $resGetJobData[projectName] Offer No: $resGetJobData[jobtype] 
					| Customer: $resGetCustomer[customername]";
					$logRef=5;	
					include_once("aduLog.php");
					
					echo 1;
					exit();
					
				}
				
				else
				{
					echo 2;
					exit();
				}	
				
}
else
{
	echo 9;
	exit();
}

?>