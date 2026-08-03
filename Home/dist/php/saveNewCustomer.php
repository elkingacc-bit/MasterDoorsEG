<?php
date_default_timezone_set("Africa/Cairo");

include_once("connection.php");
$a1=$_POST['name'];
$a3=$_POST['Area'];
$a10=$_POST['activityCust'];
$action="Add New Customer $a1";
$logRef=1;
$sqlCheckNewCust="SELECT `customername`,`area` FROM `customers` WHERE `customername`='$a1' AND `area`='$a3'";
$queryCheckCust=mysqli_query($link,$sqlCheckNewCust)or die("ERROR :01-CNC_AC_S");
if(mysqli_num_rows($queryCheckCust) > 0)
{
  echo 0;
}
else
{
 $getNewCode="SELECT `customercode` FROM `customers` WHERE length(`customercode`)= 9 ORDER BY `customercode` DESC LIMIT 1";
 $queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :02-GNC_AC_S");
 $resNewCode=mysqli_fetch_assoc($queryNewCode);
 if(mysqli_num_rows($queryNewCode) == 0){
  $customerCode=114100101;
 }
 else
 {
  $customerCode=($resNewCode['customercode']+1);
 }
 $insertNewCust="INSERT INTO`customers`(`customername`,`customercode`, `activity`, `area`)
   VALUES ('$a1',$customerCode, '$a10', '$a3')";
 mysqli_query($link,$insertNewCust)or die("ERROR :03-INC_AC_I".mysqli_error($link));
 
  $insertAllCode="INSERT INTO`allcode`(`codename`,`code`)
   VALUES ('$a1',$customerCode)";
 mysqli_query($link,$insertAllCode)or die("ERROR :04-INC_AC_I".mysqli_error($link));
 

 include_once("aduLog.php");
 echo 1;
 exit();
}
?>
