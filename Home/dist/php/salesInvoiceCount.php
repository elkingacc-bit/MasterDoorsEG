<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlMakeInv="SELECT `jobId`,`jobtype` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No'";
 $queryMakeInv=mysqli_query($link,$sqlMakeInv)or die("ERROR_SNSC : 02");
 echo mysqli_num_rows($queryMakeInv);
?>