<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$CustPORID = $_POST['expCustPOIDWH'];

/*if($Permissiom =="Admin" || $Permissiom == "Manager")
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
*/

$sqlGetExptItems= "SELECT  `warehouseId`, `description`, `export` FROM `warehouse` WHERE `poIdRef` = $CustPORID 
";
$queryGetExptItems = mysqli_query($link,$sqlGetExptItems)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetExptItems) > 0)
{
	$ser= 1;
	echo "
	
<div align='right' class='finishBTNAll' style=' margin-right:5%'>
    	<button class='btn btn-dark btn-sm finishAndPrintAll' id='finishAndPrintAll' 
		value='$CustPORID'>Print</button>
       
    </div> <br>	
	<table class='table table-sm table-striped myTableOldItems' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>No</th>
				<th>Part No</th>
                <th>Name</th>
                <th>QTY</th>
				
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetExptItems = mysqli_fetch_assoc($queryGetExptItems))
{
	
	$sqlGetItemData = "SELECT  `partnumber`, `descriptionname` FROM `stockitems` WHERE  `description` 
	= '$resGetExptItems[description]'";
	$queryGetItemData = mysqli_query($link,$sqlGetItemData)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemData = mysqli_fetch_assoc($queryGetItemData);
	
		echo "
		<tr>
			<td class='col-sm-1'>$ser</td>
			<td class='col-sm-2'>$resGetItemData[partnumber]</td>
			<td class='col-sm-3'>$resGetItemData[descriptionname]</td>
			<td class='col-sm-1'>$resGetExptItems[export]</td>
			
		</tr>
	
	";
$ser++;
	}
	echo "
	</tbody>
	<tfoot class='bg-light'>
       	   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		  
           
    </tfoot>
</table>
	";
}
?>		
      
      
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 

var dept = $("#userPermission").val();
	  var table = $('.myTableOldItems').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "asc"]],
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
            .column( 2 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 2, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
			if(dept == "Admin" || dept == "Manager")
			{
				$(api.column( 2 ).footer() ).html(pageTotal);	
			}
  		}
});

$(".finishAndPrintAll").click(function(){
			
	
	$(".finishAndPrintAll").prop('disabled', true);	
		var PoSaveRowID3 = $(this).val();	
		var newAutoDocPrint2 = window.open("dist/php/printExportAllStock.php?&PoRID="+PoSaveRowID3,"_balnk");							
							newAutoDocPrint2.focus();
							setTimeout(function(){
								$(".finishAndPrintAll").prop('disabled', false);
								
							}, 500);	
						
		
		return false;
		});
	
});
 
 </script>