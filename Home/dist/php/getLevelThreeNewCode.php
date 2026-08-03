<?php
 date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
 $level1=$_POST['firstCode'];
 $level2=$_POST['secoundCode'];
 $sqlLevel2CountLimit="SELECT `level3Code` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `level2Code` = '$level2' AND `codeLen` = 6";
 $queryLevel2CountLimit=mysqli_query($link,$sqlLevel2CountLimit)or die("ERROR_SNB : 01");
 $level2CountLimit=mysqli_num_rows($queryLevel2CountLimit);
 if($level2CountLimit == 899){
  echo 0;
 }
 else{
  $sqlLevel2Count="SELECT `level3Code` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `level2Code` = '$level2'  AND `codeLen` = 6";
  $queryLevel2Count=mysqli_query($link,$sqlLevel2Count)or die("ERROR_SNB : 01");
  $level2Count=mysqli_num_rows($queryLevel2Count);
  if($level2Count == 0){
   $tCode = 100;
  }
  else if($level2Count > 0){
   $sqlLevel2Data="SELECT `level3Code` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `level2Code` = '$level2'  AND `codeLen` = 6 
   ORDER BY `level3Code` DESC LIMIT 1";
   $queryLevel2Data=mysqli_query($link,$sqlLevel2Data)or die("ERROR_SNB : 02");
   $level2Data=mysqli_fetch_assoc($queryLevel2Data);     
   $lastCode=$level2Data['level3Code'];
   $tCode=$lastCode+1;
  }
  echo $tCode;    
 }
?>