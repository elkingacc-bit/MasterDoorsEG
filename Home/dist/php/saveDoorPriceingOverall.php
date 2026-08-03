<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$jobRowId=$_POST["JRIDESF"];
$jobTypeSF=$_POST["jobTypeSF"];
$Install=$_POST["InstallSF"];
$Shipping=$_POST["ShippSF"];
$Price=$_POST["m2PriceSF"];
$Margin=$_POST["MarginSF"];
$Margin = round(($Margin / 100), 2);


if($Margin == 1)
{
	$Margin = 2;
}
else if( $Margin == 2)
{
	$Margin = 3;
}
else if($Margin == 3)
{
	$Margin = 4;
}
else
{
	$Margin = $Margin ;
}

//echo "test ->" . $Margin;
$totalNewDoorsPrice = 0;

//echo "test ->  ";

$sqlCheckJob="SELECT `localref`, `projectName` , `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
$queryCheckJob=mysqli_query($link,$sqlCheckJob)or die("ERROR :00-ANJ_GCN_S".mysqli_error($link));
$resGetJobData = mysqli_fetch_assoc($queryCheckJob);
$jobRefRequ = $resGetJobData['localref'];
$jobProjectN = $resGetJobData['projectName'];
$OfferVal = $resGetJobData['offerValue'];

if($jobTypeSF == "Doors")
{
		
$sqlGetDoorsPrice="SELECT SUM(`totalprice`) AS oldTotalDoors FROM `itemoffer` WHERE `jobref` = $jobRowId";
$queryGetDoorsPrice=mysqli_query($link,$sqlGetDoorsPrice)or die("ERROR :01-ANJ_GCN_S".mysqli_error($link));
$resGetDoorsPrice = mysqli_fetch_assoc($queryGetDoorsPrice);	

$oldDoorsPrice = $resGetDoorsPrice['oldTotalDoors'];	
$removeOldPrice = round($OfferVal - $oldDoorsPrice);	

$sqlGetDoorVal="SELECT `id`, `itemm2`, `itemtype`,  `itemqty` FROM `itemoffer` WHERE `jobref` = $jobRowId";
$queryGetDoorVal=mysqli_query($link,$sqlGetDoorVal)or die("ERROR :02-ANJ_GCN_S".mysqli_error($link));
while($resGetDoorVal = mysqli_fetch_assoc($queryGetDoorVal))
{
	
	$doorQty = $resGetDoorVal['itemqty'];
	
	$oneDoorm2 = round($resGetDoorVal['itemm2'], 2);
	$oneDoorm2Price = round(($oneDoorm2 * $Price), 2);
	
	$newDoorVal = round(($oneDoorm2Price + ($Install + $Shipping)), 2);
	$overhead = round(($newDoorVal * $Margin) , 2);
	
	 $finalNewVal = round($newDoorVal + $overhead);
	 $finalNewTotalVal = ($finalNewVal * $doorQty); 
	
	 $totalNewDoorsPrice+=$finalNewTotalVal;
	
	$sqlUpdateDoorData = "UPDATE `itemoffer` SET `shipping` = '$Shipping', `installation` = '$Install', `margin` = '$Margin', `totalprice` = '$finalNewTotalVal'
	, `msquerprice` = '$Price' WHERE `id` =  $resGetDoorVal[id]";
mysqli_query($link,$sqlUpdateDoorData)or die("ERROR :02-ANJ_INJ_I".mysqli_error($link));
//	echo "Test -> ".$resGetDoorVal['itemtype']. " - ".$finalNewVal. " - ".$finalNewTotalVal."\n";
}

$sqlGetNewDsPrice="SELECT SUM(`totalprice`) AS NewTotalDoors FROM `itemoffer` WHERE `jobref` = $jobRowId";
$queryGetNewDsPrice=mysqli_query($link,$sqlGetNewDsPrice)or die("ERROR :01-ANJ_GCN_S".mysqli_error($link));
$resGetNewDsPrice = mysqli_fetch_assoc($queryGetNewDsPrice);

$sqlGetHWPrice="SELECT ROUND(SUM(`totalprice`)) AS HWTotal FROM `offerproperties` WHERE `jobidref` = $jobRowId";
$queryGetHWPrice=mysqli_query($link,$sqlGetHWPrice)or die("ERROR :01-ANJ_GCN_S".mysqli_error($link));
$resGetHWPrice = mysqli_fetch_assoc($queryGetHWPrice);	
if($resGetHWPrice['HWTotal'] == 0)
{
	$totalHWPrice = 0;
}
else
{
	$totalHWPrice = $resGetHWPrice['HWTotal'];
}

	 $newJobTotalval = round($resGetNewDsPrice['NewTotalDoors'] + $totalHWPrice);

//echo "Test -> ".$newJobTotalval;

	$sqlUpdateOfferVAl = "UPDATE `job` SET `offerValue` = '$newJobTotalval' WHERE `jobId` = $jobRowId";
	mysqli_query($link,$sqlUpdateOfferVAl)or die("ERROR :02-ANJ_INJ_I".mysqli_error($link));
	
	
	$action="Add New Pricing and margin for Job $jobProjectN & PR : $jobRefRequ";
	$logRef=5;	
	include_once("aduLog.php");		
	echo 1;	
	exit();
		
	
}
	else if($jobTypeSF == "Automatic")
	{

$sqlGetDoorsPrice="SELECT SUM(`totalprice`) AS oldTotalAuto FROM `autodoorsoffer` WHERE `jobid` = $jobRowId";
$queryGetDoorsPrice=mysqli_query($link,$sqlGetDoorsPrice)or die("ERROR :01-ANJ_GCN_S".mysqli_error($link));
$resGetDoorsPrice = mysqli_fetch_assoc($queryGetDoorsPrice);	

$oldDoorsPrice = $resGetDoorsPrice['oldTotalAuto'];	
if($oldDoorsPrice == 0)
	{
		echo 3;
	}
else
	{
	$removeOldPrice = round($OfferVal - $oldDoorsPrice);	
	
	$sqlGetDoorVal="SELECT `id`, `doorprice`, `doorqty`, `totalprice` FROM `autodoorsoffer` WHERE 
	`jobid` = $jobRowId";
	$queryGetDoorVal=mysqli_query($link,$sqlGetDoorVal)or die("ERROR :02-ANJ_GCN_S".mysqli_error($link));
		while($resGetDoorVal = mysqli_fetch_assoc($queryGetDoorVal))
		{
		$DoorsVal = $resGetDoorVal['totalprice'];
		$doorQty = $resGetDoorVal['doorqty'];
		$oneDoorVal = round($DoorsVal / $doorQty);
		
		$newDoorVal = round(($oneDoorVal + ($Install + $Shipping)));
		$overhead = round($newDoorVal * $Margin);
		
		 $finalNewVal = round($newDoorVal + $overhead);
		 $finalNewTotalVal = ($finalNewVal * $doorQty); 
		
		 $totalNewDoorsPrice+=$finalNewTotalVal;
		
		$sqlUpdateDoorData = "UPDATE `autodoorsoffer` SET `shipping` = '$Shipping', `installation` = '$Install', 
		`margin` = '$Margin', `totalprice` = '$finalNewTotalVal', `doorprice` = '$finalNewVal'
		WHERE `id` =  $resGetDoorVal[id]";
		mysqli_query($link,$sqlUpdateDoorData)or die("ERROR :02-ANJ_INJ_I".mysqli_error($link));
		
		
		}
		
		$sqlGetDoorsPrice="SELECT SUM(`totalprice`) AS NewTotalDoors FROM `autodoorsoffer`
		 WHERE `jobid` = $jobRowId";
	$queryGetDoorsPrice=mysqli_query($link,$sqlGetDoorsPrice)or die("ERROR :01-ANJ_GCN_S".mysqli_error($link));
	$resGetDoorsPrice = mysqli_fetch_assoc($queryGetDoorsPrice);	
	
		 $newJobTotalval = $resGetDoorsPrice['NewTotalDoors'];
		 
		$sqlUpdateOfferVAl = "UPDATE `job` SET `offerValue` = '$newJobTotalval' WHERE `jobId` = $jobRowId";
		mysqli_query($link,$sqlUpdateOfferVAl)or die("ERROR :02-ANJ_INJ_I".mysqli_error($link));
	
	
	$action="Add New Pricing and margin for Job $jobProjectN & PR : $jobRefRequ";
	$logRef=5;	
	include_once("aduLog.php");		
		
			echo 1;
			exit();
		
	}

	}
}

else
{
	echo 9;
	exit();
}
?>