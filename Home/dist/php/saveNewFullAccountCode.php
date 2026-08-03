<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action="Add New Full Account";
 $logRef=110;
 $level1Code=$_POST['fCode'];
 $level2Code=$_POST['scode'];
 $level3Code=$_POST['tcode'];
 $lastName=$_POST['aName'];
 $lastCode=$_POST['aCode'];
 $accountCode=$level1Code.$level2Code.$level3Code.$lastCode;
 $sqlLevel3Count="SELECT `accountant_code_Id` FROM `accountantcode` WHERE `level1Code` = '$level1Code' AND `level2Code` = '$level2Code' 
 AND `level3Code` ='$level3Code' AND  `accountName` = '$lastName' AND `codeLen` = 12 ";
 $queryLevel3Count=mysqli_query($link,$sqlLevel3Count)or die("ERROR_SNFC : 01");
 $level3Count=mysqli_num_rows($queryLevel3Count);
 if($level3Count > 0){
  echo "This Name Has Already Been Registered";  
 }
 else{
  $sqlAddlevel3="INSERT INTO  `accountantcode`(`level1Code`,`level2Code`,`level3Code`,`accountCode`,`accountName`,`codeLen`) 
  VALUES ('$level1Code','$level2Code','$level3Code','$accountCode','$lastName',12)";
  mysqli_query($link,$sqlAddlevel3)or die("ERROR_SNB : 04");
  include_once("aduLog.php");
  echo 1;
 }
?>