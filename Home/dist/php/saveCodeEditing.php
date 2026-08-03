<?php
 include_once("authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $action=" Edit In All Code";
 $logRef=110;
 $codeId=(int)$_POST['rowNum'];
 $name=mysqli_real_escape_string($link, $_POST['nName']);
 $query="UPDATE `accountantcode` SET `accountName` = '$name'  WHERE `accountant_code_Id` = $codeId";
 if(mysqli_query($link,$query)){
  include_once("aduLog.php");
  echo "Done";
 }
?>