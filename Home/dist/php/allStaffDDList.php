<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetStaff="SELECT `id`, `staffname` FROM `allstaff`";
	$queryGetStaff=mysqli_query($link,$sqlGetStaff)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetStaff = mysqli_fetch_assoc($queryGetStaff))
	{
		
		echo "<option data-value='$resGetStaff[id]' value='$resGetStaff[staffname]'> ";
	}
}
else
{
	echo 9;
}
?>
