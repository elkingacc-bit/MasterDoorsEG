<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['jobIdAutoD'];

$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 6;
	$colspan1 = 2;
	$colspan2 = 4;
	$colspan3 = 4;
	
}
else
{
	$diplay = "none";
	$colspan = 4;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
}

$sqlGetItemRef = "SELECT  `id`, `doortype`, `doorspecs`, `motorspecs`, `doorprice`,
	 `doorqty`, `totalprice` FROM `autodoorsoffer` WHERE  `jobid` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableOldDoors' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Door Specs</th>
                <th>Motor Specs</th>
                <th>QTY</th>
				<th style='display:$diplay;'>Price</th>
                <th style='display:$diplay;'>Total</th>
				<th></th>
				<th></th>
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{
	
	echo "
		<tr>
			<td class='col-sm-2'>$resGetItemRef[doortype]</td>
			<td class='col-sm-3'>$resGetItemRef[doorspecs]</td>
			<td class='col-sm-3'>$resGetItemRef[motorspecs]</td>
			<td class='col-sm-1'>$resGetItemRef[doorqty]</td>
			<td class='col-sm-1' style='display:$diplay;'>".number_format($resGetItemRef['doorprice'])."</td>
			<td class='col-sm-2' style='display:$diplay;'>".number_format($resGetItemRef['totalprice'])."</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Edit'>
			<button class='btn btn-link btn-xs editItem' value='$resGetItemRef[id]'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'>
					</i>
			</button>
			</span></td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Remove'>
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
		   <th style='display:$diplay;'></th>
           <th style='display:$diplay;'></th>
           <th></th>
		   <th></th>
    </tfoot>
</table>
	";
}

?>		
      
      
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" class="rowIdJobLoadAllItem"/>
  <input type="text" value="<?php echo $Permissiom?>" style="display:none" id="userPermission"/>
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 
var dept = $("#userPermission").val();
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
			if(dept == "Admin" || dept == "Manager")
			{
        $(api.column( 5 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			}
  		}

});


$(".removeItem").click(function(){
	
	var remItemRowID = $(this).val();
	
	var confRemoveDoor = confirm("confirm remove Item ?");
	var rmDoorJRowId = $(".rowIdJobLoadAllItem").val();
	
	if(confRemoveDoor === true)
	{
		$.ajax({
				
				url:"dist/php/removeDoorFromJob.php",
				type:"POST",
				data:{TRIDDoor:remItemRowID,RJROIFDoor:rmDoorJRowId},
				beforeSend: function(){
				$(".removeItem").prop('disabled', true);	
				},
				success: function(doneRMDoor){
					
					if(doneRMDoor == 1)
					{
						alert("Data Saved");
						$(".addedAutoDoor").html("");
						$(".addedAutoDoor").show("");
						$(".addedAutoDoor").load("dist/php/allAddedAutoDoors.php",{jobIdAutoD:rmDoorJRowId});
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
				
			url:"dist/php/getDoorDataForEdit.php",
			type:"POST",
			data:{DRIDFEdit:DRowIdForEdit},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$(".editItem").prop("disabled", true);
				
			},
			success: function(showDoorDataEdit){
				
				$(".editItem").prop("disabled", false);
				 
			$("#SHType").val(showDoorDataEdit.editDoorType);
			$("#DSpecs").val(showDoorDataEdit.editDoorD);
			$("#MSpecs").val(showDoorDataEdit.editDoorM);
			$("#itemPrice").val(showDoorDataEdit.editDoorPrice);
			$("#itemQty").val(showDoorDataEdit.editDoorQTY);
			$("#Total").val(showDoorDataEdit.editTotalPrice);
			
			$(".AddDoorOfferTR").hide();
			$("#AddShutterBtn").hide();
			$(".addedAutoDoor").hide();
			$(".addedAutoDoor").html(""); 
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