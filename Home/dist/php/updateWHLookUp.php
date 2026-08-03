<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

if(!empty($_SESSION['username']))
{
	
	$sqlTRUNCATELookUp=" DELETE FROM `lookupstock`";
	mysqli_query($link,$sqlTRUNCATELookUp)or die("ERROR :01-CLU_CULU_S".mysqli_error($link));
		
	$sqlSetAutoIncLookUp=" ALTER TABLE `lookupstock` AUTO_INCREMENT = 1";
	mysqli_query($link,$sqlSetAutoIncLookUp)or die("ERROR :02-CLU_CULU_S".mysqli_error($link));

	$sqlGetItemInfo=" SELECT `description`, `descriptionname`, `imagename`, `manufacturing`,
	`partnumber`, `salesfactor`,`overprice` FROM `stockitems` WHERE `description` IS NOT NULL 
	GROUP BY `description`";
	$queryGetItemInfo=mysqli_query($link,$sqlGetItemInfo)or die("ERROR :06-CLU_CULU_S"
	.mysqli_error($link));	
	while($resGetItemInfo = mysqli_fetch_assoc($queryGetItemInfo))
	{
		
	$partNum = 	$resGetItemInfo['partnumber'];
	$itemName = $resGetItemInfo['descriptionname'];
	$imagePath = $resGetItemInfo['imagename'];
	$itemCode = $resGetItemInfo['description'];
	$overCost = $resGetItemInfo['overprice'];
	
	
	$sqlGetWHInfo=" SELECT `supplier` FROM `warehouse` WHERE `description` = $itemCode ORDER BY `date` DESC LIMIT 1";
	$queryGetWHInfo=mysqli_query($link,$sqlGetWHInfo)or die("ERROR :03-CLU_CULU_S"
	.mysqli_error($link));
    $resGetWHInfo = mysqli_fetch_assoc($queryGetWHInfo);
	if(mysqli_num_rows($queryGetWHInfo) == 0 || $resGetWHInfo['supplier'] == 0)
	{
		$supplier = 'N/A';
	}
	else
	{
	    
		$sqlGetSupp=" SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
		$resGetWHInfo[supplier]";
		$queryGetSupp=mysqli_query($link,$sqlGetSupp)or die("ERROR :08-CLU_CULU_S"
		.mysqli_error($link));	
		$resGetSupp= mysqli_fetch_assoc($queryGetSupp);
		
		$supplier = $resGetSupp['suppliername'];
	}

	$sqlGetWHStock=" SELECT SUM(`income` - `export`) AS WHStock FROM `warehouse` WHERE `description` = 
	$itemCode";
	$queryGetWHStock=mysqli_query($link,$sqlGetWHStock)or die("ERROR :04-CLU_CULU_S"
	.mysqli_error($link));
	$resGetWHStock = mysqli_fetch_assoc($queryGetWHStock);
	
	if($resGetWHStock['WHStock'] == NULL || $resGetWHStock['WHStock'] == "")
	{
		$wareHouseStock = 0;
	}
	else
	{
		$wareHouseStock = $resGetWHStock['WHStock'];
	}
		
	$totalStock = ($wareHouseStock);
	
	if($resGetItemInfo['salesfactor'] == 0)
	{
		$saleFact = 0;
	}
	else
	{
		$saleFact = $resGetItemInfo['salesfactor'];
	}
	$overCost = $resGetItemInfo['overprice'];
	
	$sqlGetItemsCost="SELECT `supplierInvoiceUnitPrice`	FROM `supplierInvoiceData`, `supplierInvoice` 
	WHERE `suppliersInvoiceId` = `supplierInvoiceNumber` AND  `ItemRowId` = $itemCode 
	ORDER BY `suppliersInvoiceDate` DESC LIMIT 1";
	$queryGetItemsCost=mysqli_query($link,$sqlGetItemsCost)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
	
	if(mysqli_num_rows($queryGetItemsCost) == 0)
	{
		$itemPrice = 0;
		$totalCost = 0 ;
		$itemCost = 0;
	}
	else
	{
		$resGetItemsCost= mysqli_fetch_assoc($queryGetItemsCost);
		$itemCost = $resGetItemsCost['supplierInvoiceUnitPrice'];
		
		if($saleFact == 0)
	    {
	    	$itemPrice = 0;
	    	$totalCost = ($itemCost * $totalStock) ;
    	}
    	else
    	{
		
	    	$itemPrice = round(($saleFact * ($itemCost + $overCost)) + ( $overCost + $itemCost));
	    	$totalCost = ($itemCost * $totalStock) ;
    	}
	    
	    
	}
	
	
	if($resGetItemInfo['manufacturing'] == 0 )
	{
		$manufactuer = 'N/A';
	}
	else
	{
	$sqlGetManuf=" SELECT `manufactuername` FROM `allmanufactuers` WHERE `manufactuercode` = 
	$resGetItemInfo[manufacturing]";
	$queryGetManuf=mysqli_query($link,$sqlGetManuf)or die("ERROR :07-CLU_CULU_S"
	.mysqli_error($link));	
	$resGetManuf = mysqli_fetch_assoc($queryGetManuf);
	
	$manufactuer = $resGetManuf['manufactuername'];
	}
	
	
	
	$sqlUpdaateCSttcok = "INSERT INTO `lookupstock`(`descriptioncode`, `itemname`, `partno`, `warehouse`, 
	 `cost`, `sales`, `totalCost`, `manufacture`, `supplier`, `imagename`, `lastupdate`, `ref`) VALUES 
	($itemCode, '$itemName', '$partNum', $wareHouseStock, '$itemCost', '$itemPrice', '$totalCost',
	 '$manufactuer','$supplier', '$imagePath', NOW(), 1)";
	mysqli_query($link,$sqlUpdaateCSttcok)or die("ERROR :09-CLU_CULU_S".mysqli_error($link));
	
}
	
	echo 1;
	exit();
	
}
else
{
	echo 9;
}


?>