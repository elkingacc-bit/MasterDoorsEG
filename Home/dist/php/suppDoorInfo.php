<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 $SuppOrderRID = $_POST['MoadelSuppRID'];
 $itemRowId = $_POST['ModelIemeRID'];
 $offerRowId = $_POST['MoadelJobRID'];
 
	 $sqlGetOrderData = "SELECT `SuppCode`, `OrderNumber` FROM `supplierorder` 
	 WHERE `SOId` = $SuppOrderRID";
	$queryGetOrderData = mysqli_query($link,$sqlGetOrderData)or die("ERROR :01-ANJ_GCN_S");
	$resGetOrderData = mysqli_fetch_assoc($queryGetOrderData);
	
	$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
	$resGetOrderData[SuppCode]";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :02-AU_AU_S");
	$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);	
	
	$sqlGetOfferNum="SELECT `projectName` FROM `job` WHERE `jobId` = $offerRowId";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
	
	$suppName = $resGetSupplier['suppliername'];
	$OrderNo = $resGetOrderData['OrderNumber'];
	//$OrderTyTitel = $resGetOrderData['OrderNumber'];
	$projectName = $resGetOfferNum['projectName'];
	
	$sqlGetItemRef2 = "SELECT   `itemtype`, `itemname`, `itemhight`, `itemwidth` FROM `itemoffer` 
WHERE  `id` = $itemRowId";
$queryGetItemRef2 = mysqli_query($link,$sqlGetItemRef2)or die("ERROR :01-ANJ_GCN_S");
$resGetItemRef2 = mysqli_fetch_assoc($queryGetItemRef2);
	
?>
 <div class="modal-header">
        <p class="modal-title">Suppling Doors Details<span style="color:blue;">
        <b><?php echo $suppName; ?>
        </b></span> Supplier Order Number:&nbsp;<span style="color:blue;">
        <b><?php echo $OrderNo; ?></b></span><br>
         
        Project Name:&nbsp;<span style="color:blue;">
        <b><?php echo $projectName; ?></b></span>
        &nbsp;<span style="color:blue;"><b> 
        <?php
        	echo "<span style='color:black;'> Type: </span> $resGetItemRef2[itemtype] | 
			<span style='color:black;'> Item:</span> $resGetItemRef2[itemname] | 
			<span style='color:black;'> Width: </span>$resGetItemRef2[itemwidth] | 
			<span style='color:black;'> hight: </span> $resGetItemRef2[itemhight]";
		?>
        </b></span> 
        </p>
   
      </div>
       <div class="modal-body ">
       	
        <table class="table table-sm table-striped" style='width:99%'>
        	<thead class="bg-info">
            	<th>Ser</th>
                <th>Door No.</th>
                <th>Handling</th>
                <th>Overlap</th>
                <th>RAL</th>
            </thead>
            <tbody>
            	
<?php
$row = 1;



$sqlGetSuppOID = "SELECT `OIId` FROM `supporderitems` 
 WHERE `ItemRowId` = $itemRowId AND `SOIdRef` = $SuppOrderRID ";
$queryGetSuppOID = mysqli_query($link,$sqlGetSuppOID)or die("ERROR :03-ANJ_GCN_S");
while($resGetSuppOID = mysqli_fetch_assoc($queryGetSuppOID))
{
	
	$suppOrderItemRID = $resGetSuppOID['OIId'];
	$sqlGetItemDetails="SELECT `id`, `ral`,`doornumber`, `handlingSupp`, `overlap` FROM `suppitemdetails` 
	WHERE `oiidRef` = $suppOrderItemRID";
	$queryGetItemDetails=mysqli_query($link,$sqlGetItemDetails)or die("ERROR :04-AU_AU_S");
	while($resGetItemDetails= mysqli_fetch_assoc($queryGetItemDetails))
	{
		echo "
			<tr>
				<td>$row</td>
				<td>$resGetItemDetails[doornumber]</td>
				<td>$resGetItemDetails[handlingSupp]</td>
				<td>$resGetItemDetails[overlap]</td>
				<td>$resGetItemDetails[ral]</td>
			</tr>
		";
	$row++;
	}	
	
}
?>                
                
            </tbody>
        
        </table>
       
      	</div>
 <?php
 
 }
 ?>