<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{

	$sqlGetPartNum="SELECT `description`, `partnumber` FROM `stockitems` GROUP BY `partnumber`";
	$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetPartNum= mysqli_fetch_assoc($queryGetPartNum))
	{
		
			echo "<option data-value='$resGetPartNum[description]' value='$resGetPartNum[partnumber]'> ";
	}
}
?>
