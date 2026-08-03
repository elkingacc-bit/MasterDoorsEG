<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$suppOrder = $_POST['SORID'];
	$suppOrderType = $_POST['oType'];
	$jobRowId = $_POST['jobTableRId'];
	
	if($suppOrderType == 'Doors')
	{
		
	$sqlGetAllItems="SELECT `id`, `itemtype` , `itemqty` FROM `itemoffer`  WHERE `jobref` = $jobRowId ";
	$queryGetAllItems=mysqli_query($link,$sqlGetAllItems)or die("ERROR :01-AU_AU_S".mysqli_error($link));
		while($resGetAllItems= mysqli_fetch_assoc($queryGetAllItems))
		{
			
			$sqlGetItemRecQty="SELECT SUM(`qty`) AS SupQTY FROM `supporderitems` 
			WHERE `ItemRowId` = $resGetAllItems[id] ";			
			$queryGetItemRecQty=mysqli_query($link,$sqlGetItemRecQty)or die("ERROR :02-AU_AU_S");
			$resGetItemRecQty= mysqli_fetch_array($queryGetItemRecQty);
			if( $resGetItemRecQty == "")
			{
				$receivedQTY = 0;
			}
			else
			{
				
				$supplyQTY = $resGetItemRecQty['SupQTY'];
			}
			
			
				
				if ($resGetAllItems['itemqty'] != $supplyQTY)
				{
					echo "<option data-value='$resGetAllItems[id]' 
						value='$resGetAllItems[itemtype]'> ";
				}
				
			
		}
		 
	}
	else if($suppOrderType == 'Automatic')
	{
		
		$sqlGetAllAuto="SELECT `id`, `doortype`, `doorqty` FROM `autodoorsoffer` WHERE `jobid` = $jobRowId ";
		$queryGetAllAuto=mysqli_query($link,$sqlGetAllAuto)or die("ERROR :01-AU_AU_S".mysqli_error($link));
		while($resGetAllAuto= mysqli_fetch_assoc($queryGetAllAuto))
		{	
			
			$sqlGetItemRecQty="SELECT SUM(`qty`) AS SupQTY FROM `supporderitems` 
			WHERE `ItemRowId` = $resGetAllAuto[id] AND `SOIdRef` = $suppOrder ";			
			$queryGetItemRecQty=mysqli_query($link,$sqlGetItemRecQty)or die("ERROR :02-AU_AU_S");
			$resGetItemRecQty= mysqli_fetch_assoc($queryGetItemRecQty);
			if($resGetItemRecQty['SupQTY'] == "")
			{
				$supplyQTY = 0;
			}
			else
			{
				
				$supplyQTY = $resGetItemRecQty['SupQTY'];
			}
			
			if ($resGetAllAuto['doorqty'] != $supplyQTY)
				{
					echo "<option data-value='$resGetAllAuto[id]' 
						value='$resGetAllAuto[doortype]'> $resGetAllAuto[doorqty]";
				}	
		}
		
	}
	else
	{
		echo "<option value='Unexpected Error !!!'>";
	}
}
else
{
	echo 9;
}
?>
