<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
  
 $PoRowID = $_POST['PORIDIC'];
 $ref =  $_POST['Ref'];
 
 $sqlGetCustPO="SELECT  `PoNum` FROM `customerpo` WHERE `poId` = $PoRowID";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :01-AU_AU_S");
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
	
	if( $ref == 1)
	{
		$sqlUpdatePOComssion = "UPDATE `customerpo` SET `InstallationComsion` = '.01' WHERE 
		`poId` = $PoRowID";
		$update = 'Add Installation Comission';
	}
	else if( $ref == 2)
	{
		$sqlUpdatePOComssion = "UPDATE `customerpo` SET `InstallationComsion` = '0' WHERE 
		`poId` = $PoRowID";
		$update = 'Remove Installation Comission';
	}
	mysqli_query($link,$sqlUpdatePOComssion)or die("ERROR :01-AU_AU_S");
 
 
 	$action="Update Order Number $resGetCustPO[PoNum] with $update";
	$logRef=5;	
	include_once("aduLog.php");
			
			echo 1;
			exit();	
 
 ?>