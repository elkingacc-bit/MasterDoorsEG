<?php
 date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
 /*
 $sqlCustodyData="SELECT `empCode` , sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody` WHERE  `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
 while($custodyGetData=mysqli_fetch_assoc($queryCustodyData)){
  $empCode=$custodyGetData['empCode'];
  $sqlCustodyEmp="SELECT `empName` FROM `employees` WHERE `empId` = '$empCode' ";
  $queryEmpData=mysqli_query($link,$sqlCustodyEmp)or die("ERROR_SNSC : 01");
  $empGetData=mysqli_fetch_assoc($queryEmpData);
  $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
  echo"<li><button class='btn btn-link getEmpCustody' value='$empCode'>$empGetData[empName] :- $custody</button></li>";
 }
 */
 echo"<li><button class='btn btn-link getInvoiceData' value=''> </button></li>";
?>
<script type="text/javascript">
    /*
 $(".getEmpCustody").click(function(){
  var empName =$(this).val();
  $.ajax({
   url:'php/cheackWithdrawCustody.php',
   type:"POST",
   data:{fName:empName},
   success: function(custodyCheack){
    $(".showData").html(custodyCheack);
   }
  });
 }); 
 */
</script>
