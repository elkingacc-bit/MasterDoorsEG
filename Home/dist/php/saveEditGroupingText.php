<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$rowId=$_POST['StockIRowId'];
$RefVal=$_POST['BtnVal'];
$Text=$_POST['tetxEdited'];

if($RefVal == 1)
{
	$cloumn = "categoryname";
	$th = "Group Name";
	$type = "Group";
}
else if($RefVal == 2)
{
	$cloumn = "subcategoryname";
	$th = "Sub Group Name";
	$type = "Sub Group";
}

else if($RefVal == 3)
{
	$cloumn = "subSCatgName";
	$th = "Sub Sub Group Name";
	$type = "S-Sub Group";
}

$sqlCheckName = "SELECT `$cloumn` FROM `stockitems` WHERE `$cloumn` = '$Text' AND `itemsid` != $rowId ";
$queryCheckName = mysqli_query($link,$sqlCheckName)or die("ERROR :01-CNS_ANS_S"); 

	if(mysqli_num_rows($queryCheckName) > 0)
	{
		echo 0;
	}
	else
	{
		$sqlGetGName = "SELECT `$cloumn` FROM `stockitems` WHERE  `itemsid` = $rowId ";
		$queryGetGName = mysqli_query($link,$sqlGetGName)or die("ERROR :01-CNS_ANS_S"); 
		$resGetGName= mysqli_fetch_assoc($queryGetGName);
		$oldName = $resGetGName['$cloumn'];
		
		$sqlEditGrouping = "UPDATE `stockitems` SET `$cloumn` = '$Text' WHERE `itemsid` = $rowId ";
		mysqli_query($link,$sqlEditGrouping)or die("ERROR :02-CNS_ANS_S"); 
		
		
		$sqlCheckTypeName = "SELECT `typeId` FROM `whtype` WHERE `typeName` = '$Text' 
		AND `typeRef` = '$type'";
		$queryCheckTypeName = mysqli_query($link,$sqlCheckTypeName)or die("ERROR :03-CNS_ANS_S"); 
		
		if(mysqli_num_rows($queryCheckTypeName) > 0)
		{
			$resCheckTypeName = mysqli_fetch_assoc($queryCheckTypeName);
			$WHTRID = $resCheckTypeName['typeId'];
			$sqlEditWHType = "UPDATE `whtype` SET `typeName` = '$Text' WHERE `typeId` = $WHTRID";
			mysqli_query($link,$sqlEditWHType)or die("ERROR :04-CNS_ANS_S"); 
		}
		else
		{
			$sqlAddWHType = "INSERT INTO `whtype` (`typeName`, `typeRef`) VALUES ('$Text' , '$type')";
			mysqli_query($link,$sqlAddWHType)or die("ERROR :05-CNS_ANS_S"); 
			
		}
			
		$action="Edited $th from $oldName to $Text";
		$logRef=6;
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