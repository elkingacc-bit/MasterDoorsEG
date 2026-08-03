<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action="Add First Level Assets";
 $logRef=110;  
 $level1Name=$_POST['name'];
 $level1Code=$_POST['code'];
 $sqlLevel1Count="SELECT `level1_name`,`level1Code` FROM `accountantcode` WHERE `codeLen` = 1 AND `level1_name` = '$level1Name'";
 $queryLevel1Count=mysqli_query($link,$sqlLevel1Count)or die("ERROR_SNFC : 02");
 $level1Count=mysqli_num_rows($queryLevel1Count);
 if($level1Count > 0){
  echo "This Name Has Already Been Registered";  
 }
 else{
  $sqlAddBranch="INSERT INTO `accountantcode`(`level1_name`,`level1Code`,`accountCode`,`accountName`,`codeLen`) 
  VALUES ('$level1Name',$level1Code,$level1Code,'$level1Name', 1)";
  mysqli_query($link,$sqlAddBranch)or die("ERROR_SNFC : 03");
  include_once("aduLog.php");
  echo 1;
 }    
?>