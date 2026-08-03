<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetSupplier="SELECT `suppliername`, `suppliercode`, `suppcountry`, `suppType` FROM `allsuppliers` 
	WHERE (`suppType` = 'Doors') OR (`suppType` = 'Automatic')";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	while($resGetSupplier = mysqli_fetch_assoc($queryGetSupplier))
	{
	
		echo "<option data-value='$resGetSupplier[suppliercode]' 
				value='$resGetSupplier[suppliername]'> $resGetSupplier[suppType]";
	}
}
else
{
	echo 9;
}
//SELECT `id`, `linename`, `factorynum` FROM `alllines` ORDER BY `factorynum`, `linename` ASC
?>
