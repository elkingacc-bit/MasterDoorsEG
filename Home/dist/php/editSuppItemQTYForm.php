<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$orderRowId = $_POST['SORowId'];
$OrderType = $_POST['SupplyOType'];
$jobRowIDForEdit = $_POST['SuppEditJobRID'];

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
 <input type="text" value="<?php echo $orderRowId;?>" style="display:none" id="SOrderRID"/>
  <input type="text" value="<?php echo $OrderType;?>" style="display:none" id="SOrderType"/>
 <input type="text" value="<?php echo $jobRowIDForEdit;?>" style="display:none" id="SOJobRowId"/> 
  <div class="modal-header">
        <h5 class="modal-title">Edit Supply Item QTY<br>
        <span style="color:red; font-size:12px">{0} QTY will remove this item from supply order</span></h5>
      </div>
 <div class="modal-body">
    <div class="editForm" style="display:none">
 	<table>
    	<th>QTY</th>
        <td style="width:20px"></td>
        <td>
        	<input type="number" id="EditsupplyRequest" class="form-control" min="0" 
            data-toggle='tooltip' data-placement='left' />
        </td>
        <tr>
        <td align="center" colspan="3">
        	<button class="btn btn-sm btn-success" id="saveEditSupplyItemBTN">Save</button>
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
	WHERE `SOIdRef` = $orderRowId";
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
		<button class='btn btn-link btn-xs editSuppItem' 
		value='$resGetOrderItem[OIId],$resGetOrderItem[qty]'>
		<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
		</button>
		</span>
        </td>
        </tr>
		";
	}
 ?>
	</tbody>
    
 </table> 
  <input type="number"  style="display:none" id="SuppOrderItemRID"/>
 
 </div>
 <script type="text/javascript">
 	$(document).ready(function() {
 
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
        
	$(".editSuppItem").click(function(){
		
		var SupItemRowIdEdit = $(this).val().split(',')[0];
		var SupItemOQTYEdit = $(this).val().split(',')[1];
		
		$("#SuppOrderItemRID").val(SupItemRowIdEdit);
		
		$("#EditsupplyRequest").val('');
		$(".editForm").show();
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//var SupRowId = $("#SOrderRID").val();
		//var SupOrderType = $("#SOrderType").val();
		
		return false;
		});	
		
		
		$("#saveEditSupplyItemBTN").click(function(){
			
			var editItemQRID = $("#SuppOrderItemRID").val();
			var editItemQOerderType = $("#SOrderType").val();
			var editItemQOerderRID = $("#SOrderRID").val();
			var editedItemQty = $("#EditsupplyRequest").val();
			var editJobRowId = $("#SOJobRowId").val();
			
			if(editedItemQty == "")
			{
				alert('Please Add Supply QTY');
				$("#EditsupplyRequest").css("border-color","red");
				setTimeout(function(){
				   $("#EditsupplyRequest").css("border-color","#EBEBEB");    						
				   $("#EditsupplyRequest").focus();							
				}, 1500);
			}
			else
			{
				$.ajax({
						url:"dist/php/saveEditSupplyItemQTY.php",
						type:"POST",
						data:{SOItemRID:editItemQRID,SOType:editItemQOerderType,SORID:editItemQOerderRID,editedQTY:editedItemQty,SOEditJobRID:editJobRowId},
						beforeSend: function(){
							$("#saveEditSupplyItemBTN").prop('disabled', true);		
						},
						success: function(doneEditItemQTY){
							
							if(doneEditItemQTY == 0)
							{
								alert("Request QTY larg than the Offer QTY, please check the Offer QTY");
								$("#EditsupplyRequest").css("border-color","red");
								setTimeout(function(){
								   $("#EditsupplyRequest").css("border-color","#EBEBEB");    						
								   $("#EditsupplyRequest").focus();							
								}, 1500);
								$("#saveEditSupplyItemBTN").prop('disabled', false);	
							}
							else if(doneEditItemQTY == 1)
							{
								alert("Data Saved");
								$("#EditsupplyRequest").val('');
								$("#saveEditSupplyItemBTN").prop('disabled', false);	
								$(".editForm").hide();
								$('.ShowData').html('');
								$(".myModal").modal('toggle');
								$('.SuppOrderEdit').html('');
								$('.SuppOrderEdit').load("dist/php/allSuppOrder.php");
							}
							else
							{
								alert(doneEditItemQTY);
								$("#saveEditSupplyItemBTN").prop('disabled', false);	
							}
							
						}
					});
			}
			
			
			return false;
			});		
    });
 
 
 </script>  
