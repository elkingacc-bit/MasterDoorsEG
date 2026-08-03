<?php
 @session_start();
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 if(!empty($_SESSION['username'])){
  $sqlGetSupplier="SELECT `customername`,`customercode`,`area`  FROM `customers` ORDER BY `customername` ASC";
  $queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
  while($resGetSupplier = mysqli_fetch_assoc($queryGetSupplier)){
   echo "<option data-value='$resGetSupplier[customercode]' value='$resGetSupplier[customername]'>$resGetSupplier[area]";
  }
 }
 else{
  echo 9;
 }
?>