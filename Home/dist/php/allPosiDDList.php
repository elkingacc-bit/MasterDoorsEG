<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetAllStaff="SELECT `staffposition` FROM `allstaff` GROUP BY `staffposition`"; 
	$queryGetAllStaff=mysqli_query($link,$sqlGetAllStaff)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	if(mysqli_num_rows($queryGetAllStaff) > 0)
	{
		while($resGetAllStaff = mysqli_fetch_assoc($queryGetAllStaff))
		{
		
			
			echo "<option value='$resGetAllStaff[staffposition]'>";
		}
	}
}
else
{
	echo 9;
}
?>
