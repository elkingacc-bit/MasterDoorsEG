<?php
 date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");
 $level1=$_POST['firstCode'];
 $level2=$_POST['secoundCode'];
 $level3=$_POST['thierdCode'];
 $sqlLevel2CountLimit="SELECT `accountCode` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `level2Code` = '$level2' AND `level3Code` ='$level3' AND `codeLen` = 12";
 $queryLevel2CountLimit=mysqli_query($link,$sqlLevel2CountLimit)or die("ERROR_SNB : 01");
 $level2CountLimit=mysqli_num_rows($queryLevel2CountLimit);
 if($level2CountLimit == 89999){
  echo 0;
 }
 else{
  $sqlLevel2Count="SELECT `accountCode` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `level2Code` = '$level2' AND `level3Code` ='$level3' AND `codeLen` = 12";
  $queryLevel2Count=mysqli_query($link,$sqlLevel2Count)or die("ERROR_SNB : 01");
  $level2Count=mysqli_num_rows($queryLevel2Count);
  if($level2Count == 0){
   $tCode = 100000;
  }
  else if($level2Count > 0){
   $sqlLevel2Data="SELECT `accountCode` FROM `accountantcode` WHERE `level1Code` = '$level1' AND `level2Code` = '$level2' AND `level3Code` ='$level3' AND `codeLen` = 12 
   ORDER BY `accountCode` DESC LIMIT 1";
   $queryLevel2Data=mysqli_query($link,$sqlLevel2Data)or die("ERROR_SNB : 02");
   $level2Data=mysqli_fetch_assoc($queryLevel2Data);     
   $lastCode=$level2Data['accountCode'];
   $tCode=$lastCode+1;
  }
  echo $tCode;    
 }
?>