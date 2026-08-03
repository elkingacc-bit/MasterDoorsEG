<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$itemRowId = $_POST['IRIDFEdit'];
	
	$sqlGetItemsData="SELECT  `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`, `itemm2`, 
	`msquerprice`, `shipping`, `installation`, `margin`,`itemqty`,`totalprice`,  `FRMin`, `remarks`, 
	`Overlap`,`handling`, `doorNumber`, `itemRal` FROM `itemoffer` WHERE `id` = $itemRowId";
	$queryGetItemsData=mysqli_query($link,$sqlGetItemsData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemsData= mysqli_fetch_assoc($queryGetItemsData);
	
	
	  $resfulldate =  array(
	  "editItemType" => $resGetItemsData['itemtype'], 
	  "editItemName" => $resGetItemsData['itemname'], 
	  "editItemHight" => $resGetItemsData['itemhight'], 
      "editItemWidth" => $resGetItemsData['itemwidth'],
	  "editItemDepth" => $resGetItemsData['itemdepth'],
	  "editItemMsqu" => $resGetItemsData['itemm2'],
	  "editItemMsquPrice" => $resGetItemsData['msquerprice'],
	  "editItemPrice" => round(($resGetItemsData['itemm2'] * $resGetItemsData['msquerprice'])),
	  "editItemQty" => $resGetItemsData['itemqty'],	
	  "editItemTotalPrice" => $resGetItemsData['totalprice'],
	  "editItemFRMin" => $resGetItemsData['FRMin'],
	  "editItemRemk" => $resGetItemsData['remarks'],
	  "editItemOverlap" => $resGetItemsData['Overlap'],	
	  "editItemSipping" => $resGetItemsData['shipping'],	
	  "editItemInstall" => $resGetItemsData['installation'],
	  "editItemHandl" => $resGetItemsData['handling'],
	  "editItemDorNum" => $resGetItemsData['doorNumber'],	
	  "editItemRal" => $resGetItemsData['itemRal'],	
	  "editItemSF" => round($resGetItemsData['margin'] * 100),	
	  );
	  
	  echo json_encode($resfulldate);die;
 	
}
else
{
	echo 9;
}
?>
