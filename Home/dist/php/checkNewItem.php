<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");


	$NewDesc = $_POST['newDescrip'];
	
	if(!empty($NewDesc))
	{
		$sqlCheckDescrip = "SELECT `descriptionname` FROM `stockitems` WHERE `descriptionname` = '$NewDesc'";
		$queryCheckDescrip=mysqli_query($link,$sqlCheckDescrip)or die("ERROR :01-CNI_CD_S");
		if(mysqli_num_rows($queryCheckDescrip) > 0)
		{
			echo 0;
		}
		else
		{
			echo 1;
		}
		
	}

?>