<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$priceType=$_POST['pType'];
$M2Price=$_POST['m2PriceAmount'];

$sqlCheckname = "SELECT `pricetype` FROM `pricing` WHERE `pricetype` = '$priceType'";
$queryCheckname = mysqli_query($link,$sqlCheckname)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckname) > 0)
	{
		$sqlUpdatePrice = "UPDATE `pricing` SET `priceval` = '$M2Price' WHERE `pricetype` = '$priceType'";
		mysqli_query($link,$sqlUpdatePrice)or die("ERROR :02-CNS_ANS_S");
		
		$action="Edit M2Price = $M2Price for door type $priceType";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();
	}
	else
	{
		$sqlAddNewPrice = "INSERT INTO `pricing` (`pricetype`, `priceval`) VALUES ('$priceType', '$M2Price')";
		mysqli_query($link,$sqlAddNewPrice)or die("ERROR :02-CNS_ANS_S"); 
			
		$action="Add New M2Price = $M2Price for door type $priceType";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();
	}
}
else
{
	echo 9;
	exit();
}
?>