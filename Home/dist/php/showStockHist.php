<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
$itemCode = $_POST['ItemCode'];
$sqlGetItemData = "SELECT `descriptionname`, `partnumber` FROM `stockitems` 
WHERE `description` = $itemCode";
$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
$resGetItemData= mysqli_fetch_assoc($queryGetItemData);
?>
    <p style="font-size:14px; font-weight:bold"> <?php echo $resGetItemData['partnumber'];?>&nbsp; <?php echo $resGetItemData['descriptionname']?>&nbsp; Transaction Card</p>
<table class="myTableStockHist table table-sm table-striped table-bordered" cellspacing="0" width="99%">

    <thead class="bg-warning">
          <th>No.</th>
          <th>Income</th>
          <th>Export</th>
          <th>Date</th>
          <th>Number</th>
          <th>Staff</th>
          <th>Note</th>
    </thead>
    <tbody>    
<?php

	$ser = 1;	
	$sqlGetExptStock="SELECT `description`, `income`,`export`, DATE(`date`) AS expDate, 
	TIME(`date`) AS expTime, `invoicenumber`, `supplier`, `responsible`, `custcode`, `poIdRef`, `whref` 
	FROM `warehouse` WHERE `description` = $itemCode ORDER BY `warehouseId`";
	$queryGetExptStock=mysqli_query($link,$sqlGetExptStock)or die("ERROR :01-AU_AU_S");
	while($resGetExptStock= mysqli_fetch_assoc($queryGetExptStock))
		{
						
			if($resGetExptStock['export'] != 0)
			{
				$sqlGetCust = "SELECT `customername` FROM `customers` WHERE `customercode` =
				$resGetExptStock[custcode]";
				$queryGetCust = mysqli_query($link,$sqlGetCust)or die("ERROR :02-ANJ_GCN_S");
				$resultGetCust = mysqli_fetch_array($queryGetCust);	
				
				$sqlGetJobRef = "SELECT `jobidref`, `PoNum` FROM `customerpo` WHERE `poId` =
				 $resGetExptStock[poIdRef]";
				$queryGetJobRef=mysqli_query($link,$sqlGetJobRef)or die("ERROR :03-AU_AU_S");
				$resGetJobRef= mysqli_fetch_assoc($queryGetJobRef);
			
				$custSupp = $resultGetCust['customername'];
				$PoInvo = $resGetJobRef['PoNum'];
			}
			else if($resGetExptStock['income'] != 0)
			{
				$sqlGetSupp = "SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` =
				$resGetExptStock[supplier]";
				$queryGetSupp = mysqli_query($link,$sqlGetSupp)or die("ERROR :02-ANJ_GCN_S");
				$resultGetSupp = mysqli_fetch_array($queryGetSupp);	
				
				$custSupp = $resultGetSupp['suppliername'];
				$PoInvo = $resGetExptStock['invoicenumber'];
			}
			
			if($resGetExptStock['whref'] == 1)
			{
				$refTitel = "Normal Export / Import Action";
				$ref = 'NEA';
			}
			else if($resGetExptStock['whref'] == 2)
			{
				$refTitel = "Return From Wrong Action";
				$ref = 'RFWA';
				
			}
			
			if($resGetExptStock['whref'] == 2 && $resGetExptStock['income'] != 0)
			{
				$PoInvo = 'Returned';
			}
				echo "
				<tr>
				<td class='col-sm-1' class='ItemTypeTh'> $ser</td>
				<td class='col-sm-3'> $resGetExptStock[income]</td>
				<td class='col-sm-3'> $resGetExptStock[export]</td>
				<td class='col-sm-3'><span data-toggle='tooltip' data-placement='left' 
				title='Time:$resGetExptStock[expTime]'>$resGetExptStock[expDate]</span></td>
				<td class='col-sm-3'>$PoInvo </td>
				<td class='col-sm-3'> $resGetExptStock[responsible]</td>
				<td class='col-sm-3'> 
				<span data-toggle='tooltip' data-placement='left' title='$refTitel'>
				$ref
				</span>
				</td>
				</tr>
				";
	$ser++;
	
		}
 ?>
	</tbody>
     <tfoot >
          <th>Total</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
    </tfoot>
 </table> 
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
   
   var table = $('.myTableStockHist').DataTable( {
	 
	  fixedHeader: false,
             scrollY:'35vh',
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "asc"]], 
		 
 dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'Item_Transaction_Card '+datetime,
			filename: function () {
			return "Item_Transaction_Card" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'Item_Transaction_Card '+datetime,
			 filename: function () {
			return "Item_Transaction_Card" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | Item Transaction Card '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5,6]
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
            .column( 1 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 1, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 1 ).footer() ).html(pageTotal).css("color","blue");
		
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
        $(api.column( 2 ).footer() ).html(pageTotal).css("color","red");
			
				
			
  		}

 
   });
		
		
    });
 
 
 </script>  
