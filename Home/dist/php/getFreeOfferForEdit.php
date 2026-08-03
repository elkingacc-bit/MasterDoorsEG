<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$doorRowId = $_POST['DRIDFEdit'];
	
	$sqlGetItemsData="SELECT `id`, `type`, `price`, `typeqty`
	, `totalprice` FROM `maintoffers` WHERE `id` = $doorRowId";
	$queryGetItemsData=mysqli_query($link,$sqlGetItemsData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemsData= mysqli_fetch_assoc($queryGetItemsData);
	
	
	  $resfulldate =  array(
	  "editType" => $resGetItemsData['type'], 
	  //"editHright" => $resGetItemsData['hights'], 
	  //"editWedth" => $resGetItemsData['widths'], 
	  //"ediDepth" => $resGetItemsData['depths'], 
      "editQTY" => $resGetItemsData['typeqty'],
	  "editPrice" => $resGetItemsData['price'],
	  "editTotalPrice" => $resGetItemsData['totalprice'],
	 
	  );
	  
	  echo json_encode($resfulldate);die;
	
}
else
{
	echo 9;
}
?>
