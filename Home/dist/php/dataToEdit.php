<?php
include_once("authCheck.php");
include_once("connection.php");
 $oldcode=(int)$_POST['rowNum'];
 $sqlAllcodeEdit="SELECT `accountName` FROM `accountantcode` WHERE `accountant_code_Id` = $oldcode";
 $dateAllcodeEdit=mysqli_query($link,$sqlAllcodeEdit);
 $getData=mysqli_fetch_assoc($dateAllcodeEdit);
 $safeAccountName=htmlspecialchars($getData['accountName'], ENT_QUOTES);
 echo"<form class='form-inline'>
  <div class='form-group'>
   <label for='accountantName'>Account Name</label>
   <input type='text' class='form-control' id='accountantName' value='$safeAccountName'>
  </div>
  <button type='submit' class='btn btn-default' value='$oldcode' id='saveEdite' style='display: none;'>Save invitation</button>
 </form>";
?>