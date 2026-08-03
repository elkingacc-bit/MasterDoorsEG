<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$itemRowId = $_POST['DorTypRID'];
	
	$sqlGetItemsData="SELECT  `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`, `itemm2`, 
	`msquerprice`, `FRMin`, `remarks`, `Overlap` FROM `itemoffer` 
	WHERE `id` = $itemRowId";
	$queryGetItemsData=mysqli_query($link,$sqlGetItemsData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemsData= mysqli_fetch_assoc($queryGetItemsData);
	
	
	
	  $resfulldate =  array(
	  "putItemType" => $resGetItemsData['itemtype'], 
	  "putItemName" => $resGetItemsData['itemname'], 
	  "putItemHight" => $resGetItemsData['itemhight'], 
      "putItemWidth" => $resGetItemsData['itemwidth'],
	  "putItemDepth" => $resGetItemsData['itemdepth'],
	  "putItemMsqu" => $resGetItemsData['itemm2'],
	  "putItemMsquPrice" => $resGetItemsData['msquerprice'],
	  "putItemPrice" => round(($resGetItemsData['itemm2'] * $resGetItemsData['msquerprice'])),
	  "putItemFRMin" => $resGetItemsData['FRMin'],
	  "putItemRemk" => $resGetItemsData['remarks'],
	  "putItemOverlap" => $resGetItemsData['Overlap'],	
	  );
	  
	  echo json_encode($resfulldate);die;
	
}
else
{
	echo 9;
}
?>
