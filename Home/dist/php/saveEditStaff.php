<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$Staff=$_POST['StfName'];
$Position=$_POST['StfPosition'];
$DayVAl=$_POST['dayAmount'];

$staffRowId = $_POST['sRowId'];
 
$sqlCheckNewStaff="SELECT `staffname` FROM `allstaff` WHERE `staffname` = '$Staff' AND `id` != '$staffRowId'";
$queryCheckStaff=mysqli_query($link,$sqlCheckNewStaff)or die("ERROR :01-CNS_ANS_S");

	if(mysqli_num_rows($queryCheckStaff) > 0)
	{
		echo 0;
	}
	
	else
	{
			
			$sqlEditStaff="UPDATE  `allstaff` SET `staffname` = '$Staff', `staffposition` = '$Position',
			`dayVal` = '$DayVAl' WHERE `id` = $staffRowId";
			mysqli_query($link,$sqlEditStaff)or die("ERROR :03-INC_ANS_I".mysqli_error($link));
					
			$action="Edit Staff date for :- $Staff";
			$logRef=3;
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