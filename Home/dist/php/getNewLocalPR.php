<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 	$sqlCheckOldRef="SELECT `localref` FROM `job` WHERE `localref` LIKE 'CMS_LPR-%' 
	ORDER BY lpad(`localref`, 100, 0) DESC LIMIT 1";
	$queryCheckOldRef=mysqli_query($link,$sqlCheckOldRef)or die("ERROR :01-GNLPR_COR_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckOldRef) == 0)
	{
		$LocalPR = 'CMS_LPR-1';
		
		echo $LocalPR ;
	}
	else
	{
		$resCheckOldRef = mysqli_fetch_assoc($queryCheckOldRef);
		$currntPR = $resCheckOldRef['localref'];
		$new = 1;
		$PRNum=substr($currntPR, strrpos($currntPR, '-') + 1);
		
		$newPR = ($PRNum + $new);
		$newFullPR = "CMS_LPR-".$newPR;
		
		 echo trim($newFullPR);
	}
	
 ?>
