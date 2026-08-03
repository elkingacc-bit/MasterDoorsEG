<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$DescripRowId = $_POST['DRIDFEdit'];
	
	$sqlGetdescrip = "SELECT `id`, `descripcode`, `descripqty`, `descripprice`, `totalprice`
	 FROM `stockoffers` WHERE `id` = $DescripRowId";
	$queryGetdescrip = mysqli_query($link,$sqlGetdescrip)or die("ERROR :02-ANJ_GCN_S");
	$resGetdescrip = mysqli_fetch_assoc($queryGetdescrip);

	$sqlGetItemDate = "SELECT `descriptionname`, `partnumber` FROM `stockitems`
	 WHERE `description` =$resGetdescrip[descripcode]";
	$queryGetItemDate = mysqli_query($link,$sqlGetItemDate)or die("ERROR :03-ANJ_GCN_S");
	$resGetItemDate = mysqli_fetch_assoc($queryGetItemDate);
	
	$descName = $resGetItemDate['descriptionname'];
	$PartNum = $resGetItemDate['partnumber'];
	
	  $resfulldate =  array(
	  "PartNo" => $PartNum, 
	  "DescripName" => $descName, 
	  "DescripQTY" => $resGetdescrip['descripqty'], 
      "DescripPrice" => $resGetdescrip['descripprice'],
	  "DescripTotalP" => $resGetdescrip['totalprice'],
	 
	  );
	  
	  echo json_encode($resfulldate);die;
	
}
else
{
	echo 9;
}
?>
