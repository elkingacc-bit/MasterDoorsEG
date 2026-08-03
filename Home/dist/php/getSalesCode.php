<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
$sqlGetSalesName="SELECT `codeId`, `username` FROM `users` WHERE (`department` = 'Sales' AND `fullname` != 'administrator') OR (`department` = 'Admin' AND `fullname` != 'administrator')";
	$queryGetSalesName=mysqli_query($link,$sqlGetSalesName)or die("ERROR :01-GCA_CAPN_S");
	while($resultGetSalesName=mysqli_fetch_array($queryGetSalesName))
	{
		echo "
				
				<option data-value='$resultGetSalesName[0]' value='$resultGetSalesName[1]'>
			";
	}	
?>