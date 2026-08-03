<?php
  include_once("authCheck.php");
  include_once("connection.php");
 $oldcode=(int)$_POST['codeNum'];
 $query="DELETE FROM `accountantcode` WHERE `accountant_code_Id` = $oldcode ";
 if($dell=mysqli_query($link,$query)){
  echo 1;    
 }
?>