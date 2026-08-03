<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if($_SESSION['username'] == "")
{
	echo 9;
}
else
{
 
 $GroupName=$_POST['Group'];
 $SGroupName=$_POST['SGroup'];
 $SSGroupName=$_POST['SSGroup'];
 $RowID = $_POST['IRID'];
 $GroupCode=$_POST['GroupCode'];
 $SGroupCode=$_POST['SGroupCode'];
 $SSGroupCode=$_POST['SSGroupCode'];
 
 
	$sqlGetItemData="SELECT `description`, `descriptionname` FROM `stockitems` WHERE `itemsid` =  $RowID";
	$queryItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :01-CGN_SNSTO_S");
	$resItemData=mysqli_fetch_assoc($queryItemData);
	 
 $DescCode=$resItemData['description'];
 $Descname=$resItemData['descriptionname'];
 
 $subSubCatgLenght=substr($DescCode,0,8);
 
 if($subSubCatgLenght == $SSGroupCode)
 {
	 echo 2;
 }
 else
 {


//update item grouping

	$sqlGetLastItem="SELECT `description` FROM `stockitems` WHERE `description` LIKE ('$SSGroupCode%') 
	ORDER BY `description` DESC";
	$queryLastItem=mysqli_query($link,$sqlGetLastItem)or die("ERROR :02-CGN_SNSTO_S");
	$resLastItem=mysqli_fetch_assoc($queryLastItem);
	
	$new = 1;
	$newDescripCode = ($resLastItem['description'] + $new);

	
	$sqlUpdateStkI="UPDATE `stockitems` SET `description` = $newDescripCode WHERE `description` = $DescCode";
	mysqli_query($link,$sqlUpdateStkI)or die("ERROR :3-UPI_USCWH_U".mysqli_error($link));
	
	$sqlUpdateWH="UPDATE `warehouse` SET `description` = $newDescripCode WHERE `description` = $DescCode";
	mysqli_query($link,$sqlUpdateWH)or die("ERROR :4-UPI_USCWH_U".mysqli_error($link));
	
	$sqlUpdateLookUp="UPDATE `lookupstock` SET `descriptionCode` = $newDescripCode 
	WHERE `descriptionCode` = $DescCode";
	mysqli_query($link,$sqlUpdateLookUp)or die("ERROR :4_1-UPI_USCWH_U".mysqli_error($link));

	$sqlCheckOP="SELECT `descripcode` FROM `offerproperties` WHERE `descripcode` = $DescCode";
	$queryCheckOP=mysqli_query($link,$sqlCheckOP)or die("ERROR :5-CPI_USCWH_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckOP) > 0)
	{
		$sqlUpdateOP="UPDATE `offerproperties` SET `descripcode` = $newDescripCode 
		WHERE `descripcode` = $DescCode";
		mysqli_query($link,$sqlUpdateOP)or die("ERROR :6-UPI_USCWH_U".mysqli_error($link));
	}
	
	$sqlCheckSO="SELECT `descripcode` FROM `stockoffers` WHERE `descripcode` = $DescCode";
	$queryCheckSO=mysqli_query($link,$sqlCheckSO)or die("ERROR :7-CPI_USCWH_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckSO) > 0)
	{
		$sqlUpdateSO="UPDATE `stockoffers` SET `descripcode` = $newDescripCode 
		WHERE `descripcode` = $DescCode";
		mysqli_query($link,$sqlUpdateSO)or die("ERROR :8-UPI_USCWH_U".mysqli_error($link));
	}
	
	$sqlCheckKitCopt="SELECT `descripcode` FROM `kitscomponents` WHERE `descripcode` = $DescCode";
	$queryCheckKitCopt=mysqli_query($link,$sqlCheckKitCopt)or die("ERROR :8-CPI_USCWH_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckKitCopt) > 0)
	{
		$sqlUpdateKitCopt="UPDATE `kitscomponents` SET `descripcode` = $newDescripCode 
		WHERE `descripcode` = $DescCode";
		mysqli_query($link,$sqlUpdateKitCopt)or die("ERROR :9-UPI_USCWH_U".mysqli_error($link));
	}
	
	$sqlCheckSuppInv="SELECT `ItemRowId` FROM `supplierInvoiceData` WHERE `ItemRowId` = $DescCode";
	$queryCheckSuppInv=mysqli_query($link,$sqlCheckSuppInv)or die("ERROR :8-CPI_USCWH_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckSuppInv) > 0)
	{
		$sqlUpdateSuppInv="UPDATE `supplierinvoicedata` SET `ItemRowId` = $newDescripCode 
		WHERE `ItemRowId` = $DescCode";
		mysqli_query($link,$sqlUpdateSuppInv)or die("ERROR :9-UPI_USCWH_U".mysqli_error($link));
	}	
	
	$sqlCheckImportSTK="SELECT `descriptionCode` FROM `importstock` WHERE `descriptionCode` = $DescCode";
	$queryCheckImportSTK=mysqli_query($link,$sqlCheckImportSTK)or die("ERROR :9-CPI_USCWH_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckImportSTK) > 0)
	{
		$sqlUpdateImportSTK="UPDATE `importstock` SET `descriptionCode` = $newDescripCode 
		WHERE `descriptionCode` = $DescCode";
		mysqli_query($link,$sqlUpdateImportSTK)or die("ERROR :10-UPI_USCWH_U".mysqli_error($link));
	}	
				
		$action="Re-Organize Item : $Descname to Group $GroupName";
		$logRef=6;
	include_once("aduLog.php");
				echo 1;
				exit();	
				
 }
 
}//session chack
?>
