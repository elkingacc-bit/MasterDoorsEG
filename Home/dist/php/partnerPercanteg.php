<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $sqlPartnerPer = "SELECT sum(`partnerPercentage`) as totalPer FROM `partnershipRatio`";
 $queryPartnerPer = mysqli_query($link,$sqlPartnerPer)or die("ERROR :01-ANJ_GCN_S");
 $resPartnerPer = mysqli_fetch_assoc($queryPartnerPer);
 $allPercanteg=$resPartnerPer['totalPer'];
 $avalibalePer=(100 - $allPercanteg);
 echo "<option value=''>Choose</option>";
 for ($i=1; $i <= $avalibalePer ; $i++) { 
  echo "<option value='$i'>$i</option>";
 }
?>