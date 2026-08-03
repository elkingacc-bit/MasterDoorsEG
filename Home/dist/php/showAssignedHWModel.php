<style>
h1 {font-size:14px; font-weight:bold;
}
</style>

<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
	 
$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 10;
	$colspan1 = 2;
	$colspan2 = 4;
	$colspan3 = 4;
	
}
else
{
	$diplay = "none";
	$colspan = 7;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
}	 
 
 $JobRowId = $_POST['ModelJobRID'];
 $ItemRef = trim($_POST['ModelItemHWRef']);
 $ItemRowId = $_POST['ModelItemRID'];
 
	 $sqlGetItemRef = "SELECT `itemname`, `itemqty` FROM `itemoffer` WHERE `id` = $ItemRowId";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
	
$itemName = $resGetItemRef['itemname'];
$itemQTY = $resGetItemRef['itemqty'];
	
?>
 <div class="modal-header">
        <h5 class="modal-title">All Assigned HW for Item <span style="color:blue;"><b><?php echo $itemName; ?>
        </b></span> Group Reference:&nbsp;
		<button class="btn btn-link ChangeItemRef" value="<?php echo $ItemRowId;?>">
		<span data-toggle='tooltip' data-placement='top' title='Change Group Ref'
        style="font-size:16px;"><b><?php echo $ItemRef; ?></span></button></b>
        </h5>
       <button class="btn btn-xs btn-link close" data-toggle='tooltip' data-placement='top' title='Add New HW'
       id="AddMoreHw">
        <i class='fa fa-plus-square' aria-hidden='true' style='font-size:20px;color:#0275d8; font-weight:bold'>
        </i>
        </button>
        
      </div>
       <div class="modal-body ">
       
       <div class="EditQTYDiv" style="display:none; width:50%">
       		
            <table class="table table-sm">
            <thead class="bg-info">
            	<th>New QTY</th>
            </thead>
            <tbody>    
                <tr>
                	<td>
                    <input type="number" class="form-control editedQTY" value="" min="1" id="editedQTY"/>
                    </td>
                </tr>
                <tr>
                	<td align="center">
                    <button class="btn btn-success btn-sm" id="saveEditItemQTYBTN">Save</button>
                    </td>
                </tr>
             </tbody>   
            </table>
       	
       </div>
       
       <div class="editPrice" style="display:none;">
       	<table class="table table-sm">
        	<th>Cost</th>
            <th>Other Cost</th>
            <th>Overhead</th>
            <th>Price</th>
        	<tr>
            	<td>
                	<input type="number" class="form-control-plaintext hwCost" min="0.01" step="0.01" />
                </td>
                <td>
                	<input type="number" class="form-control hwOtherCost" min="0" step="0.01" value="0">
                </td>
                <td >
                <div class="input-group">
                  <input type="number" class="form-control hwOverhead" id="hwOverhead" aria-label="%" 
                  list="hwOverheadList" min="1">
                  <datalist id="hwOverheadList">
                  <?php 
                    for($p = 1; $p <= 400; $p++)
                    {
                        echo "<option value='$p'>";
                    }
                  
                  ?>
                  </datalist>
                  <div class="input-group-append">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
            </td>
            <td>
                	<input type="number" class="form-control-plaintext hwPrice" min="0.01" step="0.01" 
                    readonly>
                </td>
            </tr>
            <tr>
            	<td colspan="4" align="center">
                	<button class="btn btn-sm btn-success" id="editPriceBTN">Save</button>
                </td>
            </tr>
        </table>
       </div>
       
       <div class="changeItemRefDiv" style="display:none">
       	 
        	<table class="table table-sm" style="width:50%">
            	
                <td>
                	<input type="text" class="form-control ItemRefForEdit" id="ItemRefForEdit"
                    value="<?php echo trim($ItemRef); ?>" autocomplete="off" />
                </td>
                <tr>
                <td align="center">
                	<button class="btn btn-success btn-sm" id="saveEditItemRefBTN">Save</button>
                </td>
                </tr>
            	
            </table>
       	
       </div>
      <div class="addNewHW"> 
<table class="table table-sm myTableHW"  style="width:100%">
   
      <thead class="bg-info">
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th style="display:<?php echo $diplay;?>">Price</th>
        <th style="display:<?php echo $diplay;?>">Total</th>
        <th></th>
        <th></th>
         <th style="display:<?php echo $diplay;?>"></th>
      </thead>
      <tbody>

<?php 
 $sqlGetItem="SELECT `offproId`, `descripcode`, `descripquantity`, `unitPrice`, `totalprice` FROM 
 `offerproperties` WHERE `ioidref` = $ItemRowId";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
while($resGetItem = mysqli_fetch_assoc($queryGetItem))
{
    $unitPrice = $resGetItem['unitPrice'];
    $TotalPrice = $resGetItem['totalprice'];
    
   /* if($unitPrice == 0)
    {
     $sqlGetItemPrice = "SELECT `sales` FROM `lookupstock` WHERE `descriptioncode` = $resGetItem[descripcode] ";
    	$queryGetItemPrice = mysqli_query($link,$sqlGetItemPrice)or die("ERROR :02-ANJ_GCN_S");
    	$resGetItemPrice = mysqli_fetch_assoc($queryGetItemPrice);
    	 $unitPrice = $resGetItemPrice['sales'];
     	$TotalPrice = round($resGetItem['descripquantity'] * $unitPrice);
    	
    	 $sqlUpdateItemHW = "UPDATE `offerproperties` SET `unitPrice` = '$unitPrice', `totalprice` = '$TotalPrice' 
         WHERE `offproId` = $resGetItem[offproId]";
         mysqli_query($link,$sqlUpdateItemHW)or die("ERROR :05-ANJ_GCN_S");
    	
    	$sqlGetJobNum = "SELECT  `offerValue` FROM `job` WHERE `jobId` = $JobRowId";
        $queryGetJobNum = mysqli_query($link,$sqlGetJobNum)or die("ERROR :04-ANJ_GCN_S");
        $resGetJobNum = mysqli_fetch_assoc($queryGetJobNum);
        
        $offerVal = $resGetJobNum['offerValue'];
       
        
        $offerVal = ($offerVal + $TotalPrice);
        
         $sqlUpdateOfferStatus = "UPDATE `job` SET `offerValue` = '$offerVal' WHERE `jobId` = $JobRowId";
         mysqli_query($link,$sqlUpdateOfferStatus)or die("ERROR :05-ANJ_GCN_S");
    }*/
        
	$sqlGetHWName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` = 
	$resGetItem[descripcode]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :02-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);

	 $HwQTY = ($resGetItem['descripquantity'] / $itemQTY);
	 $totalHWPrice = round($HwQTY * $unitPrice); 
echo "
		<tr>
			<td>$resGetHWName[partnumber]</td>
			<td>$resGetHWName[descriptionname]</td>
			<td data-toggle='tooltip' data-placement='left' title='Total HW= $resGetItem[descripquantity]'>"
			.$HwQTY."</td>
			<td style='display:$diplay;'>".number_format($unitPrice)."</td>
			<td style='display:$diplay;'>".number_format(($totalHWPrice))."</td>
			<td>
			<button class='btn btn-link btn-xs qtyEditHw' value='$resGetItem[offproId],".($resGetItem['descripquantity'] / $itemQTY)."'
					data-toggle='tooltip' data-placement='top' title='Edit HW QTY'>
					<i class='far fas fa-wrench' aria-hidden='true' style='font-size:14px;color:#0275d8'>
					</i></button>
			</td>
			<td>
			<button class='btn btn-link btn-xs removeHw' value='$resGetItem[offproId]'
					data-toggle='tooltip' data-placement='top' title='Remove'>
					<i class='far fa-trash-alt' aria-hidden='true' style='font-size:14px;color:#d9534f'>
					</i></button>
			</td>
			<td style='display:$diplay;'>
			<button class='btn btn-link btn-xs editHW' value='$resGetItem[offproId]'
					data-toggle='tooltip' data-placement='top' title='Edit Price'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:14px;color:#0275d8'>
					</i></button>
			</td>
		</tr>
	
	";
}
 }
 ?>
  </tbody>
      <tfoot class="bg-light">
       	<th></th>
        <th></th>
        <th></th>
        <th style="display:<?php echo $diplay;?>"></th>
        <th style="display:<?php echo $diplay;?>"></th>
        <th></th>
        <th></th>
        <th style="display:<?php echo $diplay;?>"></th>
      </tfoot>
 </table>
 
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoad"/>
  <input type="text" value="<?php echo $itemName?>" style="display:none" id="ItemNameLoad"/>
  <input type="text" value="<?php echo $ItemRef?>" style="display:none" id="itemRef"/>
  <input type="text" value="<?php echo $ItemRowId?>" style="display:none" id="itemRowIdM"/>
  <input type="text" value="" style="display:none" id="HWRowId"/>
  </div>
  </div>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  

var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();	
				
				
	  var table3 = $('.myTableHW').DataTable( {
	 
	  		 fixedHeader: false,
             //scrollY:'25vh',
			 deferRender:true,
			 //scrollX: true,
        	 //scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: false ,
		  
   dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'All_HW_Offers '+datetime,
			filename: function () {
			return "All_HW_Offers" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_HW_Offers '+datetime,
			 filename: function () {
			return "All_HW_Offers" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [0,1,2]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Maintenance Tracker System {Master Doors EG} | All HW in Offers '+datetime,
	  footer: true,
	  //orientation: 'landscape',
	   exportOptions: {
		   		   
                   columns: [0,1,2]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.2; filter: alpha(opacity=15); width: 450px; height:200px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
	}
 ],			 

   "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 4 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 4, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 4 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

});


$(".removeHw").click(function(){
	
	var remRowID = $(this).val();
	
	var confRemove = confirm("confirm remove HW from Item?");
	var rmJRowId = $("#rowIdJobLoad").val();
	var rmJItemName = $("#ItemNameLoad").val();
	var rmJItemRID = $("#itemRowIdM").val();
	var rmJItemRef = $("#itemRef").val();
	
	if(confRemove === true)
	{
		$.ajax({
				
				url:"dist/php/removeHWfromItem.php",
				type:"POST",
				data:{TRIDHW:remRowID,RJROIF:rmJRowId,itemNameHWRem:rmJItemName,itemRowIdRem:rmJItemRID},
				beforeSend: function(){
				$(".removeHw").prop('disabled', true);	
				},
				success: function(doneRMHW){
					 
					if(doneRMHW == 1)
					{
						alert("Data Saved");
						$('.ShowHWData').html('');
						setTimeout(function(){
							//$(".myModal").modal('toggle');
						$('.ShowHWData').load('dist/php/showAssignedHWModel.php',{ModelJobRID:rmJRowId, ModelItemHWRef:rmJItemRef, ModelItemRID:rmJItemRID});
						
						$(".oldAddItems").html("");
							//$(".HWadded").show("");
						$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:rmJRowId});
							
						}, 500);
						$(".TotalOffer").html('');
						$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:rmJRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
						
					}
					else
					{
						alert(doneRMHW);
						$(".removeHw").prop('disabled', false);	
					}
					
					
				}
			
			});
	}
	
	return false; 
	});
	
	
	$(".qtyEditHw").click(function(){
		
		$(".EditQTYDiv").show();
		$(".addNewHW").hide();
		
		var HWRowIdForEditInput = $(this).val().split(',')[0];
		var HWQTYForEditInput = $(this).val().split(',')[1];
		
		$("#HWRowId").val(HWRowIdForEditInput);
		$("#editedQTY").val(HWQTYForEditInput);
		
		return false;
		});	
	
$("#saveEditItemQTYBTN").click(function(){

	//var editRowID = $(this).val();
	
	//var confRemove = confirm("confirm remove HW from Item?");
	var editJRowId = $("#rowIdJobLoad").val();
	var editJItemName = $("#ItemNameLoad").val();
	var editJItemRID = $("#itemRowIdM").val();
	var editJItemRef = $("#itemRef").val();
	var newEditQTY = $("#editedQTY").val();
	var EditHWRowId = $("#HWRowId").val();
	
	//alert(editJItemRef);
		$.ajax({
				
				url:"dist/php/EditQTYHWfromItem.php",
				type:"POST",
				data:{TRIDHW:EditHWRowId,RJROIF:editJRowId,itemNameHWRem:editJItemName,itemRowIdRem:editJItemRef,itemRowIdEdit:editJItemRID,NewQty:newEditQTY},
				beforeSend: function(){
				$("#saveEditItemQTYBTN").prop('disabled', true);	
				},
				success: function(doneEditHWQTY){
					 
					if(doneEditHWQTY == 1)
					{
						alert("Data Saved");
						$('.ShowHWData').html('');
						setTimeout(function(){
							//$(".myModal").modal('toggle');
						$('.ShowHWData').load('dist/php/showAssignedHWModel.php',{ModelJobRID:editJRowId, ModelItemHWRef:editJItemRef, ModelItemRID:editJItemRID});
						
						$(".oldAddItems").html("");
							//$(".HWadded").show("");
						$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:editJRowId});
							
						}, 500);
						$(".TotalOffer").html('');
						$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:editJRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
						
					}
					else
					{
						alert(doneEditHWQTY);
						$("#saveEditItemQTYBTN").prop('disabled', false);	
					}
					
					
				}
			
			});
	
	
	return false; 
	});
	
	
	$("#AddMoreHw").click(function(){
		
		var addHWJRowId = $("#rowIdJobLoad").val();
		var addHWItemName = $("#ItemNameLoad").val();
		var addHWItemRef = $("#itemRef").val();
		var addHWItemRID = $("#itemRowIdM").val();
		
		$(".addNewHW").html('');
		$(".addNewHW").load('dist/php/addMoreHWtoItemModel.php',{AHWJRID:addHWJRowId, AHWIRef:addHWItemRef,AHWIName:addHWItemName,AHWIRID:addHWItemRID});
		 
		
		return false;
		});
		
		
	$(".ChangeItemRef").click(function(){
		
		$(".changeItemRefDiv").show();
		$(".addNewHW").hide();
		
		return false;
		});	
		
	$("#saveEditItemRefBTN").click(function(){
		
		var changeItemRef = $("#ItemRefForEdit").val();
		var changeRefItemRID = $("#itemRowIdM").val();
		var changeJRowId = $("#rowIdJobLoad").val();
		var changeJItemName = $("#ItemNameLoad").val();
		
		if(changeItemRef == "")
		{
			alert('missing field');
			$('.ItemRefForEdit').css("border-color","red");
			setTimeout(function(){
           		$('.ItemRefForEdit').css("border-color","#EBEBEB");
				$(".ItemRefForEdit").focus();				
				}, 1500);
		}
		else
		{
			$.ajax({
					url:"dist/php/saveChangeItemRef.php",
					type:"POST",
					data:{chgeItemRefVal:changeItemRef,chgeItemRowId:changeRefItemRID,chgeJRob:changeJRowId,chgeItemName:changeJItemName},
					
					beforeSend: function(){
						$("#saveEditItemRefBTN").prop('disabled', true);
						
					},
					success: function(doneChangeItemRef)
					{
						if(doneChangeItemRef == 1)
						{
							alert("Data Saved");
							$(".changeItemRefDiv").hide();
							$('.ShowHWData').html('');
							$('.ShowHWData').load('dist/php/showAssignedHWModel.php',{ModelJobRID:changeJRowId, ModelItemHWRef:changeItemRef, ModelItemRID:changeRefItemRID});
							
						}
						else
						{
							alert(doneChangeItemRef);
							$("#saveEditItemRefBTN").prop('disabled', false);
						}
					}
				
				});
		}
		
		
		return false;
		});	
		
		
	$(".editHW").click(function(){
		
		var hwPriceID = $(this).val();
		var tableEdit = $(".myTableHW");
		
		var index = 1;
		 $(this).closest('tr').addClass('text-primary');
		 
		 $.ajax({
			 	url:"dist/php/getHWCostEdit.php",
				type:"POST",
				data:{hwTableRowId:hwPriceID},
				success: function(displayCost){
					var showHWCost = displayCost.replace(/^\s+|\s+$|\s+(?=\s)/g, "")
					$(".hwCost").val(showHWCost);	
				}
			 
			 });
		 
		 $("#HWRowId").val("");
		 $("#HWRowId").val(hwPriceID);
		 $(".editPrice").show();
		return false;
		});	
		
		$("#hwOverhead").change(function(){
			
			var overH = $(this).val();
			var CostH = $(".hwCost").val();
			var otherCostH = $(".hwOtherCost").val();
			if(overH != "" && CostH != "" && otherCostH != "")
			{
				overH = Number(overH);
				CostH = Number(CostH);
				otherCostH = Number(otherCostH);
				overH = parseFloat(overH / 100).toFixed(2);
				newCost = (CostH + otherCostH);
				overHeadVal = (newCost * overH);
				newPrice = parseFloat(overHeadVal + newCost).toFixed(1);
				
				$(".hwPrice").val(newPrice).css("font-weight","bold");
			
			}
			
			return false; 
			});
			
			
	$("#editPriceBTN").click(function(){
		
		var editedPriceRID = $("#HWRowId").val();
		var EditNewPrice = $(".hwPrice").val();
		var editOverH = $("#hwOverhead").val();
		var editCostH = $(".hwCost").val();
		var editOtherCostH = $(".hwOtherCost").val();
		var EditJRowId = $("#rowIdJobLoad").val();
		var EditJItemName = $("#ItemNameLoad").val();
		var EditJItemRID = $("#itemRowIdM").val();
		var EditJItemRef = $("#itemRef").val();
		
		if(editCostH == "" )
			{
			alert('missing field');
			$('.hwCost').css("border-color","red");
			setTimeout(function(){
           		$('.hwCost').css("border-color","#EBEBEB");
				$(".hwCost").focus();				
				}, 1500);
								
			}
		else if(editOtherCostH == "" )
			{
			alert('missing field');
			$('.hwOtherCost').css("border-color","red");
			setTimeout(function(){
           		$('.hwOtherCost').css("border-color","#EBEBEB");
				$(".hwOtherCost").focus();				
				}, 1500);
								
			}
		else if(editOverH == "" )
			{
			alert('missing field');
			$('.hwOverhead').css("border-color","red");
			setTimeout(function(){
           		$('.hwOverhead').css("border-color","#EBEBEB");
				$(".hwOverhead").focus();				
				}, 1500);
								
			}		
		else
			{
				$.ajax({
						url:"dist/php/saveHWNewPrice.php",
						type:"POST",
						data:{hwRowIdEdit:editedPriceRID,itemCostEdit:editCostH,overCostEdit:editOtherCostH,overheadEdit:editOverH,hwPriceEdit:EditNewPrice,hwJobRowId:EditJRowId, hwLinkedItem:EditJItemName},
						beforeSend: function(){
						$("#editPriceBTN").prop('disabled', true);
						
					},
					success: function(doneAddedNewPrice)
					{
						if(doneAddedNewPrice == 1)
						{
							alert("Data Saved");
							$('.ShowHWData').html('');
							$('.ShowHWData').load('dist/php/showAssignedHWModel.php',{ModelJobRID:EditJRowId, ModelItemHWRef:EditJItemRef, ModelItemRID:EditJItemRID});
							
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:EditJRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							
							$(".oldAddItems").html('');
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:EditJRowId});
						}
						else
						{
							alert(doneAddedNewPrice);
							$("#editPriceBTN").prop('disabled', false);
						}
					}
						
					});
			}
		
		
		
		return false;
		});		
	
});
 
 </script>