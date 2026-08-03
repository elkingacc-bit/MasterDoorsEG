
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $itemRId = $_POST['IRID'];
 $salesFactorVal =  round(($_POST['SalesFactVal'] / 100), 2);
 $OverPrice =$_POST['overPriCost'];
  
 $sqlGetItems="SELECT `descriptionname`, `description` FROM `stockitems` WHERE `itemsid` = $itemRId";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
$resGetItems = mysqli_fetch_assoc($queryGetItems);

$sqlGetItemsCost="SELECT `cost` FROM `lookupstock` WHERE `descriptioncode` = $resGetItems[description]";
$queryGetItemsCost=mysqli_query($link,$sqlGetItemsCost)or die("ERROR :01-AU_AU_S");
$resGetItemsCost = mysqli_fetch_assoc($queryGetItemsCost);

$itemCost = $resGetItemsCost['cost'];

$newPrice = round($itemCost + $OverPrice);

$OverHead = round($newPrice * $salesFactorVal);
$NewSalesPrice = round($newPrice + $OverHead);

//
//echo "Test ->" .$newPrice;
// 

$sqlUpdatePrice = "UPDATE `lookupstock` SET `sales` = '$NewSalesPrice' WHERE `descriptioncode` =
 $resGetItems[description]";
mysqli_query($link,$sqlUpdatePrice)or die("ERROR :03-AU_AU_S");

$sqlAddSF = "UPDATE `stockitems` SET `salesfactor` = '$salesFactorVal', `overprice` = '$OverPrice' WHERE 
`itemsid` = $itemRId";
mysqli_query($link,$sqlAddSF)or die("ERROR :02-AU_AU_S");

$action="Edit Sales Factor = ".($salesFactorVal * 100)."% and OverCost = $OverPrice For 
Item: $resGetItems[descriptionname]";
		$logRef=12;
		include_once("aduLog.php");
			echo 1;
			exit();

}
?>
