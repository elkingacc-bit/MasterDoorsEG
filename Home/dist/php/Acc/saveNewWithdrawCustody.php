<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $logRef=112;
 $result = "";
 $transactionsDate=mysqli_real_escape_string($link, $_POST['fDate']);
 $projectNum=mysqli_real_escape_string($link, $_POST['poNumber']);
 $discrebtion=mysqli_real_escape_string($link, $_POST['fdiscrebtion']);
 $recipient=(int)$_POST['frecipient'];
 $amount=(float)$_POST['famount'];
 $cashCode='116100100000';
 $sqlCustodyEmp="SELECT `fullname` FROM `users` WHERE `userid` = '$recipient' ";
 $queryEmpData=mysqli_query($link,$sqlCustodyEmp)or die("ERROR_SNSC : 01");
 $empGetData=mysqli_fetch_assoc($queryEmpData);
 $empName=$empGetData['fullname'];
 $action="Withdrawal $amount Custdy To $empName";
 //Cheak Balance
 $sqlCasher="SELECT (sum(`income`) - sum(`withdrawal`) ) as cashbalance FROM `cash_transaction`  WHERE `statmentRef` = '$cashCode'";
 $queryCasher=mysqli_query($link,$sqlCasher)or die("ERROR_SNSC : 01");
 $casherBalance=mysqli_fetch_assoc($queryCasher); 
 $avilablBalance=$casherBalance['cashbalance'];
 if($avilablBalance > $amount)
 {
  //Cheak Entry
  $sqlCustodyCount="SELECT `custody_Id` FROM `custody` 
  WHERE `custodyTransactionDate` = '$transactionsDate' AND `poNum` = '$projectNum' AND `discription` = '$discrebtion' AND `empCode` = '$recipient' 
  AND `amount` = '$amount' AND `custodyRef` = 1";
  $queryCustodyTransactionCount=mysqli_query($link,$sqlCustodyCount)or die("ERROR_SNSC : 01");
  $custodyTransactionCount=mysqli_num_rows($queryCustodyTransactionCount);
  if($custodyTransactionCount > 0)
  {
   $result="This Transaction has Already Been Registered";  
  }
  else
  {
   $sqlWithdrawalCustody="INSERT INTO `custody`(`custodyTransactionDate`,`poNum`,`discription`,`empCode`,`amount`,`custodyRef`)
   VALUES ('$transactionsDate','$projectNum','$discrebtion','$recipient','$amount',1)";
   if(mysqli_query($link,$sqlWithdrawalCustody))
   {
    $result = 1;
   }
   else
   {
    $result = "ERROR_SNSC : 02";
   } 
  }
  include_once("../aduLog.php");
  echo $result;
 } 
 else 
 {
  echo 2;
 }
?>