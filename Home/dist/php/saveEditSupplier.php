<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$manuCode=$_POST['manufctuerCode'];
$newSuppl=$_POST['Supplier'];
$supType=$_POST['spplierType'];
$suppCounter=$_POST['Country'];
$suppRowID=$_POST['SuppTID'];
$allCodeRowId =$_POST['ACodeRID'];
$firstSCode=11;
$forgin=2;
$local=1;

	$sqlGetAllCode="SELECT `code`, `codename`, MID(`code`, 7, 1) AS SuppType, MID(`code`, 1, 6) AS ManuCode 
	FROM `allcode` WHERE `allcodeid` = $allCodeRowId ";
	$queryGetAllCode=mysqli_query($link,$sqlGetAllCode)or die("ERROR :01-AU_AU_S");
	$resGetAllCode = mysqli_fetch_assoc($queryGetAllCode);
	$suppOldCode = $resGetAllCode['code'];
	$manufOldCode = $resGetAllCode['ManuCode'];
	$suppOldType = $resGetAllCode['SuppType'];
	$suppOldName = $resGetAllCode['codename'];
	
	if($manuCode == $manufOldCode)
	{
		$useMCode = $manufOldCode;
		if($supType == 1 )
			{
				$SuppT = $useMCode.$local;
			}
		else if($supType == 2)
			{
				$SuppT = $useMCode.$forgin;
			}
	}
	else
	{
		$useMCode = $manuCode;
		if($supType == 1 )
			{
				$SuppT = $useMCode.$local;
			}
		else if($supType == 2)
			{
				$SuppT = $useMCode.$forgin;
			}
	}
 
$sqlCheckNewSuppl="SELECT `codename` FROM `allcode` WHERE `codename` = '$newSuppl' AND `allcodeid` 
!= $allCodeRowId";
$queryCheckSuppl=mysqli_query($link,$sqlCheckNewSuppl)or die("ERROR :01-CNS_ANS_S");

	if(mysqli_num_rows($queryCheckSuppl) > 0)
	{
		echo 0;
	}

	else
	{
		 

		if($supType == $suppOldType && $manuCode == $manufOldCode)
		{
			$SupplCode = $suppOldCode;
		}
		else
		{
		 
			$getNewCode="SELECT `code` FROM `allcode` WHERE `code` LIKE('$SuppT%') ORDER BY `code` 
			DESC LIMIT 1";
			$queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :02-GNC_ANM_S");
			if(mysqli_num_rows($queryNewCode) == 0)
			{
				$SupplCode=$SuppT.$firstSCode;
			}
			else
			{
				$resNewCode=mysqli_fetch_assoc($queryNewCode);
				$SupplCode=($resNewCode['code']+1);
			}	

	}

		$sqlUpdateNewCode="UPDATE `allcode` SET `codename` = '$newSuppl', `code` = $SupplCode WHERE 
		 `allcodeid` = $allCodeRowId ";
		mysqli_query($link,$sqlUpdateNewCode)or die("ERROR :03-INC_ANS_I".mysqli_error($link));
		
		$sqlUpdateSupplier="UPDATE `allsuppliers` SET `suppliercode` = $SupplCode , `suppliername` = 
		'$newSuppl',  `suppcountry` = '$suppCounter' WHERE `id` = $suppRowID";
		mysqli_query($link,$sqlUpdateSupplier)or die("ERROR :04-INC_ANS_I".mysqli_error($link));
		
		$updateWarehouse="UPDATE `warehouse` SET `supplier` = $SupplCode
		WHERE `supplier` = $suppOldCode";
		mysqli_query($link,$updateWarehouse)or die("ERROR :05-INC_AMN_I");
		
		$sqlUpdateLookUp="UPDATE `lookupstock` SET `supplier` = '$newSuppl' 
		WHERE `supplier` = '$suppOldName'";
		mysqli_query($link,$sqlUpdateLookUp)or die("ERROR :06-INC_AMN_I");
		
		$action="Edit Supplier old code $suppOldCode to - $newSuppl";
		$logRef=3;
		include_once("aduLog.php");
		echo 1;
		exit();
	}
}//session
else
{
	echo 9;
	exit();	
}

?>