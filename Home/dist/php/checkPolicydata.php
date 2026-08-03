<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['JRIDFCP'];

	$sqlGetPolicyData = "SELECT  `validate`, `validitydate`, `deliver`, `deliverydate`, `downpayment`, 
	`deliverypayment`, `finishpayment`, `attdName`, `custcode`, `offerNotes` FROM `offerpolicy` 
	WHERE  `jobRowId` =$jobRowId ";
	$queryGetPolicyData = mysqli_query($link,$sqlGetPolicyData)or die("ERROR :02-ANJ_GCN_S");
	
	if(mysqli_num_rows($queryGetPolicyData) == 0)
	{
	  $resfulldate =  array(
	  "Ref" => 0, 
	  );
	  
	  echo json_encode($resfulldate);die;
	}
	else
	{
	
	$resGetPolicyData = mysqli_fetch_assoc($queryGetPolicyData);
	
	
	$note = ($resGetPolicyData['offerNotes']);
	$delivery = ($resGetPolicyData['deliver']);
	$validety = ($resGetPolicyData['validate']);
	$downpay = ($resGetPolicyData['downpayment'] * 100)."%";
	$deliverpay = ($resGetPolicyData['deliverypayment'] * 100)."% ";
	$finishpay = ($resGetPolicyData['finishpayment'] * 100)."% ";
	
	 $resfulldate =  array(
	  "Ref" => 1, 
	  "AttN" => $resGetPolicyData['attdName'], 
	  "ValidNote" =>$validety, 
	  "ValidDate" => $resGetPolicyData['validitydate'], 
	  "DelivryNote" => $delivery, 
	  "DelivryDate" => $resGetPolicyData['deliverydate'], 
	  "DownPay" => $downpay, 
	  "ReceivedPay" => $deliverpay, 
	  "LastPay" => $finishpay, 
	  "Notes" => $note, 
	  );
	  
	}
	
	echo json_encode($resfulldate);die;

?>