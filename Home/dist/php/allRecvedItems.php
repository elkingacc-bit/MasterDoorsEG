<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$InvoNum = $_POST['invNumTbl'];

$sqlGetImportItems= "SELECT  `warehouseId`, `description`, `income` FROM `warehouse` 
WHERE `invoicenumber` = $InvoNum AND `whref` = 0";
$queryGetImportItems = mysqli_query($link,$sqlGetImportItems)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetImportItems) > 0)
{
	$ser= 1;
	echo "
	<table class='table table-sm table-striped myTableOldItems' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>No</th>
				<th>Part No</th>
                <th>Name</th>
                <th>QTY</th>
				
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetImportItems = mysqli_fetch_assoc($queryGetImportItems))
{
	
	$sqlGetItemData = "SELECT  `partnumber`, `descriptionname` FROM `stockitems` WHERE  `description` 
	= '$resGetImportItems[description]'";
	$queryGetItemData = mysqli_query($link,$sqlGetItemData)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemData = mysqli_fetch_assoc($queryGetItemData);
	
		echo "
		<tr>
			<td class='col-sm-1'>$ser</td>
			<td class='col-sm-2'>$resGetItemData[partnumber]</td>
			<td class='col-sm-3'>$resGetItemData[descriptionname]</td>
			<td class='col-sm-1'>$resGetImportItems[income]</td>
			
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
	
});
 
 </script>