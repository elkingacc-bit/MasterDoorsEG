<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

 
 $GroupName=$_POST['Group'];
 $SGroupName=$_POST['SGroup'];
 $SSGroupName=$_POST['SSGroup'];
 $RowID = $_POST['IRID'];
 
	$sqlGetItemData="SELECT `description`, `descriptionname` FROM `stockitems` WHERE `itemsid` =  $RowID";
	$queryItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :011-CGN_SNSTO_S");
	$resItemData=mysqli_fetch_assoc($queryItemData);
	 
 $DescCode=$resItemData['description'];
 $Descname=$resItemData['descriptionname'];


//group
$sqlCheckGroupName="SELECT `categoryname`, `category` FROM `stockitems` WHERE `categoryname` = '$GroupName'";
	$queryChecGroupName=mysqli_query($link,$sqlCheckGroupName)or die("ERROR :01-CGN_SNSTO_S");
	$resGroupName=mysqli_fetch_assoc($queryChecGroupName);
	if(mysqli_num_rows($queryChecGroupName) > 0)
	{
		 $groupCode=$resGroupName['category'];
		 
	}

	else
	{
	$sqlCheckGroupCode="SELECT `category` FROM `stockitems` ORDER BY`category` DESC LIMIT 1";
	$queryCheckGroupCode=mysqli_query($link,$sqlCheckGroupCode)or die("ERROR :02-CGC_SNSTO_S");
	$resGroupCode=mysqli_fetch_assoc($queryCheckGroupCode);	
	$groupCode=($resGroupCode['category']+1);
	
	$sqlAddNewGroup="INSERT INTO `stockitems` (`categoryname`, `category`) VALUES ('$GroupName', $groupCode)";
	mysqli_query($link,$sqlAddNewGroup)or die("ERROR :01_3-ANG_SNSTO_I");
	}
	//sub group
	$sqlCheckSGroupName="SELECT `subcategory`, `subcategoryname` FROM `stockitems` 
	WHERE `subcategoryname` = '$SGroupName'	AND `subcategory` LIKE '$groupCode%' ORDER BY `subcategory` 
	DESC LIMIT 1";
	$queryCheckSGroupName=mysqli_query($link,$sqlCheckSGroupName)or die("ERROR :03-CSGN_SNSTO_S");
	$resSGroupName=mysqli_fetch_assoc($queryCheckSGroupName);
	if(mysqli_num_rows($queryCheckSGroupName) > 0)
	{
		 $groupSCode=$resSGroupName['subcategory'];
	}

	else
	{
	$sqlGetSGroupCode="SELECT `subcategory` FROM `stockitems` WHERE `subcategory` LIKE '$groupCode%' 
	ORDER BY `subcategory` DESC LIMIT 1";
	$queryGetSGroupCode=mysqli_query($link,$sqlGetSGroupCode)or die("ERROR :03-CSGN_SNSTO_S");
	$resGetSGroupCode=mysqli_fetch_assoc($queryGetSGroupCode);
		 if(mysqli_num_rows($queryGetSGroupCode) > 0)
		 {
		  $groupSCode=($resGetSGroupCode['subcategory']+1);
			$sqlAddNewSGroup="INSERT INTO `stockitems` (`subcategoryname`, `subcategory`) 
			VALUES ('$SGroupName', $groupSCode)";
			mysqli_query($link,$sqlAddNewSGroup)or die("ERROR :3_3-ANSSG_SNSTO_I");
		 }
		 else
		 {
			 $newSubCode = 11;
			 $groupSCode=$groupCode.$newSubCode;
			 
			 $sqlAddNewSGroup="INSERT INTO `stockitems` (`subcategoryname`, `subcategory`) 
			 VALUES ('$SGroupName', $groupSCode)";
			 mysqli_query($link,$sqlAddNewSGroup)or die("ERROR :3_3-ANSSG_SNSTO_I".mysqli_error($link));
		 }

	}
	//ssGroup
	
	$sqlCheckSSGroupName="SELECT `subSCatg` FROM `stockitems` WHERE `subSCatg` LIKE '$groupSCode%' 
	AND `subSCatgName` = '$SSGroupName'  ORDER BY `subSCatg` DESC LIMIT 1";
	$queryCheckSSGroupName=mysqli_query($link,$sqlCheckSSGroupName)or die("ERROR :05-CSSGN_SNSTO_S");
	$resSSGroupName=mysqli_fetch_assoc($queryCheckSSGroupName);
		
		if(mysqli_num_rows($queryCheckSSGroupName) > 0)
		{
			$groupSSCode=$resSSGroupName['subSCatg'];
		}
	else
		{
		$sqlCheckSSGroupName="SELECT `subSCatg` FROM `stockitems` WHERE `subSCatg` LIKE '$groupSCode%' 
		ORDER BY `subSCatg` DESC LIMIT 1";
		$queryCheckSSGroupName=mysqli_query($link,$sqlCheckSSGroupName)or die("ERROR :05-CSSGN_SNSTO_S");
		$resSSGroupName=mysqli_fetch_assoc($queryCheckSSGroupName);
		
			if(mysqli_num_rows($queryCheckSSGroupName) > 0)
			{
			$groupSSCode=($resSSGroupName['subSCatg']+1);
			$sqlAddNewSSGroup="INSERT INTO `stockitems` (`subSCatgName`, `subSCatg`)
			 VALUES ('$SSGroupName', $groupSSCode)";
			mysqli_query($link,$sqlAddNewSSGroup)or die("ERROR :2_3-ANSG_SNSTO_I");	
			}
			else
			{
				$newSSubCode = 11;
		 		$groupSSCode=$groupSCode.$newSSubCode;
				$sqlAddNewSSGroup="INSERT INTO `stockitems` (`subSCatgName`, `subSCatg`) 
				VALUES ('$SSGroupName', $groupSSCode)";
				mysqli_query($link,$sqlAddNewSSGroup)or die("ERROR :2_4-ANSG_SNSTO_I");	
			}
		}
	$sqlCheckDescrip="SELECT `description` FROM `stockitems` WHERE `description` LIKE '$groupSSCode%' 
			AND `description` IS NOT NULL ORDER BY `description` DESC LIMIT 1";
			$queryCheckDescrip=mysqli_query($link,$sqlCheckDescrip)or die("ERROR :07-CD_SNSTO_S");
			$resDescrip=mysqli_fetch_assoc($queryCheckDescrip);
			if(mysqli_num_rows($queryCheckDescrip) > 0)
				{
					//echo $resDescrip['description']."<br>";
					 $newDescripCode=($resDescrip['description']+1);
				}
			else
				{
					$newDescCode = 10001 ;
				    $newDescripCode = $groupSSCode.$newDescCode;
				}


	
	$sqlUpdateStkI="UPDATE `stockitems` SET `description` = $newDescripCode WHERE `description` = $DescCode";
	mysqli_query($link,$sqlUpdateStkI)or die("ERROR :12-UPI_USCWH_U".mysqli_error($link));
	
	$sqlUpdateWH="UPDATE `warehouse` SET `description` = $newDescripCode WHERE `description` = $DescCode";
	mysqli_query($link,$sqlUpdateWH)or die("ERROR :12_1-UPI_USCWH_U".mysqli_error($link));
	
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
	
	$sqlCheckSuppInv="SELECT `ItemRowId` FROM `supplierinvoicedata` WHERE `ItemRowId` = $DescCode";
	$queryCheckSuppInv=mysqli_query($link,$sqlCheckSuppInv)or die("ERROR :8-CPI_USCWH_S".mysqli_error($link));
	if(mysqli_num_rows($queryCheckSuppInv) > 0)
	{
		$sqlUpdateSuppInv="UPDATE `supplierinvoicedata` SET `ItemRowId` = $newDescripCode 
		WHERE `ItemRowId` = $DescCode";
		mysqli_query($link,$sqlUpdateSuppInv)or die("ERROR :9-UPI_USCWH_U".mysqli_error($link));
	}	
	
		$action="Edit New Organize For Item : $Descname";
		$logRef=6;
	include_once("aduLog.php");
				echo 1;
				exit();		
?>
