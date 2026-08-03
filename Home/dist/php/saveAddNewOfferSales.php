<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$Cust=$_POST["custNameJ"];
$SalesMan=$_SESSION['code'];
$TypeJ=$_POST["jobType"];
$pjtNameJ=$_POST["jobName"];
$JBY='Phone';
$SalesCommVal=$_POST['salesComm'];
$jobRefRequ=ltrim($_POST['jRequRef']);
$jobDescribtion=$_POST["jobDesc"];
$jobMonth=date("m");
$jobYear=date("Y");

$sqlCheckJob="SELECT `localref` FROM `job` WHERE `localref` = '$jobRefRequ' AND `customer` = $Cust";
$queryCheckJob=mysqli_query($link,$sqlCheckJob)or die("ERROR :00-ANJ_GCN_S".mysqli_error($link));
if(mysqli_num_rows($queryCheckJob) > 0)
{
	echo 0;
}
else
{

$sqlGetCustName="SELECT `customername`  FROM `customers` WHERE `customercode` = $Cust";
	$queryGetCustName=mysqli_query($link,$sqlGetCustName)or die("ERROR :01-ANJ_GCN_S");
	$resultGetCustName=mysqli_fetch_array($queryGetCustName);
$action="Add New Job For- $resultGetCustName[customername] PR : $jobRefRequ";
$logRef=5;	
include_once("aduLog.php");


		$sqlInsertNewJob="INSERT INTO `job`(`startDate`,`localref`, `projectName`, `customer`, `Commotion`, 
		`responsible`, `offerStatus`, `description`, `jobtype`, `jobreceivables`,`salesman`, 
		`lastupdate`) VALUES(NOW(), '$jobRefRequ','$pjtNameJ', $Cust,'$SalesCommVal',  '$_SESSION[fname]',
		 'Start', '$jobDescribtion', '$TypeJ', '$JBY',  $SalesMan, NOW())";

	mysqli_query($link,$sqlInsertNewJob)or die("ERROR :02-ANJ_INJ_I".mysqli_error($link));
	
		echo 1;
		exit();	
   }
}
else
{
	echo 9;
	exit();
}
?>