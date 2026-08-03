<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$orderRowId = $_POST['SORowIdR'];
$OrderType = $_POST['SupplyOTypeR'];
$jobRowIDForEdit = $_POST['SuppRJobRID'];

if($OrderType == "Doors")
{
	$th2= "Item";
	$th3= "M<sup>2</sup>";
}
else if($OrderType == "Automatic")
{
	$th2= "Door Specs";
	$th3= "Motor Specs";
}

?>
 <input type="text" value="<?php echo $orderRowId;?>" style="display:none" id="recevSOrderRID"/>
  <input type="text" value="<?php echo $OrderType;?>" style="display:none" id="recevSOrderType"/>
 <input type="text" value="<?php echo $jobRowIDForEdit;?>" style="display:none" id="recevSOJobRowId"/> 
  <div class="modal-header">
        <h5 class="modal-title">Receive Supply Item QTY</h5>
      </div>
 <div class="modal-body">
   
    <div class="editFormRecevie" style="display:none">
 	
    <table>
    	<th>QTY</th>
        <td style="width:20px"></td>
        <td>
        	<input type="number" id="recevsupplyRequest" class="form-control" min="1" 
            data-toggle='tooltip' data-placement='left' />
        </td>
        <tr>
        <td align="center" colspan="3">
        	<button class="btn btn-sm btn-success" id="saveRecevSupplyItemBTN">Save</button>
        </td>
        </tr>
    </table>
 </div> 
   
<div class="table-responsive">
 <table class=" table  table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Type</th>
          <th><?php echo $th2;?></th>
          <th><?php echo $th3;?></th>
          <th>QTY</th>
          <th>Order QTY</th>
          <th>Edit</th>
    </thead>
    <tbody>
    
		<?php
		
	$sqlGetOrderItem="SELECT `OIId`,`ItemRowId`, `qty` FROM `supporderitems` 
	WHERE `SOIdRef` = $orderRowId AND `qty` != `receivedQTY`";
	$queryGetOrderItem=mysqli_query($link,$sqlGetOrderItem)or die("ERROR :01-AU_AU_S");
	while($resGetOrderItem= mysqli_fetch_assoc($queryGetOrderItem))
	{
			
			if($OrderType == "Doors")
			{
				$sqlGetItemData = "SELECT `itemtype`, `itemname`, `itemm2`, `itemqty` FROM `itemoffer` 
				WHERE `id` = $resGetOrderItem[ItemRowId]";
			}
			else if($OrderType == "Automatic")
			{
				$sqlGetItemData = "SELECT `doortype`, `doorspecs`, `motorspecs`,`doorqty` FROM `autodoorsoffer`
				 WHERE `id` = $resGetOrderItem[ItemRowId]";
			}
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
		
        echo "
		<tr>
        <td class='col-sm-1' class='ItemTypeTh'> $resGetItemData[0]</td>
        <td class='col-sm-3'> $resGetItemData[1]</td>
        <td class='col-sm-3'> $resGetItemData[2]</td>
        <td class='col-sm-1'> $resGetItemData[3]</td>
        <td class='col-sm-1'> $resGetOrderItem[qty]</td>
        <td class='col-sm-1'>
        <span data-toggle='tooltip' data-placement='left' title='Edit'>
		<button class='btn btn-link btn-xs recevSuppItem' 
		value='$resGetOrderItem[OIId],$resGetOrderItem[qty]'>
		<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
		</button>
		</span>
        </td>
        </tr>
		";
	}
 ?>
 	<tr>
    	<td colspan="6" align="center">
        	<button class="btn btn-sm btn-dark" id="receiveAllItemsBTN">Receive All</button>
        </td>
    </tr>
	</tbody>
    
 </table> 
  <input type="number"  style="display:none" id="recevSuppOrderItemRID"/>
   <input type="number"  style="display:none" id="SupplyOrderQTY"/>
 
 </div>
 <script type="text/javascript">
 	$(document).ready(function() {
 
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
        
	$(".recevSuppItem").click(function(){
		
		var SupItemRowIdRecev = $(this).val().split(',')[0];
		var SupItemOQTYRecev = $(this).val().split(',')[1];
		
		$("#recevSuppOrderItemRID").val(SupItemRowIdRecev);
		
		$("#recevsupplyRequest").val('');
		$("#SupplyOrderQTY").val('');
		$("#SupplyOrderQTY").val(SupItemOQTYRecev);
		$(".editFormRecevie").show();
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//var SupRowId = $("#SOrderRID").val();
		//var SupOrderType = $("#SOrderType").val();
		
		return false;
		});	
		
		$("#receiveAllItemsBTN").click(function(){
			
			var recAllJobId = $("#recevSOJobRowId").val();
			var recAllSuppRID = $("#recevSOrderRID").val();
			var recSuppOrderType = $("#recevSOrderType").val();
			
			var confirmRecAll = confirm("Please Confirm Receive All Item?");
			
			if(confirmRecAll === true)
			{
				$.ajax({ 
					
					url:"dist/php/saveReceiveSupplyAllItemQTY.php",
					type:"POST",
					data:{RecvAllJID:recAllJobId,RecvAllSORID:recAllSuppRID,RecvSOType:recSuppOrderType},
					beforeSend: function(){
					$("#receiveAllItemsBTN").prop('disabled', true);				
					},
					success: function(doneReceiveAllItems){
						
						if(doneReceiveAllItems == 1)
						{
							alert("Data Saved");
								
								$('.ShowData').html('');
								$(".myModal").modal('toggle');
								$('.SuppOrderEdit').html('');
								setTimeout(function(){
								$('.SuppOrderEdit').load("dist/php/allSuppOrder.php");
								}, 500);
						}
						else
						{
							alert(doneReceiveAllItems);
						}
					}
					
					});
			}
			
			});
		
		
		$("#saveRecevSupplyItemBTN").click(function(){
			
			var receiveItemQRID = $("#recevSuppOrderItemRID").val();
			var receiveItemQOerderType = $("#recevSOrderType").val();
			var receiveItemQOerderRID = $("#recevSOrderRID").val();
			var receiveedItemQty = $("#recevsupplyRequest").val();
			var receiveJobRowId = $("#recevSOJobRowId").val();
			var SuplyQTYCheck = $("#SupplyOrderQTY").val();
			
			receiveedItemQty = Number(receiveedItemQty);
			SuplyQTYCheck = Number(SuplyQTYCheck);
			
			if(receiveedItemQty == "" || receiveedItemQty == 0)
			{
				alert('Please Add Supply QTY');
				$("#recevsupplyRequest").css("border-color","red");
				setTimeout(function(){
				   $("#recevsupplyRequest").css("border-color","#EBEBEB");    						
				   $("#recevsupplyRequest").focus();							
				}, 1500);
			}
			else if(receiveedItemQty > SuplyQTYCheck)
			{
				alert('Received QTY large Than Supply QTY expexted QTY <=' + SuplyQTYCheck);
				$("#recevsupplyRequest").css("border-color","red");
				setTimeout(function(){
				   $("#recevsupplyRequest").css("border-color","#EBEBEB");    						
				   $("#recevsupplyRequest").focus();							
				}, 1500);
			}
			else
			{
				$.ajax({
						url:"dist/php/saveReceiveSupplyItemQTY.php",
						type:"POST",
						data:{ReItemRID:receiveItemQRID,ReSOType:receiveItemQOerderType,SOReRowID:receiveItemQOerderRID,receivedQTY:receiveedItemQty,SOReceJobRID:receiveJobRowId},
						beforeSend: function(){
							$("#saveRecevSupplyItemBTN").prop('disabled', true);		
						},
						success: function(doneReceiveItemQTY){
							
							if(doneReceiveItemQTY == 0)
							{
								alert("Recevied QTY larg than the Order QTY, please check the Order QTY");
								$("#recevsupplyRequest").css("border-color","red");
								setTimeout(function(){
								   $("#recevsupplyRequest").css("border-color","#EBEBEB");    						
								   $("#recevsupplyRequest").focus();							
								}, 1500);
								$("#saveRecevSupplyItemBTN").prop('disabled', false);	
							}
							else if(doneReceiveItemQTY == 1)
							{
								alert("Data Saved");
								$("#recevsupplyRequest").val('');
								$("#saveRecevSupplyItemBTN").prop('disabled', false);	
								$(".editFormRecevie").hide();
								$('.ShowData').html('');
								//$(".myModal").modal('toggle');
								$('.ShowData').load("dist/php/recevieSuppItemQTYForm.php",
								{SORowIdR:receiveItemQOerderRID, SupplyOTypeR:receiveItemQOerderType,SuppRJobRID:receiveJobRowId});
								$('.SuppOrderEdit').html('');
								setTimeout(function(){
								$('.SuppOrderEdit').load("dist/php/allSuppOrder.php");
								}, 500);
							}
							else 
							{
								alert(doneReceiveItemQTY);
								$("#saveRecevSupplyItemBTN").prop('disabled', false);	
							}
							
						}
					});
			}
			
			
			return false;
			});		
    });
 
 
 </script>  
