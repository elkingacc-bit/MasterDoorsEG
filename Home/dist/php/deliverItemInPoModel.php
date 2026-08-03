<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$ser = 1;
$OrderType = $_POST['MoadelDeliverOrderType'];
$jobRowId = $_POST['JRIDDeliver'];
$suppRowId = $_POST['ModelDeliverSuppORID'];
if($OrderType == "Doors")
{
	
	$TableStyle= "
		<thead class='bg-info'>
			<th>Ser</th>
			<th>Type</th>
			<th>Item</th>
			<th>W</th>
			<th>H</th>
			<th>QTY</th>
			<th>Delivered</th>
			<th></th>
		</thead>
		<tbody>
	";
}
else if($OrderType == "Automatic")
{
	$TableStyle= "
		<thead class='bg-info'>
			<th>Ser</th>
			<th>Door Specs</th>
			<th>Motor Specs</th>
			<th>QTY</th>
			<th>Delivered</th>
			<th></th>
		</thead>
		<tbody>
	";
	
	$th2= "Door Specs";
	$th3= "Motor Specs";
}


	$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` = $jobRowId";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :01-AU_AU_S");
	$resGetProject = mysqli_fetch_assoc($queryGetProject);

?>
 <input type="text" value="<?php echo $suppRowId;?>" style="display:none" id="deliverSOrderRID"/>
  <input type="text" value="<?php echo $OrderType;?>" style="display:none" id="deliverOrderType"/>
 <input type="text" value="<?php echo $jobRowId;?>" style="display:none" id="deliverJobRowId"/> 
  <div class="modal-header">
        <h5 class="modal-title">Delivered Item QTY for Job <span style="color:blue"><b>
        <?php echo $resGetProject['projectName'];?></b></h5>
      </div>
 <div class="modal-body">
    <div class="editFormDeliver" style="display:none">
 	<table>
    	<th>Deliver QTY</th>
        <td style="width:20px"></td>
        <td>
        	<input type="number" id="deliverQTYRequest" class="form-control" min="1" 
            data-toggle='tooltip' data-placement='left' />
        </td>
        <td style="width:20px"></td>
        <td align="center" colspan="3">
        	<button class="btn btn-sm btn-success" id="saveDeliverItemBTN">Save</button>
        </td>
    </table>
 </div> 
  
<div class="table-responsive">
 <table class="table table-sm table-bordered" cellspacing="0" width="99%">
    
<?php
		
if($OrderType == "Doors")
{
	
	echo $TableStyle;
	
	
	
	$sqlGetOrderItem="SELECT `OIId`,`ItemRowId`, `qty`, `receivedQTY` FROM `supporderitems` 
	WHERE `SOIdRef` = $suppRowId AND `receivedQTY` != 0";
	$queryGetOrderItem=mysqli_query($link,$sqlGetOrderItem)or die("ERROR :01-AU_AU_S");
	while($resGetOrderItem= mysqli_fetch_assoc($queryGetOrderItem))
	{
	
		$sqlGetItemData = "SELECT `itemtype`, `itemname`,  `itemhight`, `itemwidth`, 
			`itemdepth`,`itemm2`,`itemqty`, `handling`, `Overlap`, `itemRal`
			 FROM `itemoffer` WHERE `id` = $resGetOrderItem[ItemRowId]";
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
		
		$sqlCheckQTY = "SELECT SUM(`itemquantity`) AS itemquantity FROM `custorderdeliver` WHERE `itemRowId` 
		= $resGetOrderItem[OIId]";
		$queryCheckQTY=mysqli_query($link,$sqlCheckQTY)or die("ERROR :01-AU_AU_S");
		$resCheckQTY= mysqli_fetch_assoc($queryCheckQTY);
		if($resGetOrderItem['qty'] != $resCheckQTY['itemquantity'] || $resCheckQTY['itemquantity'] == "")	
		{	
		if($resCheckQTY['itemquantity'] == "")
		{
			$deliverQty = 0;
		}
		else
		{
			$deliverQty = $resCheckQTY['itemquantity'];
		}
					
		echo "
				<tr>
				<td class='col-sm-1'> $ser</td>
				<td class='col-sm-1' class='ItemTypeTh'> $resGetItemData[itemtype]</td>
				<td class='col-sm-3'> $resGetItemData[itemname]</td>
				<td class='col-sm-1'> $resGetItemData[itemwidth]</td>
				<td class='col-sm-1'> $resGetItemData[itemhight]</td>
				<td class='col-sm-1'>$resGetOrderItem[receivedQTY]</td>
				<td class='col-sm-1'> $deliverQty</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Edit'>
				<button class='btn btn-link btn-xs deliverPoItem' 
				value='$resGetOrderItem[OIId],$resGetOrderItem[qty],$deliverQty'>
				<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
				</button>
				</span>
				</td>
				</tr>
				";
		
		$ser++;
		}
	
	}
}
	else if($OrderType == "Automatic")
		
		{	
		echo $TableStyle= "
		<thead class='bg-info'>
			<th>Door Specs</th>
			<th>Motor Specs</th>
			<th>QTY</th>
			<th>Delivered</th>
			<th></th>
		</thead>
		<tbody>
	";
	$sqlGetOrderItem="SELECT `OIId`,`ItemRowId`, `qty` FROM `supporderitems` 
	WHERE `SOIdRef` = $suppRowId AND `receivedQTY` != 0";
	$queryGetOrderItem=mysqli_query($link,$sqlGetOrderItem)or die("ERROR :01-AU_AU_S");
	while($resGetOrderItem= mysqli_fetch_assoc($queryGetOrderItem))
	{
		$sqlGetItemData = "SELECT `doortype`, `doorspecs`, `motorspecs`,`doorqty` FROM `autodoorsoffer`
		WHERE `id` = $resGetOrderItem[ItemRowId]";
			
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
		
				echo "
				<tr>
				<td class='col-sm-1'>$ser</td>
				<td class='col-sm-1' class='ItemTypeTh'> $resGetItemData[0]</td>
				<td class='col-sm-3'> $resGetItemData[1]</td>
				<td class='col-sm-3'> $resGetItemData[2]</td>
				<td class='col-sm-1'>$resGetOrderItem[qty]</td>
				<td class='col-sm-1'> $deliverQty</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Edit'>
				<button class='btn btn-link btn-xs deliverPoItem' 
				value='$resGetOrderItem[OIId],$resGetOrderItem[qty],$deliverQty'>
				<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
				</button>
				</span>
				</td>
				</tr>
				";
				
			$ser++;	
		}
		
}
	
 ?>
	</tbody>
    
 </table> 
  <input type="number"  style="display:none;" id="recevCustOrderItemRID"/>
  <input type="number"  style="display:none;" id="qtyItemCheck"/>
  <input type="number"  style="display:none;" id="deliveredQty"/>
 
 </div>
 <script type="text/javascript">
 	$(document).ready(function() {
 
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
        
	$(".deliverPoItem").click(function(){
		
		var deliverItemRowId = $(this).val().split(',')[0];
		var deliverItemChechQTY = $(this).val().split(',')[1];
		var deliveredQTY = $(this).val().split(',')[2];
		$("#recevCustOrderItemRID").val('');
		$("#recevCustOrderItemRID").val(deliverItemRowId);
		$("#qtyItemCheck").val(deliverItemChechQTY);
		$("#deliveredQty").val('');
		$("#deliveredQty").val(deliveredQTY);
	
		$(".editFormDeliver").show();
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//var SupRowId = $("#SOrderRID").val();
		//var SupOrderType = $("#SOrderType").val();
		
		return false;
		});	
		
		
		$("#saveDeliverItemBTN").click(function(){
			
			var deliverItemQRID = $("#recevCustOrderItemRID").val();
			var deliverItemQOerderType = $("#deliverOrderType").val();
			var deliverSuppRID = $("#deliverSOrderRID").val();
			var deliverItemQty = $("#deliverQTYRequest").val();
			var deliverJobRowId = $("#deliverJobRowId").val();
			var SupplyItemQtyCheck = $("#qtyItemCheck").val();
			var delivQTYCheck = $("#deliveredQty").val();
			var reminingQTY = (Number(SupplyItemQtyCheck) - Number(delivQTYCheck));
			
			if(deliverItemQty == "" || deliverItemQty == 0)
			{
				alert('Please Add Delivered QTY');
				$("#deliverQTYRequest").css("border-color","red");
				setTimeout(function(){
				   $("#deliverQTYRequest").css("border-color","#EBEBEB");    						
				   $("#deliverQTYRequest").focus();							
				}, 1500);
			}
			else if(deliverItemQty > reminingQTY )
			{
				alert('Delivered QTY large than the Remainig QTY, Rimining= ' + reminingQTY);
				$("#deliverQTYRequest").css("border-color","red");
				setTimeout(function(){
				   $("#deliverQTYRequest").css("border-color","#EBEBEB");    						
				   $("#deliverQTYRequest").focus();							
				}, 1500);
			} 
			else 
			{
				$.ajax({
						url:"dist/php/saveReceivePOItemQTY.php",
						type:"POST",
						data:{deliverItemRowId:deliverItemQRID,deliverOrderType:deliverItemQOerderType,deliverSORowID:deliverSuppRID,deliveredQTY:deliverItemQty,deliverJobRID:deliverJobRowId},
						beforeSend: function(){
							$("#saveDeliverItemBTN").prop('disabled', true);		
						},
						success: function(doneDeliverItemQTY){
							
							if(doneDeliverItemQTY == 1)
							{
								alert("Data Saved");
								$("#deliverQTYRequest").val('');
								$("#saveDeliverItemBTN").prop('disabled', false);	
								$(".editFormDeliver").hide();
								$('.ShowData').html('');
								$('.ShowData').load('dist/php/deliverItemInPoModel.php',
								{ModelDeliverSuppORID:deliverSuppRID, MoadelDeliverOrderType:deliverItemQOerderType,JRIDDeliver:deliverJobRowId});
								//$(".myModal").modal('toggle');
								$('.CustOrderDeliver').html('');
								$('.CustOrderDeliver').load("dist/php/allCustOrderforDeliver.php");
							}
							else
							{
								alert(doneDeliverItemQTY);
								$("#saveDeliverItemBTN").prop('disabled', false);	
							}
							
						}
					});
			}
			
			
			return false;
			});		
    });
 
 
 </script>  
