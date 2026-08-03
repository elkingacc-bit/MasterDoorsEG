<?php
 date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
 $sqlCustodyData="SELECT `empCode` , sum(`amount`) as cash,sum(`cashBack`) as comback FROM `custody` WHERE  `custodyRef` = 1";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_SNSC : 02");
 echo  mysqli_num_rows($queryCustodyData);
   
?>