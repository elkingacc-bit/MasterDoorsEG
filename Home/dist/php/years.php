<?php
 date_default_timezone_set("Africa/Cairo");
 $thisYear=date("Y");
 echo "<option value=''>Choose</option>";
 for ($i = 2022; $i <= $thisYear; $i++) { 
  echo "<option value='$i'> $i </option>";
 }
?>