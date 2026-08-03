<?php
  include_once("connection.php");
 $oldcode=$_POST['codeNum'];
 $query="DELETE FROM `accountantcode` WHERE `accountant_code_Id` = $oldcode ";            
 if($dell=mysqli_query($link,$query)){
  echo 1;    
 }
?>