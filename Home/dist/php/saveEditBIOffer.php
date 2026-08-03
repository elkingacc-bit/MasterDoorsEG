<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$JobRID=$_POST["jobBIRowId"];	
$Cust=$_POST["custNameJ"];
$SalesMan=$_POST["SalesNJ"];
$TypeJ=$_POST["jobType"];
$pjtNameJ=$_POST["jobName"];
$JBY='Phone';
$SalesCommVal=$_POST['salesComm'];
$Date=$_POST['jSDate'];
$jobDescribtion=$_POST["jobDesc"];

 $sqlGetProjectN = "SELECT `startDate`, `projectName`, `customer`, `Commotion`, `description`, `jobtype`
	 , `salesman` FROM `job` WHERE `jobId` = $JobRID";
	$queryGetProjectN = mysqli_query($link,$sqlGetProjectN)or die("ERROR :01-ANJ_GCN_S");
	$resGetProjectN = mysqli_fetch_assoc($queryGetProjectN);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetProjectN[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetProjectN[salesman]";
	$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
	$resGetSales= mysqli_fetch_assoc($queryGetSales);

$oldCommision = "%".($resGetProjectN['Commotion'] * 100);
$oldProject = $resGetProjectN['projectName'];
$oldDate = $resGetProjectN['startDate'];
$oldType = $resGetProjectN['jobtype'];
$oldCustomer = $resGetCustomer['customername'];
$oldSales = $resGetSales['username'];


$sqlGetCustName="SELECT `customername`  FROM `customers` WHERE `customercode` = $Cust";
	$queryGetCustName=mysqli_query($link,$sqlGetCustName)or die("ERROR :01-ANJ_GCN_S");
	$resultGetCustName=mysqli_fetch_array($queryGetCustName);


		$sqlUpadteJob="UPDATE `job` SET `startDate` = '$Date', `projectName` = '$pjtNameJ', 
		`customer` = $Cust, `Commotion` = '$SalesCommVal', `description` = '$jobDescribtion', 
		`jobtype` = '$TypeJ', `salesman` = $SalesMan, `lastupdate` = NOW() WHERE `jobId` = $JobRID";

		mysqli_query($link,$sqlUpadteJob)or die("ERROR :02-ANJ_INJ_I".mysqli_error($link));
	
	$action="Edit Job From (customer: $oldCustomer | Project: $oldProject | Sales: $oldSales | Commission:
	 $oldCommision | Type: $oldType) to new data ($resultGetCustName[customername] | $TypeJ | $pjtNameJ |
	 $SalesMan | %".($SalesCommVal * 100)." | $Date)";
	$logRef=5;	
	include_once("aduLog.php");	
	
		echo 1;
		exit();	
}

else
{
	echo 9;
	exit();
}
?>