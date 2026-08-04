<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");
 $action="Add New Secuond Level Accountant Code";
 $logRef=110;
 $level1Code=$_POST['fCode'];
 $level2Name=$_POST['sName'];
 $level2Code=$_POST['scode'];
 $accountCode=$level1Code.$level2Code;
 $sqlLevel2Count="SELECT `level1Code`,`level2Name` FROM `accountantcode` WHERE `level1Code` = '$level1Code' AND `level2Name` ='$level2Name' AND `codeLen` = 3";
 $queryLevel2Count=mysqli_query($link,$sqlLevel2Count)or die("ERROR_SNSC : 01");
 $level2Count=mysqli_num_rows($queryLevel2Count);
 if($level2Count > 0){
  echo "This Name Has Already Been Registered";  
 }
 else{
  $sqlAddlevel2="INSERT INTO `accountantcode`(`level1Code`,`level2Name`,`level2Code`,`accountCode`,`accountName`,`codeLen`) 
  VALUES ('$level1Code','$level2Name','$level2Code','$accountCode','$level2Name','3')";
  mysqli_query($link,$sqlAddlevel2)or die("ERROR_SNSC : 02");
  include_once("../aduLog.php");
  echo 1;
 }
?>