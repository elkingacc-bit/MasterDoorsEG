<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 	$sqlCheckOldRef="SELECT `PoNum` FROM `customerpo` WHERE `PoNum` LIKE('CMS LPO-%') 
	ORDER BY lpad(`PoNum`, 100, 0) DESC LIMIT 1";
	$queryCheckOldRef=mysqli_query($link,$sqlCheckOldRef)or die("ERROR :01-GNLPR_COR_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckOldRef) == 0)
	{
		$LocalPR = 'CMS LPO-1';
		
		echo $LocalPR ;
	}
	else
	{
		$resCheckOldRef = mysqli_fetch_assoc($queryCheckOldRef);
		$currntPR = $resCheckOldRef['PoNum'];
		$new = 1;
		$PRNum=substr($currntPR, strrpos($currntPR, '-') + 1);
		
		$newPR = ($PRNum + $new);
		$newFullPR = "CMS LPO-".$newPR;
		
		 echo trim($newFullPR);
	}
	
 ?>
