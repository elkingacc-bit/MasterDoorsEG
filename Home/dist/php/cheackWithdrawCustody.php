<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $empCode=$_POST['fName'];
 $result= "";
 //Cheak Entry
 // `custodyRef` = 1 : Open
 // `custodyRef` = 2 : Close
 $sqlCustodyTransaction="SELECT `custodyTransactionDate`,`poNum`,`discription`,`amount`,`cashBack` FROM `custody` WHERE `empCode` = '$empCode' AND `custodyRef` = 1";
 $queryCustodyTransaction=mysqli_query($link,$sqlCustodyTransaction)or die("ERROR_SNSC : 01");
 $custodyTransaction=mysqli_num_rows($queryCustodyTransaction); 
 if($custodyTransaction > 0){
  $sqlCustodyData="SELECT sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody`  WHERE `empCode` = '$empCode' AND `custodyRef` = 1";
  $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
  $custodyGetData=mysqli_fetch_assoc($queryCustodyData);
  $custody=($custodyGetData['cash'] - $custodyGetData['comback']);
  
  $sqlCustodyEmp="SELECT `id`,`staffname` FROM `allstaff` WHERE `id` = $empCode";
  $queryEmpData=mysqli_query($link,$sqlCustodyEmp)or die("ERROR_SNSC : 01");
  if(mysqli_num_rows($queryEmpData) > 0 ){
   $empGetData=mysqli_fetch_assoc($queryEmpData);
   $name=$empGetData['staffname'];
  }
  else{
   $sqlFirstLevelData="SELECT `fullname` FROM `users` WHERE `userid` = $empCode";
   $queryFirstLevelData=mysqli_query($link,$sqlFirstLevelData)or die("ERROR LOA_S:02");
   $firstLevel=mysqli_fetch_assoc($queryFirstLevelData);
   $name=$firstLevel['fullname'];
  }
  echo " <h5 style='text-align: left;'><b>$name</b> Custody Summary</h5>";
  echo"<table class='table table-primary table-bordered border-dark'>
   <thead>
    <th>Po</th>
    <th>Date</th>
    <th>Amount</th>
    <th>cashback</th>
    <th>Discription</th>
   </thead>
   <tbody>
  ";
  while($custodyData=mysqli_fetch_assoc($queryCustodyTransaction)){
   $duedate = date("d/m/Y",strtotime($custodyData['custodyTransactionDate']));
   if($custodyData['poNum'] == 0){
    $project="General Custody";
   }
   else{
    $project=$custodyData['poNum'];
   }
   echo"<tr>
    <td>$project</td>
    <td>".$duedate."</td>
    <td>$custodyData[amount]</td>
    <td>$custodyData[cashBack]</td>
    <td>$custodyData[discription]</td>
   </tr>";
  }
  echo"</tbody>
   </table>
   <hr>
   <h5 style='text-align: right;'> Remining :- <b>$custody</b>L.E </h5>
  ";
 }
 else{
  echo 3;
 }
?>