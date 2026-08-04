<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $level1=$_POST['firstCode'];
 $sqlLevel2CountLimit="SELECT `accountCode` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `codeLen` = 3";
 $queryLevel2CountLimit=mysqli_query($link,$sqlLevel2CountLimit)or die("ERROR_SNB : 01");
 $level2CountLimit=mysqli_num_rows($queryLevel2CountLimit);
 if($level2CountLimit == 89){
  echo 0;
 }
 else{
  $sqlLevel2Count="SELECT `accountCode` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `codeLen` = 3";
  $queryLevel2Count=mysqli_query($link,$sqlLevel2Count)or die("ERROR_SNB : 01");
  $level2Count=mysqli_num_rows($queryLevel2Count);
  if($level2Count == 0){
   $sCode = 10;
  }
  else if($level2Count > 0){
   $sqlLevel2Data="SELECT `level2Code` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `codeLen` = 3 ORDER BY `level2Code` DESC LIMIT 1";
   $queryLevel2Data=mysqli_query($link,$sqlLevel2Data)or die("ERROR_SNB : 02");
   $level2Data=mysqli_fetch_assoc($queryLevel2Data);     
   $lastCode=$level2Data['level2Code'];
   $sCode=$lastCode+1;
  }
  $newSCode=trim($sCode);
  echo $newSCode;    
 }
?>