<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$itemRowId = $_POST['DorTypRID2'];
	
	$sqlGetItemsData="SELECT  `doortype`, `doorspecs`, `motorspecs`, `doorprice` FROM `autodoorsoffer` 
	WHERE `id` = $itemRowId";
	$queryGetItemsData=mysqli_query($link,$sqlGetItemsData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemsData= mysqli_fetch_assoc($queryGetItemsData);
	
	
	$doorSpecs = strip_tags($resGetItemsData['doorspecs']);
	$MotorSpecs = strip_tags($resGetItemsData['motorspecs']);
	
	  $resfulldate =  array(
	  "putItemType2" => $resGetItemsData['doortype'], 
	  "putDoorSpecs" => $doorSpecs, 
      "putMotorSpecs" => $MotorSpecs,
	  "putDoorPrice" => $resGetItemsData['doorprice'],
	  );
	  
	  echo json_encode($resfulldate);die;
	
}
else
{
	echo 9;
}
?>
