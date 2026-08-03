<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $mDate=mysqli_real_escape_string($link, $_POST['fDate']);
 $amount=(float)$_POST['fAmount'];
 $qun=(float)$_POST['fQun'];
 $group=(int)$_POST['fGroup'];
 $dis=mysqli_real_escape_string($link, $_POST['fDis']);
 $logRef=116;
 $action="Sales Invesmint";
 $result = 0;
 $sqlInvesmintCount="SELECT `investmentId` FROM `investment` WHERE `transactionDate` = '$mDate' AND `salesAmount` = $amount AND `investmentGroup` = $group";
 $queryInvesmintCount=mysqli_query($link,$sqlInvesmintCount)or die("ERROR_SNSC : 01");
 $invesmintCount=mysqli_num_rows($queryInvesmintCount);
 if($invesmintCount > 0){
  $result="This Transaction has Already Been Registered";  
 }
 else{
  $sqlBuyInvesmint="INSERT INTO `investment`(`transactionDate`, `quantaty`, `buyingAmount`, `salesAmount`, `investmentGroup`,`description`) 
  VALUES ('$mDate',$qun ,'0','$amount',$group,'$dis')";
  if(mysqli_query($link,$sqlBuyInvesmint)){
   $result = 1;
   include_once("aduLog.php");
  }
 }
 echo $result;
?>