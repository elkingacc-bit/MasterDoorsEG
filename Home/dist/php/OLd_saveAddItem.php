<?php
//Canceled
session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

 $subSubCatgN=$_POST['SSCode'];
$descrip=$_POST['NewItem'];
$Cost = $_POST['dCost'];
$salesFact = $_POST['dPrice'];
$Location = $_POST['location'];
$Price = round(($Cost * $salesFact));
$Price = ($Price + $Cost);

if($Location == "")
{
	$Location = "N/A";
}

	$getNewCode="SELECT `description` FROM `stockitems` WHERE `description` LIKE('$subSubCatgN%') ORDER BY `description` DESC LIMIT 1";
	$queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :02-CND_AND_S");
	$resNewCode=mysqli_fetch_assoc($queryNewCode);
	if($resNewCode['description'] == NULL)
	{
		$new=101;
		$DescCode=($subSubCatgN.$new);
	}
	else
	{
		
		$DescCode=($resNewCode['description']+1);
	}	

		$insertNewCode="INSERT INTO `stockitems` (`descriptionname`, `description`) 
		VALUES ('$descrip', $DescCode)";
		mysqli_query($link,$insertNewCode)or die("ERROR :03-INC_AND_I".mysqli_error($link));

$sqlGetPartNum="SELECT `partnumber` FROM `warehouse` WHERE `partnumber` LIKE 'AJ_%' ORDER BY LPAD(`partnumber`, 20, '0') DESC LIMIT 1 ";
$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-CPN_PNFD_S");
if(mysqli_num_rows($queryGetPartNum) > 0)
{
$resGetPartNo=mysqli_fetch_assoc($queryGetPartNum);

$crntPartNumber=substr($resGetPartNo['partnumber'], strrpos($resGetPartNo['partnumber'], '_') + 1);
$PartNo=($crntPartNumber+1);
$newPartNumber="AJ_".$PartNo;
}
else
{
	$newPartNumber="AJ_16001";
}
$action="Add New Description- $descrip";
		$logRef=3;
		include_once("aduLog.php");
		
$sqlSaveNewQunt="INSERT INTO `warehouse`(`description`, `date`,`income`, `export`,`amount`, `salesfactor`,
 `price`, `partnumber`,`location`, `responsible`) VALUES
($DescCode, NOW(), 0, 0,'$Cost', '$salesFact', '$Price', '$newPartNumber', '$Location', '$respons')";
mysqli_query($link,$sqlSaveNewQunt)or die("ERROR :01-SNQ_SRI_I");

$sqlAddInLookUP="INSERT INTO `lookupwh`(`descriptionCode`, `partnum`, `descripName`, `lkmanufacture`, `stock`
, `cost`, `price`, `totalcost`, `grossfactor`, `lastUpdate`, `assets`, `replacement`, `assemblykits`, 
`location`, `underOrder`, `ref`) VALUES ($DescCode, '$newPartNumber', '$descrip', 'N/A', 0, '$Cost', '$Price'
, 0, 0, NOW(), 0, 0, 0, 'N/A', 0, 1)";
mysqli_query($link,$sqlAddInLookUP)or die("ERROR :02-AILU_SRI_I");
		
		echo 1;
		exit();
	

?>
