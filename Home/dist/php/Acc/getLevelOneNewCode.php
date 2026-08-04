<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $level1=$_POST['firstName'];
 $sqlLevel1Count="SELECT `level1Code` FROM `accountantcode` WHERE `codeLen` = 1";
 $queryLevel1Count=mysqli_query($link,$sqlLevel1Count)or die("ERROR_GL1NC : 01");
 $level1Count=mysqli_num_rows($queryLevel1Count);
 $sqlLevel1CountLimit="SELECT `level1Code` FROM `accountantcode` WHERE `codeLen` = 1 ";
 $queryLevel1CountLimit=mysqli_query($link,$sqlLevel1CountLimit)or die("ERROR_SNFC : 01");
 $level1CountLimit=mysqli_num_rows($queryLevel1CountLimit);
 if($level1CountLimit >= 5){
  echo 0;  
 }
 else{
  if($level1Count == 0){
   $fCode = 1;
  }
  else if($level1Count > 0){
   $sqlLevel1Data="SELECT `level1Code` FROM `accountantcode` WHERE `codeLen` = 1 ORDER BY `level1Code` DESC LIMIT 1";
   $queryLevel1Data=mysqli_query($link,$sqlLevel1Data)or die("ERROR_GL1NC : 02");
   $level1Data=mysqli_fetch_assoc($queryLevel1Data); 	
   $lastCode=$level1Data['level1Code'];
   $fCode=$lastCode+1;
  }
  echo $fCode;
 }
?>