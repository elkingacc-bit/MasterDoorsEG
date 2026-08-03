<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$doorRowId = $_POST['DRIDFEdit'];
	
	$sqlGetItemsData="SELECT `id`, `doortype`, `doorspecs`, `motorspecs`, `doorprice`,
	 `doorqty`, `totalprice` FROM `autodoorsoffer` WHERE `id` = $doorRowId";
	$queryGetItemsData=mysqli_query($link,$sqlGetItemsData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemsData= mysqli_fetch_assoc($queryGetItemsData);
	
	$doorSpecs = strip_tags($resGetItemsData['doorspecs']);
	$MotorSpecs = strip_tags($resGetItemsData['motorspecs']);
	
	  $resfulldate =  array(
	  "editDoorType" => $resGetItemsData['doortype'], 
	  "editDoorD" => $doorSpecs, 
	  "editDoorM" => $MotorSpecs, 
      "editDoorQTY" => $resGetItemsData['doorqty'],
	  "editDoorPrice" => $resGetItemsData['doorprice'],
	  "editTotalPrice" => $resGetItemsData['totalprice'],
	 
	  );
	  
	  echo json_encode($resfulldate);die;
	
}
else
{
	echo 9;
}
?>
