<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$jobRowId = $_POST['SjobTableRId'];
$SuppOrderRowId = $_POST['SupplyORID'];
$SuppOrderType = $_POST['SupplyOrderType'];
$itemRID = $_POST['IRowId'];

if($SuppOrderType == "Doors")
{
	$th2= "Item";
	$th3= "M<sup>2</sup>";
}
else if($SuppOrderType == "Automatic")
{
	$th2= "Door Specs";
	$th3= "Motor Specs";
}

?>
 <input type="text" value="<?php echo $SuppOrderRowId;?>" style="display:none" id="ModelSuppRowId"/>
  <input type="text" value="<?php echo $SuppOrderType;?>" style="display:none" id="ModelOrderType"/>
  <input type="text" value="<?php echo $jobRowId;?>" style="display:none" id="ModelJobId"/>
  <input type="text" value="<?php echo $itemRID;?>" style="display:none" id="ModelItemId"/>
  
<div class="table-responsive-sm">
 <table class=" table table-sm" cellspacing="0" width="99%">
    
    
    
		<?php
			
		if($SuppOrderType == "Doors")
		{
				echo "
					<thead class='bg-warning'>
						  <th>Type</th>
						  <th>Item</th>
						  <th data-toggle='tooltip' data-placement='left' 
						  title='Door Number'>D.No</th>
						  <th data-toggle='tooltip' data-placement='left' 
						  title='Width'>W</th>
						  <th data-toggle='tooltip' data-placement='left' 
						   title='Hight'>H</th>
						  <th data-toggle='tooltip' data-placement='left' 
						   title='Width'>D</th>
						  <th>M<sup>2</sup></th>
						  <th>Handle</th>
						  <th data-toggle='tooltip' data-placement='left' 
						   title='Offer Quantity'>QTY</th>
						  <th>Order QTY</th>
					</thead>
					<tbody>
				
				";
				
				$sqlGetItemData = "SELECT `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth` 
				,`itemm2`, `itemqty`, `handling`, `doorNumber` FROM `itemoffer` WHERE `id` = $itemRID";
				$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
				$resGetItemData= mysqli_fetch_array($queryGetItemData);
			
			
				$sqlGetTRecevQTY="SELECT SUM(`qty`) AS qty FROM `supporderitems` WHERE `ItemRowId` = $itemRID ";
				$queryGetTRecevQTY=mysqli_query($link,$sqlGetTRecevQTY)or die("ERROR :01-AU_AU_S"
				.mysqli_error($link));
				$resGetTRecevQTY= mysqli_fetch_assoc($queryGetTRecevQTY);
				if( $resGetTRecevQTY['qty'] == "")
				{
					 $reminingQTY = $resGetItemData['itemqty'];
				}
				else
				{
					
					 $reminingQTY = ($resGetItemData['itemqty'] - $resGetTRecevQTY['qty']);
				}	
				
				echo "
					<td class='col-sm-1' class='ItemTypeTh'>$resGetItemData[itemtype]</td>
					<td class='col-sm-2'>$resGetItemData[itemname]</td>
					<td class='col-sm-1'>$resGetItemData[doorNumber]</td>
					<td class='col-sm-1'>$resGetItemData[itemwidth]</td>
					<td class='col-sm-1'>$resGetItemData[itemhight]</td>
					<td class='col-sm-1'>$resGetItemData[itemdepth]</td>
					<td class='col-sm-1'>$resGetItemData[itemm2]</td>
					<td class='col-sm-1'>$resGetItemData[handling]</td>
					<td class='col-sm-1'>$resGetItemData[itemqty]</td>
					<td class='col-sm-1'>
						<input type='number' id='supplyRequest' class='form-control' min='1' 
						max='$reminingQTY' data-toggle='tooltip' data-placement='left' 
						title='Remaining QTY: $reminingQTY' />
					</td>
	</tbody>
	</table> 			
				";
				
				
						
			
		}
		else if($SuppOrderType == "Automatic")
		{
				
				echo "
					<thead class='bg-warning'>
						  <th>Type</th>
						  <th>Door Specs</th>
						  <th>Motor Specs</th>
						  <th data-toggle='tooltip' data-placement='left' 
						   title='Offer Quantity'>QTY</th>
						  <th>Order QTY</th>
					</thead>
					<tbody>
			";
			 
			$sqlGetItemData = "SELECT `doortype`, `doorspecs`, `motorspecs`,`doorqty` FROM `autodoorsoffer`
			 WHERE `id` = $itemRID";
			 $queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
			 
			$sqlGetTRecevQTY="SELECT SUM(`qty`) AS qty FROM `supporderitems` WHERE `ItemRowId` = $itemRID 
			AND `SOIdRef` = $SuppOrderRowId";
			$queryGetTRecevQTY=mysqli_query($link,$sqlGetTRecevQTY)or die("ERROR :01-AU_AU_S"
			.mysqli_error($link));
			$resGetTRecevQTY= mysqli_fetch_assoc($queryGetTRecevQTY);
			if( $resGetTRecevQTY['qty'] == "")
			{
				 $reminingQTY = $resGetItemData['doorqty'];
			}
			else
			{
				
				 $reminingQTY = ($resGetItemData['doorqty'] - $resGetTRecevQTY['qty']);
			}
			
			
			echo "
					<td class='col-sm-1' class='ItemTypeTh'>$resGetItemData[doortype]</td>
					<td class='col-sm-2'>$resGetItemData[doorspecs]</td>
					<td class='col-sm-1'>$resGetItemData[motorspecs]</td>
					<td class='col-sm-1'>$resGetItemData[doorqty]</td>
					<td class='col-sm-1'>
						<input type='number' id='supplyRequest' class='form-control' min='1' 
						max='$reminingQTY' data-toggle='tooltip' data-placement='left' 
						title='Remaining QTY: $reminingQTY' />
					</td>
	</tbody>
	</table> 			
			
			
			";
		}

?>
        
       
    
 
 <center>
 <button class="btn btn-success btn-sm" id="saveAddSupplyItemBTN">Save</button>
 </center>
  <input type="number" value="<?php echo $reminingQTY;?>" style="display:none" id="maxQTY"/>
 <script type="text/javascript">
 	$(document).ready(function() {
 
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
        
		$("#saveAddSupplyItemBTN").click(function(){
			
			var suppOrderIdSave = $("#ModelSuppRowId").val();
			var ItemRowIdSave = $("#ModelItemId").val();
			var orderTypeSave = $("#ModelOrderType").val();
			var jobRowIdSave = $("#ModelJobId").val();
			var itemQTYSave = $("#supplyRequest").val();
			var itemMaxQTYSave = $("#maxQTY").val();
			
			
			if(itemQTYSave == 0 || itemQTYSave == "")
			{
				alert('Please Add Supply QTY');
				$("#supplyRequest").css("border-color","red");
				setTimeout(function(){
				   $("#supplyRequest").css("border-color","#EBEBEB");    						
				   $("#supplyRequest").focus();							
				}, 1500);
			}
			else if(itemQTYSave > itemMaxQTYSave)
			{
				alert('Supply QTY large than the offer QTY');
				$("#supplyRequest").css("border-color","red");
				setTimeout(function(){
				   $("#supplyRequest").css("border-color","#EBEBEB");    						
				   $("#supplyRequest").focus();							
				}, 1500);
			}
			
			else
			{
				$.ajax({
						url:"dist/php/saveAddSupplyItemQTY.php",
						type:"POST",
						data:{SOIdRequ:suppOrderIdSave,IRIdRequ:ItemRowIdSave,OTRequ:orderTypeSave,JRIdRequ:jobRowIdSave,IQRequ:itemQTYSave},
						beforeSend: function(){
							$("#addItemHWBtn").prop('disabled', true);	
						},
						success: function(doneSaveSupplyItemQTY){
							
							if(doneSaveSupplyItemQTY == 0)
						{
							alert("Item Name Is Already existing in this Offer.!");
							$('.ItemTypeTh').css("border-color","red");
							setTimeout(function(){
								$('.ItemTypeTh').css("border-color","#EBEBEB");
											
							}, 1500);
						}
						else if(doneSaveSupplyItemQTY == 1)
						{
							alert("Data Saved");
							$('.ShowData').html('');
							$(".myModal").modal('toggle');
							
							$('.SuppOrderEdit').html('');
							$('.SuppOrderEdit').load("dist/php/allSuppOrder.php");
						}
						else
						{
							alert(doneSaveSupplyItemQTY);
							$("#addItemHWBtn").prop('disabled', false);
						}
							
						}
					
					
					});
				
			}
			
			
			return false;
			});
		
    });
 
 
 </script>  
