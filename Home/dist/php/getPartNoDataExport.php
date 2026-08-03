<?php
//Canceled
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$partNo = $_POST['sPartNum'];
	
	$sqlGetItemsCode="SELECT `description` FROM `stockitems` WHERE `partnumber` = '$partNo'";
	$queryGetItemsCode=mysqli_query($link,$sqlGetItemsCode)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemsCode= mysqli_fetch_assoc($queryGetItemsCode);
	
	$itemCode = $resGetItemsCode['description'];
	$sqlGetItems="SELECT `descriptionname`, `description`, `imagename`, `salesfactor`, `overprice` 
	FROM `stockitems` WHERE `description` = $itemCode";
	$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
	$resGetItems= mysqli_fetch_assoc($queryGetItems);
	
	$sqlGetItemPrice = "SELECT `sales` FROM `lookupstock` WHERE `descriptioncode` = $resGetItems[description] ";
    $queryGetItemPrice = mysqli_query($link,$sqlGetItemPrice)or die("ERROR :02-ANJ_GCN_S");
    $resGetItemPrice = mysqli_fetch_assoc($queryGetItemPrice);
		
    	$itemPrice = $resGetItemPrice['sales'];	
		
		if($itemPrice == 0)
		{
			$sqlGetPrice = "SELECT `overprice`, `salesfactor` FROM `stockitems` 
			WHERE `description` = $resGetItems[description] ";
			$queryGetPrice = mysqli_query($link,$sqlGetPrice)or die("ERROR :02-ANJ_GCN_S");
			$resGetPrice = mysqli_fetch_assoc($queryGetPrice);
			if($resGetPrice['overprice'] > 0 && $resGetPrice['salesfactor'] > 0)
			{
				$itemPrice = round($resGetPrice['overprice'] + 
				($resGetPrice['overprice'] * $resGetPrice['salesfactor']));
			}
		}	
	
	$sqlGetWHStock="SELECT SUM(`income` - `export`) AS WHStock FROM `warehouse` WHERE `description`
	 = $resGetItemsCode[description] ";
	$queryGetWHStock=mysqli_query($link,$sqlGetWHStock)or die("ERROR :03-AM_AMDL_S".mysqli_error($link));
	if(mysqli_num_rows($queryGetWHStock) > 0)
	{
		$resGetWHStock= mysqli_fetch_assoc($queryGetWHStock);
		$warehouseStock = $resGetWHStock['WHStock'];
	}
	else
	{
		$warehouseStock = 0;
	}
	
	
	$totalStock = ($warehouseStock );
	
	if($resGetItems['imagename'] == NULL|| $resGetItems['imagename'] == "")
	{
		$imageSource = 'defaultItem.jpg';
	}
	else
	{
		$imageSource = $resGetItems['imagename'];
	}
	
	
	  $resfulldate =  array(
	  "ItemName" => $resGetItems['descriptionname'], 
	  "ItemImage" => $imageSource, 
      "WHStock" => $warehouseStock,
	  "TotalStock" => $totalStock,	
	  "ItemCode" => $itemCode,	
	  "ItemPrice" => $itemPrice,	
	  );
	  
	  echo json_encode($resfulldate);die;
	
}
else
{
	echo 9;
}
?>
