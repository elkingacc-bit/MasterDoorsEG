<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
//echo "test -> ";
$TypeM=$_POST['TypeM'];
$newManuf=$_POST['ManfName'];
$cuntoryManuf=$_POST['selectCountry'];
$rowIdManuf = $_POST['MRID'];
$ManufactuerCode = $_POST['mCode'];
$ManufactuerTypeName = $_POST['manufTypeName'];

	$sqlGetAllCode="SELECT `allcodeid`, `subcategoryname` FROM `allcode` WHERE `subcategory`
	 = $ManufactuerCode";
	$queryGetAllCode=mysqli_query($link,$sqlGetAllCode)or die("ERROR :01-AU_AU_S");
	$resGetAllCode = mysqli_fetch_assoc($queryGetAllCode);
	$allCodeRID = $resGetAllCode['allcodeid'];
	$manufOldName = $resGetAllCode['subcategoryname'];

$firstMCode=11;
if($TypeM == 1 )
{
	$MaunfT = 2811;
}
else if($TypeM == 2)
{
	$MaunfT = 2812;
}

//$ManufOldCode = substr($ManufactuerCode, 0, 6);

$ManufOldCodeType = substr($ManufactuerCode, 0, 4);
$ManufOldType = substr($ManufOldCodeType, 3, 4);


$sqlCheckNewManuf="SELECT `subcategory` FROM `allcode` WHERE `subcategoryname` = '$newManuf' AND 
`allcodeid` != $allCodeRID";
$queryCheckManuf=mysqli_query($link,$sqlCheckNewManuf)or die("ERROR :02-CNM_ANM_S");


if(mysqli_num_rows($queryCheckManuf) > 0)
{
	echo 0;
}

else
   {
	if($TypeM == $ManufOldType)
	{
		$ManufCode = $ManufactuerCode;
	}
	else
	{
		$getNewCode="SELECT `subcategory` FROM `allcode` WHERE `subcategory` LIKE('$MaunfT%') 
		AND LENGTH(`subcategory`) = 6 ORDER BY `subcategory` DESC LIMIT 1";
		$queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :03-GNC_ANM_S");
		
		if(mysqli_num_rows($queryNewCode) == 0)
		{
			$ManufCode=$MaunfT.$firstMCode;
		}
		else
		{
			$resNewCode=mysqli_fetch_assoc($queryNewCode);
			$ManufCode=($resNewCode['subcategory']+1);
		}
		
		$sqlGetSuppCode="SELECT `code` FROM `allcode` WHERE `code` LIKE('$ManufactuerCode%')
		AND `code` IS NOT NULL";
		$querySuppCode=mysqli_query($link,$sqlGetSuppCode)or die("ERROR :04-GNC_ANM_S");
		if(mysqli_num_rows($querySuppCode) > 0)
		{
			while($resSuppCode=mysqli_fetch_assoc($querySuppCode))
			{
			 $suppCode = $resSuppCode['code'];
			 $suppOldCode = substr($suppCode, 6, 9);
			 $newSuppCode = $ManufCode.$suppOldCode;
			
			$upadteSuppCode="UPDATE `allcode` SET `code` = $newSuppCode WHERE `code` = $suppCode";
			mysqli_query($link,$upadteSuppCode)or die("ERROR :04-INC_AMN_I");
			
			$upadteSupp="UPDATE `allsuppliers` SET `suppliercode` = $newSuppCode 	
			WHERE `suppliercode` = $suppCode";
			mysqli_query($link,$upadteSupp)or die("ERROR :04-INC_AMN_I");
			
			$updateWarehouse="UPDATE `warehouse` SET `supplier` = $newSuppCode
			WHERE `supplier` = $suppCode";
			mysqli_query($link,$updateWarehouse)or die("ERROR :06-INC_AMN_I");
			
			
			
			}
		}
		
	}
	
	
		
		$upadteNewCode="UPDATE `allcode` SET `subcategoryname` = '$newManuf', `subcategory` = $ManufCode 
		WHERE `allcodeid` = $allCodeRID";
		mysqli_query($link,$upadteNewCode)or die("ERROR :04-INC_AMN_I");
		
		$updateNewManuf="UPDATE `allmanufactuers` SET `manufactuername` = '$newManuf', `manufactuercode` = 
		$ManufCode, `country` = '$cuntoryManuf', `manuftype` = '$ManufactuerTypeName' WHERE `id` = $rowIdManuf";
		mysqli_query($link,$updateNewManuf)or die("ERROR :05-INC_AMN_I");
		
		$updateWarehouse="UPDATE `stockitems` SET `manufacturing` = $ManufCode 
		WHERE `manufacturing` = $ManufactuerCode";
		mysqli_query($link,$updateWarehouse)or die("ERROR :06-INC_AMN_I");
		
		$sqlUpdateLookUp="UPDATE `lookupstock` SET `manufacture` = '$newManuf' 
		WHERE `manufacture` = '$manufOldName'";
		mysqli_query($link,$sqlUpdateLookUp)or die("ERROR :06-INC_AMN_I");
		
		$action="Edit Manufacturer old Name $manufOldName to - $newManuf and Solve Supplier code also";
		$logRef=3;
		include_once("aduLog.php");
		echo 1;
		exit();	
   }
}


else
{
	echo 9;
	exit();	
}

?>