
<style>
h1 {font-size:14px; font-weight:bold;
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
   
   var table = $('.myTableCustDeliver').DataTable( {
	 
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
			title:'All_Customre_Order_Deliver '+datetime,
			filename: function () {
			return "All_Customre_Order_Deliver" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Customre_Order_Deliver '+datetime,
			 filename: function () {
			return "All_Customre_Order_Deliver" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | All Customre Order Deliver '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5,6,7]
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
 
   });
	 
	$(".deliverPO").click(function(){
		
		var jobType = $(this).val().split(',')[1];
		var jobRowId = $(this).val().split(',')[0];
		var SuppORowID = $(this).val().split(',')[2];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/deliverItemInPoModel.php',
                type:'POST',
                data:{ModelDeliverSuppORID:SuppORowID, MoadelDeliverOrderType:jobType,JRIDDeliver:jobRowId},
                
				success: function(showSuppForm)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showSuppForm);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		
		return false;
		});
    });

</script>
<div class="table-responsive">
 <table class="myTableCustDeliver table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>PO</th>
          <th>Type</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Sales</th>
          <th><span data-toggle='tooltip' data-placement='left' title='Supplied Quantity'>QTY</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Remaining for Delivery'>RFD</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Delivery QTY'>DQTY</span></th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;

	$sqlGetExcededPo = "SELECT `poId`, `jobidref`, `PoNum` FROM `customerpo` WHERE `deleveryDate` <= NOW() 
	AND `poRef` IS NULL";
	$queryGetExcededPo=mysqli_query($link,$sqlGetExcededPo)or die("ERROR :01-AU_AU_S");
	while($resGetExcededPo= mysqli_fetch_assoc($queryGetExcededPo))
	{
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `customer`, `responsible`, `jobtype`,
	`description`, `jobtype`, `salesman`,  `poref` FROM `job` WHERE `jobId` = $resGetExcededPo[jobidref] ";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetAllNewJob[salesman]";
	$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
	$resGetSales= mysqli_fetch_assoc($queryGetSales);
		
	$sqlGetPolicy="SELECT `deliverydate`, DATEDIFF(`deliverydate`, NOW()) AS Deff FROM `offerpolicy` 
	WHERE `jobRowId` = $resGetAllNewJob[jobId]";
	$queryGetPolicy=mysqli_query($link,$sqlGetPolicy)or die("ERROR :01-AU_AU_S");
	$resGetPolicy= mysqli_fetch_assoc($queryGetPolicy);
	
	//$sqlAllOfferQTY="SELECT `poId`, `PoNum` FROM `customerpo` 
	//WHERE `jobidref` = $resGetAllNewJob[jobId]";
	//$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :01-AU_AU_S");
	//$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
	$sqlGetSuppRID="SELECT `SOId` FROM `supplierorder` WHERE `custPOId` = $resGetExcededPo[poId]";
	$queryGetSuppRID=mysqli_query($link,$sqlGetSuppRID)or die("ERROR :01-AU_AU_S");
	if(mysqli_num_rows($queryGetSuppRID) > 0)
	{
	$resGetSuppRID= mysqli_fetch_assoc($queryGetSuppRID);
	
	$sqlGetSuppQTY="SELECT SUM(`receivedQTY`) AS receivedQTY FROM `supporderitems` 
	WHERE `SOIdRef` = $resGetSuppRID[SOId]";
	$queryGetSuppQTY=mysqli_query($link,$sqlGetSuppQTY)or die("ERROR :01-AU_AU_S");
	$resGetSuppQTY= mysqli_fetch_assoc($queryGetSuppQTY);
	if($resGetSuppQTY['receivedQTY'] == "")
		{
			$suppReceived = 0;
		}
	else if($resGetSuppQTY['receivedQTY'] != "") 
		{
			$suppReceived = $resGetSuppQTY['receivedQTY'];
		}
	
	$sqlGetDeliverQTY="SELECT SUM(`itemquantity`) AS itemquantity FROM `custorderdeliver`
	 WHERE `poRowId` = $resGetExcededPo[poId]";
	$queryGetDeliverQTY=mysqli_query($link,$sqlGetDeliverQTY)or die("ERROR :01-AU_AU_S");
	$resGetDeliverQTY= mysqli_fetch_assoc($queryGetDeliverQTY);
	if($resGetDeliverQTY['itemquantity'] == "")
	{
		$deliverQTY = 0;
	}
	else
	{
		$deliverQTY = $resGetDeliverQTY['itemquantity'];
	}
	}
	else
	{
		$suppReceived = 0;
		$deliverQTY = 0;
		
	}
	
	if($resGetPolicy['Deff'] > 0)
	{
		$color = "blue";
	}
	else
	{
		$color = "red";
	}
	
		echo "
			<tr>
				<td class='col-sm-0'>$ser</td>
				<td><span data-toggle='tooltip' data-placement='left' 
				title='Offer Number: $resGetAllNewJob[localref]'>$resGetExcededPo[PoNum]</span></td>
				<td>$resGetAllNewJob[jobtype]</td>
				<td>$resGetCustomer[customername]</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetSales[username]</td>
				<td>$suppReceived</td>
				<td style='color:$color'><span data-toggle='tooltip' data-placement='left' 
				title='Deliver Date: $resGetPolicy[deliverydate]'>$resGetPolicy[Deff]<span</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title=''>$deliverQTY</span></td>
				
            </tr>
		
		
			";
	$ser++;		
	}

 ?>
    </tbody>  
 </table>
 </div>


