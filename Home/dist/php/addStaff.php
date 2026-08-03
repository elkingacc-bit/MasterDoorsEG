<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
//echo "tst -> ";

$Staff=$_POST['StfName'];
$Position=$_POST['StfPosition'];
$DayVal=$_POST['dayAmount'];
 
$sqlCheckNewStaff="SELECT `staffname` FROM `allstaff` WHERE `staffname` = '$Staff' AND `staffposition` 
= '$Position'";
$queryCheckStaff=mysqli_query($link,$sqlCheckNewStaff)or die("ERROR :01-CNS_ANS_S");

	if(mysqli_num_rows($queryCheckStaff) > 0)
	{
		echo 0;
	}
	
	else
	{
			
			$insertNewStaff="INSERT INTO `allstaff` (`staffname`, `staffposition`, `dayVal`) 
			VALUES ('$Staff', '$Position', '$DayVal')";
			mysqli_query($link,$insertNewStaff)or die("ERROR :03-INC_ANS_I".mysqli_error($link));
					
			$action="Add New Staff - $Staff";
			$logRef=2;
			include_once("aduLog.php");
			echo 1;
			exit();
	}

}
else
{
	echo 9;
}
?>