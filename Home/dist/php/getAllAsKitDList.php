<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
		
	$sqlGetAllKitName="SELECT `id`, `kitName` FROM `assemblykits` ORDER BY `kitName` ASC";
	$queryGetAllKitName=mysqli_query($link,$sqlGetAllKitName)or die("ERROR :01-AU_AU_S");
	while($resGetAllKitName = mysqli_fetch_assoc($queryGetAllKitName))
	{
		
		echo "<option data-value='$resGetAllKitName[id]' value='$resGetAllKitName[kitName]'>";
	}
}
else
{
	echo 9;
}
?>
