<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 
$jobRowId = $_POST['jRIDConf'];
	
	$sqlGetJobNum = "SELECT `localref`, `jobtype` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :01-ANJ_GCN_S");
	$resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);
 $jobLocalRef = $resGetJobNum['localref']; 
 $jobTypeCheck = $resGetJobNum['jobtype'];
 
if($jobTypeCheck == "Doors")
{
	$sqlCheckPrice = "SELECT `id` FROM `itemoffer` WHERE `totalprice` = 0 AND `jobref` = $jobRowId";
	$queryCheckPrice = mysqli_query($link,$sqlCheckPrice)or die("ERROR :02-ANJ_GCN_S");	
	if(mysqli_num_rows($queryCheckPrice) > 0)
	{
		echo 0;
	}
	else 
	{
		$sqlCheckHWPrice = "SELECT `offproId` FROM `offerproperties` WHERE `totalprice` = 0 
		AND `jobidref` = $jobRowId";
		$queryCheckHWPrice = mysqli_query($link,$sqlCheckHWPrice)or die("ERROR :02_1-ANJ_GCN_S");
		if(mysqli_num_rows($queryCheckHWPrice) > 0)
		{
			echo 3;
		}
		else
		{
		$sqlUpdateOfferStatus = "UPDATE `job` SET `jobref` = 2 , `offerStatus` = 'Confirmed' 
		 WHERE `jobId` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :02-ANJ_GCN_S");
		 
		 $sqlUpdateOfferProp = "UPDATE `offerproperties` SET `jobproref` = 1 WHERE `jobidref` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferProp)or die("ERROR :03-ANJ_GCN_S");
		 
		 $sqlUpdateOfferItems = "UPDATE `itemoffer` SET `ref` = 2 WHERE `jobref` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferItems)or die("ERROR :04-ANJ_GCN_S");
			
			$action="Confrim Doors Offer for Job Number : $jobLocalRef";
		
		$logRef=5;	
		include_once("aduLog.php");
			
			echo 1;
		}
	}
}
else if($jobTypeCheck == "Automatic")
{
	$sqlCheckPrice = "SELECT `id` FROM `autodoorsoffer` WHERE `totalprice` = 0 AND `jobid` = $jobRowId";
	$queryCheckPrice = mysqli_query($link,$sqlCheckPrice)or die("ERROR :02_2-ANJ_GCN_S");	
	if(mysqli_num_rows($queryCheckPrice) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlUpdateOfferStatus = "UPDATE `job` SET `jobref` = 2 , `offerStatus` = 'Confirmed' 
		 WHERE `jobId` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :03_2-ANJ_GCN_S");
		 
		 $sqlUpdateOfferItems = "UPDATE `autodoorsoffer` SET `ref` = 2 WHERE `jobid` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferItems)or die("ERROR :04_2-ANJ_GCN_S");
			
			$action="Confrim Auto Doors Offer for Job Number : $jobLocalRef";
		
		$logRef=5;	
		include_once("aduLog.php");
			
			echo 1;
		
	}
}

else if($jobTypeCheck == "Stock")
{
	$sqlCheckPrice = "SELECT `id` FROM `stockoffers` WHERE `totalprice` = 0 AND `jobref` = $jobRowId";
	$queryCheckPrice = mysqli_query($link,$sqlCheckPrice)or die("ERROR :02_3-ANJ_GCN_S");	
	if(mysqli_num_rows($queryCheckPrice) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlUpdateOfferStatus = "UPDATE `job` SET `jobref` = 2 , `offerStatus` = 'Confirmed' 
		 WHERE `jobId` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :03_3-ANJ_GCN_S");
		 
		 $sqlUpdateOfferItems = "UPDATE `stockoffers` SET `ref` = 1 WHERE `jobref` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferItems)or die("ERROR :04_3-ANJ_GCN_S");
			
			$action="Confrim Stock Offer for Job Number : $jobLocalRef";
		
		$logRef=5;	
		include_once("aduLog.php");
			
			echo 1;
		
	}
}

else if($jobTypeCheck == "Maintenance")
{
	$sqlCheckPrice = "SELECT `id` FROM `maintoffers` WHERE `totalprice` = 0 AND `jobid` = $jobRowId";
	$queryCheckPrice = mysqli_query($link,$sqlCheckPrice)or die("ERROR :02_4-ANJ_GCN_S");	
	if(mysqli_num_rows($queryCheckPrice) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlUpdateOfferStatus = "UPDATE `job` SET `jobref` = 2 , `offerStatus` = 'Confirmed' 
		 WHERE `jobId` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :03_4-ANJ_GCN_S");
		 
		 $sqlUpdateOfferItems = "UPDATE `maintoffers` SET `ref` = 2 WHERE `jobid` = $jobRowId";
		 mysqli_query($link,$sqlUpdateOfferItems)or die("ERROR :04_4-ANJ_GCN_S");
			
			$action="Confrim Maintenance Offer for Job Number : $jobLocalRef";
		
		$logRef=5;	
		include_once("aduLog.php");
			
			echo 1;
		
	}
}
else
{
	echo "Unexpected Error!!!";
}
 

 

?>