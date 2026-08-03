<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $empCode=$_POST['femp'];
 $sqlCustodyData="SELECT `poNum`, sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody`  WHERE `empCode` = '$empCode' AND `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
 $custodyGetData=mysqli_fetch_assoc($queryCustodyData);
 $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
?>
<div  style="overflow-y: scroll;max-height:30vh;">	
 <table class='table table-sm table-bordered'>
  <thead>
   <th>SN</th>
   <th>Date</th>
   <th>Custdy</th>
   <th>Amount</th>
   <th>Project</th>
   <th>Discription</th>
  </thead>
  <tbody>
   <?php
    $sqlCustodyCashbachData="SELECT `custodyTransactionDate`,`amount`,`cashBack`,`poNum`,`discription` 
    FROM `custody`  
    WHERE `empCode` = '$empCode' AND `custodyRef` = 1 ORDER BY `custodyTransactionDate` ASC";
    $queryCustodyCashbachData=mysqli_query($link,$sqlCustodyCashbachData)or die("ERROR_SNSC : 02");
    if(mysqli_num_rows($queryCustodyCashbachData) > 0)
    {
     $serial=0;
     while($resultCustodyCashbachData=mysqli_fetch_assoc($queryCustodyCashbachData))
     {
      $serial++;
      echo"<tr>
       <td>$serial</td>
       <td>$resultCustodyCashbachData[custodyTransactionDate]</td>
       <td>$resultCustodyCashbachData[amount]</td>
       <td>$resultCustodyCashbachData[cashBack]</td>
       <td>$resultCustodyCashbachData[poNum]</td>
       <td>$resultCustodyCashbachData[discription]</td>
      </tr>";
     }
    }
   ?>
  </tbody>
 </table>
</div>
<hr>
<h3 style="color:blue">Remining :- <small> <strong><u style="color:red"><?php echo $custody; ?></u>L.E</strong></small></h3>