<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['refJobRId'];
$ItemName = $_POST['refiItemName'];

	$sqlGetItemRef = "SELECT `id`, `itemRef` FROM `itemoffer` WHERE `jobref` = $jobRowId 
	AND `itemname` = '$ItemName' ORDER BY `id` DESC LIMIT 1";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :02-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
	
	$itemRef = $resGetItemRef['itemRef'];
	$itemID = $resGetItemRef['id'];
	
	
	 $resfulldate =  array(
	  "itemRowId" => $itemID, 
	  "itemRef" => $itemRef, 
	  );
	  
	  echo json_encode($resfulldate);die;
	
?>