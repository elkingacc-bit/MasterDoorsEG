<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $getAllItemCode="SELECT `description`, `descriptionname` FROM `stockitems` GROUP BY `description`";
 $queryAllItemCode=mysqli_query($link,$getAllItemCode)or die("ERROR :01-AIC_AIDL_S");
 echo "<option value=''>Choose</option>";
 while($resAllItemCode=mysqli_fetch_assoc($queryAllItemCode)){
  echo "<option value='$resAllItemCode[description]'>$resAllItemCode[descriptionname]</option>";
 }
?>