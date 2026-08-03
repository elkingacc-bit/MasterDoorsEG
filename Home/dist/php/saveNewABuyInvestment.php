<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $mDate=$_POST['fDate'];
 $amount=$_POST['fAmount'];
 $qun=$_POST['fQun'];
 $group=$_POST['fGroup'];
 $dis=$_POST['fDis'];
 $logRef=116;
 $action="Buy Invesmint";
 $result = 0;
 $sqlInvesmintCount="SELECT `investmentId` FROM `investment` WHERE `transactionDate` = '$mDate' AND `buyingAmount` = $amount AND `investmentGroup` = $group";
 $queryInvesmintCount=mysqli_query($link,$sqlInvesmintCount)or die("ERROR_SNSC : 01");
 $invesmintCount=mysqli_num_rows($queryInvesmintCount);
 if($invesmintCount > 0){
  $result="This Transaction has Already Been Registered";  
 }
 else{
  $sqlBuyInvesmint="INSERT INTO `investment`(`transactionDate`, `quantaty` ,`buyingAmount`, `salesAmount`, `investmentGroup`,`description`) 
  VALUES ('$mDate',$qun, '$amount' ,'0', $group, '$dis')";
  if(mysqli_query($link,$sqlBuyInvesmint)){
   $result = 1;
   include_once("aduLog.php");
  }
 }
 echo $result;
?>