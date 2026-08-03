
<style>
h1 {font-size:14px; font-weight:bold;
}
.btn-link {
  padding-left: 0
}
</style>

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
   
   var table = $('.myTableSuppOrder').DataTable( {
	 
	  fixedHeader: true,
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
			title:'All_New_Supp_Order '+datetime,
			filename: function () {
			return "All_New_Supp_Order" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7,8,9,10,11]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_New_Supp_Order '+datetime,
			 filename: function () {
			return "All_New_Supp_Order" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7,8,9,10,11]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | All New Supplier Order '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5,6,7,8,9,10,11]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:7cm; left:30%; opacity: 0.2; filter: alpha(opacity=15); width: 450px; height:200px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
	}
 ],			 
 
   });
			
		
		$(".showOrderInfo").click(function(){
		
		var jobRowIdInfo = $(this).val().split(',')[0];
		var ItemTypeInfo = $(this).val().split(',')[1];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/showAlljobInfo.php',
                type:'POST',
                data:{JRIDInfo:jobRowIdInfo,ITInfo:ItemTypeInfo},
                
				success: function(showJobInfo)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showJobInfo);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		
		return false;
		}); 
    });

</script>
<div class="table-responsive">
 <table class="myTableSuppOrder table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning" style="font-size:12px">
          <th>No.</th>
          <th>Order No</th>
          <th>Type</th>
          <th>Supplier</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Order QTY</th>
          <th>Sent QTY</th>
          <th>Receive</th>
          <th>Remaining</th>
          <th><span data-toggle='tooltip' data-placement='left' title='PO Delivery Date'>PODD</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Remaining for Supply Delivery'>RFD
          </span></th>
    </thead>
    <tbody  style="font-size:12px">
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetAllNewSuppOrder="SELECT `SOId`, `SuppCode`, `OrderNumber`, `date`, `deliveryDate`, `totalAmout`,
	 `authUser`, `orderNotes`, `custPOId`, DATEDIFF(`deliveryDate`, NOW()) AS Deff FROM `supplierorder` 
	 WHERE (`SORef` = 0 AND `deliveryDate` <= NOW()) OR (`SORef` = 1 AND `deliveryDate` <= NOW()) ";
	$queryGetAllSuppOrder=mysqli_query($link,$sqlGetAllNewSuppOrder)or die("ERROR :01-AU_AU_S");
	while($resGetAllSuppOrder= mysqli_fetch_assoc($queryGetAllSuppOrder))
	{
	
	$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
	$resGetAllSuppOrder[SuppCode]";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :03-AU_AU_S");
	$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);
	
	$sqlGetCustPO="SELECT `custCode`, `PoNum`, `orderType`, `jobidref` FROM `customerpo` 
	WHERE `poId` = $resGetAllSuppOrder[custPOId]";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :04-AU_AU_S");
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	if($resGetCustPO['orderType'] == 'Doors')
	{
	$sqlGetItemAllQTY="SELECT SUM(`itemqty`) AS AllQTY FROM `itemoffer` 
	WHERE `jobref` = $resGetCustPO[jobidref]";
	}
	else if($resGetCustPO['orderType'] == 'Automatic')
	{
	$sqlGetItemAllQTY="SELECT SUM(`doorqty`) AS AllQTY FROM `autodoorsoffer` 
	WHERE `jobid` = $resGetCustPO[jobidref]";
	}
	$queryGetItemAllQTY=mysqli_query($link,$sqlGetItemAllQTY)or die("ERROR :08-AU_AU_S");
	$resGetItemAllQTY= mysqli_fetch_assoc($queryGetItemAllQTY);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetCustPO[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :05-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetPolicy="SELECT `deliverydate`, DATEDIFF(`deliverydate`, NOW()) AS Deff FROM `offerpolicy` 
	WHERE `jobRowId` = $resGetCustPO[jobidref]";
	$queryGetPolicy=mysqli_query($link,$sqlGetPolicy)or die("ERROR :06-AU_AU_S");
	$resGetPolicy= mysqli_fetch_assoc($queryGetPolicy);
	
	$sqlGetOrderStat="SELECT SUM(`qty`) AS QTY, SUM(`receivedQTY`) AS QTYRece FROM `supporderitems` 
	WHERE `SOIdRef` = $resGetAllSuppOrder[SOId]";
	$queryGetOrderStat=mysqli_query($link,$sqlGetOrderStat)or die("ERROR :07-AU_AU_S");
	$resGetOrderStat= mysqli_fetch_assoc($queryGetOrderStat);
		if($resGetOrderStat['QTY'] == "" && $resGetOrderStat['QTYRece'] == "")
		{
			$OrderQTY = $resGetItemAllQTY['AllQTY'];
			$suppQTY = 0;
			$suppReminig = $resGetItemAllQTY['AllQTY'];
			$suppReceived = 0;
		}
		else if($resGetOrderStat['QTY'] != "" && $resGetOrderStat['QTYRece'] == "") 
		{
			$OrderQTY = $resGetItemAllQTY['AllQTY'];
			$suppQTY = $resGetOrderStat['QTY'];
			$suppReminig = $resGetItemAllQTY['AllQTY'];
			$suppReceived = 0;
		}
		else
		{
			
			$OrderQTY = $resGetItemAllQTY['AllQTY'];
			$suppQTY = $resGetOrderStat['QTY'];
			$suppReceived = $resGetOrderStat['QTYRece'];
			$suppReminig = ($OrderQTY - $suppReceived);
		}
	
	
	if($resGetPolicy['Deff'] > 0)
	{
		$color = "black";
	}
	else
	{
		$color = "red";
	}
	
	if($resGetAllSuppOrder['Deff'] > 0)
	{
		$SuppColor = "black";
	}
	else
	{
		$resGetAllSuppOrder = "red";
	}
	
	if($suppQTY != 0)
	{
		$suppQTYTD = $suppQTY;
	}
	else
	{
		$suppQTYTD = $suppQTY;
	}
	
	if($suppQTY == $suppReceived || $suppQTY == 0)
	{
		$receiceQTYTD = $suppReceived;
		$suppQTYTD = $suppQTY;
	}
	else
	{
		$receiceQTYTD = $suppReceived;
	}
	
		echo "
			<tr>
				<td class='col-sm-0'>$ser</td>
				<td><span data-toggle='tooltip' data-placement='left' 
				title='Show Order Info'><button class='btn btn-link showOrderInfo' 
				value='$resGetCustPO[jobidref],$resGetCustPO[orderType]'>
				 $resGetAllSuppOrder[OrderNumber]</button></span></td>
				<td>$resGetCustPO[orderType]</td>
				<td>$resGetSupplier[suppliername]</td>
				<td><span data-toggle='tooltip' data-placement='left' 
				title='Order Number: $resGetCustPO[PoNum]'>$resGetCustomer[customername]</span></td>
				<td class='col-sm-2'>$resGetAllSuppOrder[orderNotes]</td>
				<td>$OrderQTY</td>
				<td>$suppQTYTD</td>
				<td>$receiceQTYTD</td>
				<td >$suppReminig</td>
				<td style='color:$color'>$resGetPolicy[Deff]</td>
				<td style='color:$SuppColor'>$resGetAllSuppOrder[Deff]</td>
              	
            </tr>
		
		
			";
	$ser++;		
	}

 ?>
    </tbody>  
 </table>
 </div>

<div class="modal fade myModal" tabindex="-1"  role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 80%;">
    <div class="modal-content">
    
    <div class="ShowData" align="center">
    </div>
    </div>
  </div>
</div>

