<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $empCode=$_POST['frecipient'];
 $sqlCustodyEmp="SELECT `fullname` FROM `users` WHERE `userid` = '$empCode' ";
 $queryEmpData=mysqli_query($link,$sqlCustodyEmp)or die("ERROR_SNSC : 01");
 $empGetData=mysqli_fetch_assoc($queryEmpData);
 $sqlCustodyData="SELECT sum(`amount`) as cash,sum(`cashBack`) as comback ,`poNum` FROM `custody`  WHERE `empCode` = '$empCode' AND `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
 $custodyGetData=mysqli_fetch_assoc($queryCustodyData);
 $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
 $poNumber=$custodyGetData['poNum'];
 $sqlProjectName="SELECT `projectName` FROM `job` WHERE `jobId` = $poNumber";
 $quaryProjectName=mysqli_query($link,$sqlProjectName)or die("ERROR LOA_S:01");
 $projectName=mysqli_fetch_assoc($quaryProjectName);
 if($poNumber == 0)
 {
  $projectSelect="<select class='form-control projectName' id='projectName'></select>";
 }
 else
 {
  $projectSelect="<select class='form-control' id='projectName'><option value='$poNumber'>$projectName[projectName]</option></select>";
 }
 echo"
  <input id='projectsCustody' value='$poNumber' hidden>
  <input id='cashbackCustodyRecipien' value='$empCode' hidden>
 ";
 if($custody > 0)
 {
  echo"
   <h3>Custody :- <small> <strong><u> $empGetData[fullname]</u></strong> </small> Cashback</h3>
   <input id='totalCustody' value='$custody' hidden>
    <div class='table-responsive-lg'>
   <table class='table table-sm table-primary table-bordered' id='tableCustody'>
    <thead class='text-center'>
     <th class='col-1'>Date</th>
     <th class='col-1'>Amount</th>
     <th class='col-2'>Project</th>
     <th class='col-3'>Account</th>
     <th class='col-5'>Discription</th>
    </thead>
    <tbody>
     <tr>
      <td><input class='form-control' type='date' id='date'></td>
      <td><input class='form-control' type='number' step='0.01' id='amount' min='1.00'></td>
      <td>$projectSelect</td>
      <td><select class='form-control' id='acountant'></select></td>
      <td><input class='form-control' id='dis'></td>
     </tr>
    </tbody>
   </table>
   </div>
   <hr>
   <center>
    <button class='btn btn-info' id='saveCustodyCashback'>Save</button>
   </center>
   <hr>
   <div class='cashBackData'></div>
  ";
 }
 else
 {
  echo"
   <h3> <strong>$empGetData[fullname]</strong> Not Have Custody</h3>
  ";
 }
?>
<script type="text/javascript">
 $(document).ready(function(){ 
  var emp = $("#cashbackCustodyRecipien").val();
  $.ajax({ 
   url:'dist/php/cashbackdetils.php',
   type:"POST",
   data:{femp:emp},
   success: function(cashbackCustodyDetils)
   {
    $('.cashBackData').html(cashbackCustodyDetils);
   }
  });
  //
  $("#acountant").load("dist/php/allcodeCashback.php");
  $(".projectName").load("dist/php/projectsList.php");
  //
  $("#amount").change(function(){
   var custodyAmount=$("#totalCustody").val();
   var nAmount=$(this).val();  
   if(nAmount > custodyAmount)
   {
    $(".msg").html("Amount Over Than Custody ");
    $("#amount").focus();
   }
  });
  // Save Data
  $("#saveCustodyCashback").click(function(){
   var PONum = $('#projectName').val();
   var recipient = $("#cashbackCustodyRecipien").val();
   var lastDate = $("#date").val();
   var lastAmount = $("#amount").val();
   var lastAccount = $("#acountant").val();
   var lastDiscrebtion = $("#dis").val();
   if(lastDate == '')
   {
    alert ("Do'not Leave Date Blank.");
   }
   else if(lastAmount == '')
   {
    alert ("Do'not Leave Amount Blank.");
   }
   else if(lastAccount == '')
   {
    alert ("Do'not Leave Account Blank.");
   }
   else if(lastDiscrebtion == '')
   {
    alert ("Do'not Leave Discreption Blank.");
   }
   else
   {
    $.ajax({ 
     url:'dist/php/saveNewCashbackCustody.php',
     type:"POST",
     data:{fDate:lastDate,frecipient:recipient,famount:lastAmount,fdiscrebtion:lastDiscrebtion,poNumber:PONum,fAccount:lastAccount},
     beforeSend:function()
     {
      $('#saveCustodyCashback').prop('disabled', true);
     },
     
     success:function(saveCashbackCustody)
     {
     
      if(saveCashbackCustody == 1)
      {
       alert("Data Saved");
       $("#date").val("");
       $("#amount").val("");
       $("#acountant").load("dist/php/allcodeCashback.php");
       $("#projectName").load("dist/php/projectsList.php");
       $("#dis").val("");
       
       $.ajax({ 
        url:'dist/php/cashbackdetils.php',
        type:"POST",
        data:{femp:emp},
        
        success: function(cashbackCustodyDetils2)
        {
         $('.cashBackData').html(cashbackCustodyDetils2);
        }
       
       });
       
       $('#saveCustodyCashback').prop('disabled', false);
      }
      else
      {
       alert(saveCashbackCustody);
      }
     }
    });
   }
  });
  return false;
 });
</script>