<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action="Add New Assets";
 $logRef=110;  
 $level1Code=$_POST['fCode'];
 $level2Code=$_POST['scode'];
 $level3Name=$_POST['tName'];
 $level3Code=$_POST['tcode'];
 $accountCode=$level1Code.$level2Code.$level3Code;
 $sqlLevel3Count="SELECT `accountCode` FROM `accountantcode` WHERE `level1Code` = '$level1Code' AND `level2Code` = '$level2Code' 
 AND `level3Name` ='$level3Name' AND `codeLen` = 6 ";
 $queryLevel3Count=mysqli_query($link,$sqlLevel3Count)or die("ERROR_SNFC : 01");
 $level3Count=mysqli_num_rows($queryLevel3Count);
 if($level3Count > 0){
  echo "This Name Has Already Been Registered";  
 }
 else{
  $sqlAddlevel3="INSERT INTO  `accountantcode`(`level1Code`,`level2Code`,`level3Name`,`level3Code`,`accountCode`,`accountName`,`codeLen`) 
  VALUES ('$level1Code','$level2Code','$level3Name','$level3Code','$accountCode','$level3Name','6')";
  mysqli_query($link,$sqlAddlevel3)or die("ERROR_SNB : 04");
  include_once("aduLog.php");
  echo 1;
 }
?>