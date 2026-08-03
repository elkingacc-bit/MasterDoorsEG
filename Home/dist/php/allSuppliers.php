<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 echo "<option value=''>Choose</option>";
 $sqlGetSupplier="SELECT `suppliername`, `suppliercode`, `suppcountry` FROM `allsuppliers`";
 $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
 while($resGetSupplier = mysqli_fetch_assoc($queryGetSupplier)){
  echo "<option value='$resGetSupplier[suppliercode]'> $resGetSupplier[suppliername]";
}
?>
