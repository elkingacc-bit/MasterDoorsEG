<?php

@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	echo "<option value='test'>";
	$sqlGetGroup="SELECT `typeName` FROM `whtype` WHERE `typeRef` = 'Group' ORDER BY `typeName` ASC";
	$queryGetGroup=mysqli_query($link,$sqlGetGroup)or die("ERROR :01-AU_AU_S");
	while($resGetGroup = mysqli_fetch_assoc($queryGetGroup))
	{
		echo "<option value='$resGetGroup[typeName]'>";
	}


}
else
{
	echo 9;
}
?>