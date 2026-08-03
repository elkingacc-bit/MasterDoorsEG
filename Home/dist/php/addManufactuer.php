<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
$TypeM=$_POST['RegionM'];
$newManuf=$_POST['ManfName'];
$cuntoryManuf=$_POST['selectCountry'];
$ManufTy=$_POST['TypeM'];
$firstMCode=11;
if($TypeM == 1 )
{
	$MaunfT = 2811;
}
else if($TypeM == 2)
{
	$MaunfT = 2812;
}

$sqlCheckNewManuf="SELECT `subcategory` FROM `allcode` WHERE `subcategoryname` = '$newManuf'";
$queryCheckManuf=mysqli_query($link,$sqlCheckNewManuf)or die("ERROR :01-CNM_ANM_S");

if(mysqli_num_rows($queryCheckManuf) > 0)
{
	echo 0;
}

else
{
	$getNewCode="SELECT `subcategory` FROM `allcode` WHERE `subcategory` LIKE('$MaunfT%') 
	AND LENGTH(`subcategory`) = 6 ORDER BY `subcategory` DESC LIMIT 1";
	$queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :02-GNC_ANM_S");
	$resNewCode=mysqli_fetch_assoc($queryNewCode);
	if(mysqli_num_rows($queryNewCode) == 0)
	{
		$ManufCode=$MaunfT.$firstMCode;
	}
	else
	{
		
		$ManufCode=($resNewCode['subcategory']+1);
	}	
		$insertNewCode="INSERT INTO `allcode` (`subcategoryname`, `subcategory`) 
		VALUES ('$newManuf', $ManufCode)";
		mysqli_query($link,$insertNewCode)or die("ERROR :03-INC_AMN_I");
		
		$insertNewManuf="INSERT INTO `allmanufactuers`(`manufactuername`, `manufactuercode`, `country`, 
		`manuftype`) VALUES ('$newManuf', $ManufCode, '$cuntoryManuf', '$ManufTy')";
		mysqli_query($link,$insertNewManuf)or die("ERROR :04-INC_AMN_I");
		
		
		if($ManufTy == 'Doors' || $ManufTy == 'Automatic')
		{ 
			$firstSCode=11;
			$local=1;
			$SuppT = $ManufCode.$local;
			$SupplCode=$SuppT.$firstSCode;
			$newSuppl = $newManuf;
			$suppCounter = $cuntoryManuf;
			
			$insertNewCode="INSERT INTO `allcode` (`codename`, `code`) VALUES ('$newSuppl', $SupplCode)";
			mysqli_query($link,$insertNewCode)or die("ERROR :03-INC_ANS_I".mysqli_error($link));
			
			$insertNewSupplier="INSERT INTO `allsuppliers` (`suppliercode`, `suppliername`, `suppcountry`, 
			`suppType`)	VALUES ($SupplCode, '$newSuppl', '$suppCounter', '$ManufTy')";
			mysqli_query($link,$insertNewSupplier)or die("ERROR :04-INC_ANS_I".mysqli_error($link));
			
			$action="Add New Manufacturer- $newManuf Type is: $ManufTy And Same date For Supplier";
			$logRef=2;
			include_once("aduLog.php");
			echo 1;
			exit();	
		}
		else
		{
			$action="Add New Manufacturer- $newManuf Type is: $ManufTy";
			$logRef=2;
			include_once("aduLog.php");
			echo 1;
			exit();	
		}
}

?>