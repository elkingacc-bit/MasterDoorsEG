<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$keyWord = $_POST['keyWordSearch'];

$sqlGetTypeData = "SELECT `id`, `itemtype`, `jobref` FROM `itemoffer` WHERE `itemtype` LIKE('$keyWord%') 
GROUP BY `itemtype` ORDER BY `id` DESC";
$queryGetTypeData = mysqli_query($link,$sqlGetTypeData)or die("ERROR :01-ANJ_GCN_S");
if(mysqli_num_rows($queryGetTypeData) > 0)
{
	while($resultGetTypeData = mysqli_fetch_assoc($queryGetTypeData))
	{ 
	
		$sqlGetJobData = "SELECT `startDate`, `customer` FROM `job` WHERE `jobId` = 
		$resultGetTypeData[jobref]";
		$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :02-ANJ_GCN_S");
		$resultGetJobData = mysqli_fetch_assoc($queryGetJobData);
		
		$sqlGetCustName = "SELECT `customername` FROM `customers` WHERE `customercode` =
		 $resultGetJobData[customer]";
		$queryGetCustName = mysqli_query($link,$sqlGetCustName)or die("ERROR :03-ANJ_GCN_S");
		$resultGetCustName = mysqli_fetch_assoc($queryGetCustName);
			
			echo 
				"<option data-value='$resultGetTypeData[id]' value='$resultGetTypeData[itemtype]'>
				Date: $resultGetJobData[startDate] | Customer: $resultGetCustName[customername]";
		
	}
	
}
?>