<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 10;
	$colspan1 = 2;
	$colspan2 = 2;
	$colspan3 = 3;
	
}
else
{
	$diplay = "none";
	$colspan = 7;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
} 

$jobRowId = $_POST['tableJobId'];
$ItemNameTable = $_POST['tableItemName'];
$ItemTypeTable = $_POST['tableItemType'];
$ItemTableRowId = $_POST['tableItemRowId'];

echo "

	<table class='table table-sm myTableItems'  style='width:100%'>
   
      <thead class='bg-dark'>
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th style='display:$diplay'>Price</th>
        <th style='display:$diplay'>Total</th>
        <th></th>
      </thead>
      <tbody>


";


$sqlGetItemRef = "SELECT `itemRef`, `itemqty` FROM `itemoffer` WHERE `jobref` = $jobRowId AND `id` = $ItemTableRowId
LIMIT 1";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");
$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);

$LetterRef = $resGetItemRef['itemRef'];
$ItemQTY = $resGetItemRef['itemqty'];
 
$sqlGetHW = "SELECT `offproId`,`descripcode`, `descripquantity`, `unitPrice`, `totalprice` 
FROM `offerproperties` WHERE `ioidref` = $ItemTableRowId";
$queryGetHW = mysqli_query($link,$sqlGetHW)or die("ERROR :02-ANJ_GCN_S");
while($resGetHW = mysqli_fetch_assoc($queryGetHW))
{ 
	$sqlGetItemDate = "SELECT `descriptionname`, `partnumber` FROM `stockitems`
	 WHERE `description` =$resGetHW[descripcode]";
	$queryGetItemDate = mysqli_query($link,$sqlGetItemDate)or die("ERROR :03-ANJ_GCN_S");
	$resGetItemDate = mysqli_fetch_assoc($queryGetItemDate);
	
	$descName = $resGetItemDate['descriptionname'];	
	$PartNum = $resGetItemDate['partnumber'];
	$hwQTY = ($resGetHW['descripquantity'] / $ItemQTY);
	
	echo "
		<tr>
			<td>$PartNum</td>
			<td>$descName</td>
			<td>$hwQTY</td>
			<td style='display:$diplay'>".number_format($resGetHW['unitPrice'])."</td>
			<td style='display:$diplay'>".number_format($resGetHW['unitPrice'] * $hwQTY)."</td>
			<td>
			<button class='btn btn-link btn-xs removeHw' value='$resGetHW[offproId]'
					data-toggle='tooltip' data-placement='top' title='Remove'>
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
      </tfoot>
 </table>

";

?>		
      
 
 <input type="text" value="<?php echo $jobRowId?>" style="display:none" id="rowIdJobLoad"/>
  <input type="text" value="<?php echo $ItemNameTable?>" style="display:none" id="ItemNameLoad"/>
   <input type="text" value="<?php echo $ItemTableRowId?>" style="display:none" id="rowIdItemLoad"/>
 <script type="text/javascript">
 $(document).ready(function() {
    
	  var table2 = $('.myTableItems').DataTable( {
	 
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
	var rmJItemRowId = $("#rowIdItemLoad").val();
	
	if(confRemove === true)
	{
		$.ajax({
				
				url:"dist/php/removeHWfromItem.php",
				type:"POST",
				data:{TRIDHW:remRowID,RJROIF:rmJRowId,itemNameHWRem:rmJItemName},
				beforeSend: function(){
				$(".removeHw").prop('disabled', true);	
				},
				success: function(doneRMHW){
					
					if(doneRMHW == 1)
					{
						alert("Data Saved");
						$(".HWadded").html("");
						$(".HWadded").show("");
						$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:rmJRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
						$(".HWadded").load("dist/php/showAllAddHWtoItem.php",{tableJobId:rmJRowId,tableItemName:rmJItemName,tableItemRowId:rmJItemRowId});
						$(".removeHw").prop('disabled', false);	
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
	
});
 
 </script>