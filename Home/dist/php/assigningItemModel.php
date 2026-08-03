<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $itemRowId = $_POST['ModelItemRID'];
 
	 $sqlGetItemRef = "SELECT `itemname` FROM `itemoffer` WHERE `id` = $ItemRowId";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
	
$itemName = $resGetItemRef['itemname'];
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Add Supply order Item <span style="color:blue;"><b><?php echo $itemName; ?>
        </b></span> Group Reference:&nbsp;<span style="color:blue;"><b><?php echo $ItemRef; ?></b></span></h5>
       <button class="btn btn-xs btn-link close" data-toggle='tooltip' data-placement='top' title='Add New HW'
       id="AddMoreHw">
        <i class='fa fa-plus-square' aria-hidden='true' style='font-size:20px;color:#0275d8; font-weight:bold'>
        </i>
        </button>
        
      </div>
       <div class="modal-body addNewHW">
<table class="table table-sm myTableHW"  style="width:100%">
   
      <thead class="bg-info">
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th>Price</th>
        <th>Total</th>
        <th></th>
      </thead>
      <tbody>

<?php 
 $sqlGetItem="SELECT `offproId`, `descripcode`, `descripquantity`, `unitPrice`, `totalprice` FROM 
 `offerproperties` WHERE `ioidref` = $ItemRowId";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
while($resGetItem = mysqli_fetch_assoc($queryGetItem))
{

	$sqlGetHWName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` = 
	$resGetItem[descripcode]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :02-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);

echo "
		<tr>
			<td>$resGetHWName[partnumber]</td>
			<td>$resGetHWName[descriptionname]</td>
			<td>$resGetItem[descripquantity]</td>
			<td>".number_format($resGetItem['unitPrice'])."</td>
			<td>".number_format($resGetItem['totalprice'])."</td>
			<td>
			<button class='btn btn-link btn-xs removeHw' value='$resGetItem[offproId]'
					data-toggle='tooltip' data-placement='top' title='Remove'>
					<i class='far fa-trash-alt' aria-hidden='true' style='font-size:20px;color:#d9534f'>
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
        <th></th>
        <th></th>
        <th></th>
      </tfoot>
 </table>
 </div>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoad"/>
  <input type="text" value="<?php echo $itemName?>" style="display:none" id="ItemNameLoad"/>
  <input type="text" value="<?php echo $ItemRef?>" style="display:none" id="itemRef"/>
  <input type="text" value="<?php echo $ItemRowId?>" style="display:none" id="itemRowIdM"/>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  
	  var table3 = $('.myTableHW').DataTable( {
	 
	  		 fixedHeader: false,
             //scrollY:'25vh',
			 deferRender:true,
			 //scrollX: true,
        	 //scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: false ,
		  
 
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
	
	$("#AddMoreHw").click(function(){
		
		var addHWJRowId = $("#rowIdJobLoad").val();
		var addHWItemName = $("#rowIdItemLoad").val();
		var addHWItemRef = $("#itemRef").val();
		var addHWItemRID = $("#itemRowIdM").val();
		
		$(".addNewHW").html('');
		$(".addNewHW").load('dist/php/addMoreHWtoItemModel.php',{AHWJRID:addHWJRowId, AHWIRef:addHWItemRef,AHWIName:addHWItemName,AHWIRID:addHWItemRID});
		
		
		return false;
		});
	
});
 
 </script>