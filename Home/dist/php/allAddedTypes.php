<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['jobIdAutoD'];

$sqlGetItemRef = "SELECT  `id`, `type`, `price`, `typeqty`, `totalprice` FROM `maintoffers` 
WHERE `jobid` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

/*
<th>Width</th>
				<th>Height</th>
				<th>Depth</th>
				
<td class='col-sm-3'>$resGetItemRef[hights]</td>
			<td class='col-sm-3'>$resGetItemRef[widths]</td>
			<td class='col-sm-3'>$resGetItemRef[depths]</td>				

*/

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableOldDoors' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th class='col-sm-5'>Type</th>
				<th class='col-sm-1'>QTY</th>
				<th class='col-sm-2'>Price</th>
				
				<th class='col-sm-2'>Total</th>
				<th class='col-sm-0'></th>
				<th class='col-sm-0'></th>
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{

	echo "
		<tr>
			<td class='col-sm-5'>$resGetItemRef[type]</td>
			
			<td class='col-sm-1'>$resGetItemRef[typeqty]</td>
			<td class='col-sm-2'>".number_format($resGetItemRef['price'])."</td>
			<td class='col-sm-2'>".number_format($resGetItemRef['totalprice'])."</td>
			<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Edit'>
			<button class='btn btn-link btn-xs editItem' value='$resGetItemRef[id]'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'>
					</i>
			</button>
			</span></td>
			<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Remove'>
			<button class='btn btn-link btn-xs removeItem' value='$resGetItemRef[id]'>
					<i class='far fa-trash-alt' aria-hidden='true' style='font-size:16px;color:#d9534f'>
					</i>
			</button>
			</span></td>
		</tr>
	
	";
}
	
	echo "
	</tbody>
	<tfoot class='bg-light'>
       	   <th></th>
		   <th></th>
           <th></th>
           <th></th>
		   <th></th>
    </tfoot>
</table>
	";
}

?>		
      
      
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" class="rowIdJobLoadAllItem"/>
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 
	  var table = $('.myTableOldDoors').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: true ,
		  
 
   "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 5 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 5, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 5 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

});


$(".removeItem").click(function(){
	
	var remItemRowID = $(this).val();
	
	var confRemoveDoor = confirm("confirm remove Item ?");
	var rmDoorJRowId = $(".rowIdJobLoadAllItem").val();
	
	if(confRemoveDoor === true)
	{
		$.ajax({
				
				url:"dist/php/removeTypeFromJob.php",
				type:"POST",
				data:{TRIDDoor:remItemRowID,RJROIFDoor:rmDoorJRowId},
				beforeSend: function(){
				$(".removeItem").prop('disabled', true);	
				},
				success: function(doneRMDoor){
					
					if(doneRMDoor == 1)
					{
						alert("Data Saved");
						$(".addedMaintDoor").html("");
						$(".addedMaintDoor").show("");
						$(".addedMaintDoor").load("dist/php/allAddedTypes.php",{jobIdAutoD:rmDoorJRowId});
						$(".removeItem").prop('disabled', false);	
						$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:rmDoorJRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
					}
					else
					{
						alert(doneRMDoor);
						$(".removeItem").prop('disabled', false);	
					}
					
					
				}
			
			});
	}
	
	return false; 
	});
	
	$(".editItem").click(function(){
		
		var DRowIdForEdit = $(this).val();
		
		$.ajax({
				
			url:"dist/php/getFreeOfferForEdit.php",
			type:"POST",
			data:{DRIDFEdit:DRowIdForEdit},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$(".editItem").prop("disabled", true);
				
			},
			success: function(showMaintDataEdit){
				
				$(".editItem").prop("disabled", false);
				 
			$("#SHType").val(showMaintDataEdit.editType);
			//$("#heights").val(showMaintDataEdit.editHright);
			//$("#widths").val(showMaintDataEdit.editWedth);
			//$("#depths").val(showMaintDataEdit.ediDepth);
			$("#itemPrice").val(showMaintDataEdit.editPrice);
			$("#itemQty").val(showMaintDataEdit.editQTY);
			$("#Total").val(showMaintDataEdit.editTotalPrice);
			
			$(".AddDoorOfferTR").hide();
			$("#AddMaintBtn").hide();
			$(".addedMaintDoor").hide();
			$(".EditDoorOfferTR").show();
			$("#rowIdDoorForEdit").val('');
			$("#rowIdDoorForEdit").val(DRowIdForEdit);
			$(".backBTN").hide();
			$(".backBTN2").show();
			
			}
		
		});
		return false;
		});
	
});
 
 </script>