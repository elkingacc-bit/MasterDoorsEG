<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['OIJRID'];

$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 5;
	$colspan1 = 2;
	$colspan2 = 4;
	$colspan3 = 4;
	
}
else
{
	$diplay = "none";
	$colspan = 3;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
}

$sqlGetHW = "SELECT `id`, `descripcode`, `descripqty`, `descripprice`, `totalprice`, `jobref`, `ref` FROM
 `stockoffers` WHERE `jobref` = $jobRowId";
$queryGetHW = mysqli_query($link,$sqlGetHW)or die("ERROR :02-ANJ_GCN_S");
if(mysqli_num_rows($queryGetHW) > 0)
{
echo "

	<table class='table table-sm myTableItems'  style='width:100%'>
   
      <thead class='bg-dark'>
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th style='display:$diplay'>Price</th>
        <th style='display:$diplay'>Total</th>
        <th></th>
        <th></th>
      </thead>
      <tbody>

";	

while($resGetHW = mysqli_fetch_assoc($queryGetHW))
{
	$sqlGetItemDate = "SELECT `descriptionname`, `partnumber` FROM `stockitems` 
	WHERE `description` =$resGetHW[descripcode]";
	$queryGetItemDate = mysqli_query($link,$sqlGetItemDate)or die("ERROR :03-ANJ_GCN_S");
	$resGetItemDate = mysqli_fetch_assoc($queryGetItemDate);
	
	$descName = $resGetItemDate['descriptionname'];	
	$PartNum = $resGetItemDate['partnumber'];
	
	echo "
		<tr>
			<td>$PartNum</td>
			<td>$descName</td>
			<td>$resGetHW[descripqty]</td>
			<td style='display:$diplay'>".number_format($resGetHW['descripprice'])."</td>
			<td style='display:$diplay'>".number_format($resGetHW['totalprice'])."</td>
			<td>
			<button class='btn btn-link btn-xs editStockOffer' value='$resGetHW[id]'
					data-toggle='tooltip' data-placement='left' title='Edit'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'>
					</i></button>
			</td>
			<td>
			<button class='btn btn-link btn-xs removeHw' value='$resGetHW[id]'
					data-toggle='tooltip' data-placement='left' title='Remove'>
					<i class='far fa-trash-alt' aria-hidden='true' style='font-size:20px;color:#d9534f'>
					</i></button>
			</td>
		</tr>
	
	";
}

echo "

	</tbody>
      <tfoot class='bg-light'>
       	<th></th>
        <th></th>
        <th></th>
        <th style='display:$diplay'></th>
        <th style='display:$diplay'></th>
        <th></th>
        <th></th>
      </tfoot>
 </table>

";
}
?>		
      
 
 <input type="text" value="<?php echo $jobRowId?>" style="display:none" id="rowIdJobLoad"/>
  <input type="text" value="<?php echo $Permissiom?>" style="display:none" id="userPermission"/>
 <script type="text/javascript">
 $(document).ready(function() {
   
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 
var dept = $("#userPermission").val();
	  var table2 = $('.myTableItems').DataTable( {
	 
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
			if(dept == "Admin" || dept == "Manager")
			{
        $(api.column( 4 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			}
  		}

});


$(".removeHw").click(function(){
	
	var remRowID = $(this).val();
	
	var confRemove = confirm("confirm remove Item from Offer?");
	var rmJRowId = $("#rowIdJobLoad").val();
	
	if(confRemove === true)
	{
		$.ajax({
				
				url:"dist/php/removeStockItemFromOffer.php",
				type:"POST",
				data:{TRIDHW:remRowID,jobRowId:rmJRowId},
				beforeSend: function(){
				$(".removeHw").prop('disabled', true);	
				},
				success: function(doneRMSI){
					
					if(doneRMSI == 1)
					{
						alert("Data Saved");
						$(".addedStock").html("");
						$(".addedStock").show("");
						$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:rmJRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
						$(".addedStock").load("dist/php/allAddedStockinOffer.php",{OIJRID:rmJRowId});
						$(".removeHw").prop('disabled', false);	
					}
					else
					{
						alert(doneRMSI);
						$(".removeHw").prop('disabled', false);	
					}
					
					
				}
			
			});
	}
	
	return false; 
	});
	
	
	$(".editStockOffer").click(function(){
		
		var DRowIdForEdit = $(this).val();
		//alert(DRowIdForEdit);
		
		$(".EditStockinOfferTR").show();
		$(".EditStockinOfferBtn").show();
		$(".AddItemOfferTR").hide();
		$(".addStockBtn").hide();
		
		$.ajax({
				
			url:"dist/php/getOfferStockForEdit.php",
			type:"POST",
			data:{DRIDFEdit:DRowIdForEdit},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$(".editStockOffer").prop("disabled", true);
				
			},
			success: function(showStockDataEdit){
				
				$(".editStockOffer").prop("disabled", false);
				 
			$("#partNo").val(showStockDataEdit.PartNo);
			$("#ItemName").val(showStockDataEdit.DescripName);
			$("#descQty").val(showStockDataEdit.DescripQTY);
			$("#descPrice").val(showStockDataEdit.DescripPrice);
			$("#totalPrice").val(showStockDataEdit.DescripTotalP);
			
			$(".addedStock").hide();
			$(".addNewStockItem").hide();
			$(".EditOldStockItem").show();
			$(".rowIdStockForEdit").val('');
			$(".rowIdStockForEdit").val(DRowIdForEdit);
			$(".backBTN").hide();
			$(".backBTN2").show();
			
			}
		
		});
		return false;
		});
	
	
});
 
 </script>