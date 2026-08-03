<?php
include_once("connection.php");
 $oldcode=$_POST['rowNum'];
 $sqlAllcodeEdit="SELECT `accountName` FROM `accountantcode` WHERE `accountant_code_Id` = $oldcode";  
 $dateAllcodeEdit=mysqli_query($link,$sqlAllcodeEdit);
 $getData=mysqli_fetch_assoc($dateAllcodeEdit);
 echo"<form class='form-inline'>
  <div class='form-group'>
   <label for='accountantName'>Account Name</label>
   <input type='text' class='form-control' id='accountantName' value='$getData[accountName]'>
  </div>
  <button type='submit' class='btn btn-default' value='$oldcode' id='saveEdite' style='display: none;'>Save invitation</button>  
 </form>";
?>