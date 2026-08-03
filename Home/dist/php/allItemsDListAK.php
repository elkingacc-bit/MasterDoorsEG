<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	

	$sqlGetItems="SELECT `descriptionname`, `description` FROM `stockitems` WHERE `description` IS NOT NULL
	 ORDER BY `descriptionname` ASC";
	$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetItems= mysqli_fetch_assoc($queryGetItems))
	{
			echo "<option data-value='$resGetItems[description]' value='$resGetItems[descriptionname]'> ";
	}
}
?>
