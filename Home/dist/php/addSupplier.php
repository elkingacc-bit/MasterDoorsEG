<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$manuCode=$_POST['manufctuerCode'];
$newSuppl=$_POST['Supplier'];
$supType=$_POST['spplierType'];
$suppCounter=$_POST['Country'];
$firstSCode=11;
$forgin=2;
$local=1;

if($supType == 1 )
{
	$SuppT = $manuCode.$local;
}
else if($supType == 2)
{
	$SuppT = $manuCode.$forgin;
}
 
$sqlCheckNewSuppl="SELECT `codename` FROM `allcode` WHERE `codename` = '$newSuppl' AND `code` LIKE '$manuCode%'";
$queryCheckSuppl=mysqli_query($link,$sqlCheckNewSuppl)or die("ERROR :01-CNS_ANS_S");

if(mysqli_num_rows($queryCheckSuppl) > 0)
{
	echo 0;
}

else
{
	$getNewCode="SELECT `code` FROM `allcode` WHERE `code` LIKE('$SuppT%') ORDER BY `code` DESC LIMIT 1";
	$queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :02-GNC_ANM_S");
	$resNewCode=mysqli_fetch_assoc($queryNewCode);
	if(mysqli_num_rows($queryNewCode) == 0)
	{
		$SupplCode=$SuppT.$firstSCode;
	}
	else
	{
		
		$SupplCode=($resNewCode['code']+1);
	}	
		$insertNewCode="INSERT INTO `allcode` (`codename`, `code`) VALUES ('$newSuppl', $SupplCode)";
		mysqli_query($link,$insertNewCode)or die("ERROR :03-INC_ANS_I".mysqli_error($link));
		
		$insertNewSupplier="INSERT INTO `allsuppliers` (`suppliercode`, `suppliername`, `suppcountry`) 
		VALUES ($SupplCode, '$newSuppl', '$suppCounter')";
		mysqli_query($link,$insertNewSupplier)or die("ERROR :04-INC_ANS_I".mysqli_error($link));
		
		$action="Add New Supplier- $newSuppl";
		$logRef=2;
		include_once("aduLog.php");
		echo 1;
		exit();
}

?>