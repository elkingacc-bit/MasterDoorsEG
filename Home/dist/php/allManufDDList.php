<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetManufactuers="SELECT `manufactuername`, `manufactuercode` FROM `allmanufactuers`";
	$queryGetManufactuers=mysqli_query($link,$sqlGetManufactuers)or 
	die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetManufactuers = mysqli_fetch_assoc($queryGetManufactuers))
	{
		
		echo "<option data-value='$resGetManufactuers[manufactuercode]' 
			  value='$resGetManufactuers[manufactuername]'> ";
	}
}
else
{
	echo 9;
}
?>
