<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$date = date("Y-m-d");

?>
<style type="text/css">

h1{
	font-size:14px;
	}
</style>
<div class="body">
 <table class="myTableInstallComm table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Project</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Sales</th>
          <th><span data-toggle='tooltip' data-placement='left' title='Offer QTY'>QTY</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Supplied Request Quantity'>SQTY
          </span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Customer Deliveried QTY'>DQTY</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Remaining for Customer Delivery'>RFD
          </span></th>
          <th>Commission</th> 
    </thead>
    <tbody >
            
<?php
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `projectName`,`customer`, `responsible`, `jobtype`,
	`offerStatus`, `description`, `jobtype`, `salesman`,  `poref` FROM `job` WHERE `jobref` = 3 ";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	while($resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob))
	{
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetAllNewJob[salesman]";
	$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
	$resGetSales= mysqli_fetch_assoc($queryGetSales);
	
	$sqlGetPolicy="SELECT `deliverydate`, DATEDIFF(`deliverydate`, NOW()) AS Deff FROM `offerpolicy` 
	WHERE `jobRowId` = $resGetAllNewJob[jobId]";
	$queryGetPolicy=mysqli_query($link,$sqlGetPolicy)or die("ERROR :01-AU_AU_S");
	if(mysqli_num_rows($queryGetPolicy) > 0)
	{
		$resGetPolicy= mysqli_fetch_assoc($queryGetPolicy);
		$deff = $resGetPolicy['Deff'];
		$deffDate = $resGetPolicy['deliverydate'];
	}
	else
	{
		$deff = '-';
		$deffDate = 'N/A';
	}
	
	if($resGetPolicy['Deff'] > 0)
	{
		$color = "blue";
	}
	else
	{
		$color = "red";
	}

	$sqlGetCustPO="SELECT `poId`, `PoNum`, `poRef`,`InstallationComsion`, `poVal` FROM `customerpo` 
	WHERE `jobidref` = $resGetAllNewJob[jobId]";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :01-AU_AU_S");
	
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
	
	if($resGetCustPO['InstallationComsion'] == 0)
	{
		$Commission = 0;
	}
	else
	{
		
		$Commission = number_format(($resGetCustPO['poVal']) * $resGetCustPO['InstallationComsion']);
	}
	
	if($resGetCustPO['poRef'] == NULL || $resGetCustPO['poRef'] == "")
	{
		$OrderStatus = '<span style="color:red">Pending</span>';
	}
	else if($resGetCustPO['poRef'] == 1 )
	{
		$OrderStatus = '<span style="color:green">Finished</span>';
	}

	if($resGetAllNewJob['jobtype'] == 'Doors')
	{
		$sqlGetOfferQTY="SELECT SUM(`itemqty`) AS OferQTY FROM `itemoffer` WHERE `jobref` = 
		$resGetAllNewJob[jobId]";
	$queryOfferQTY=mysqli_query($link,$sqlGetOfferQTY)or die("ERROR :01-AU_AU_S");
	$resOfferQTY= mysqli_fetch_assoc($queryOfferQTY);
	
	$offerQTY = $resOfferQTY['OferQTY'];
	
	$sqlGetSuppRID="SELECT `SOId`, `OrderNumber` FROM `supplierorder` WHERE `custPOId` = $resGetCustPO[poId]";
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
				$receiveQTY = 0;
			}
			else
			{
				$receiveQTY = $resGetSuppQTY['receivedQTY'];
			}	
		$sqlGetDeliverQTY="SELECT SUM(`itemquantity`) AS itemquantity FROM `custorderdeliver`
		 WHERE `poRowId` = $resGetCustPO[poId]";
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
			$receiveQTY = 0;
			$deliverQTY = 0;
		}
		
		if($offerQTY == $deliverQTY)
			{
				$deff = "--";
			}
	
	}//Doors
	else if($resGetAllNewJob['jobtype'] == 'Automatic')
	{
		$sqlGetOfferQTY="SELECT SUM(`doorqty`) AS OferQTY FROM `autodoorsoffer` WHERE `jobid` = 
		$resGetAllNewJob[jobId]";
		$queryOfferQTY=mysqli_query($link,$sqlGetOfferQTY)or die("ERROR :01-AU_AU_S");
		$resOfferQTY= mysqli_fetch_assoc($queryOfferQTY);
		
		$offerQTY = $resOfferQTY['OferQTY'];
		
		$sqlGetSuppRID="SELECT `SOId`, `OrderNumber` FROM `supplierorder` WHERE `custPOId` = $resGetCustPO[poId]";
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
				$receiveQTY = 0;
			}
			else
			{
				$receiveQTY = $resGetSuppQTY['receivedQTY'];
			}	
		$sqlGetDeliverQTY="SELECT SUM(`itemquantity`) AS itemquantity FROM `custorderdeliver`
		 WHERE `poRowId` = $resGetCustPO[poId]";
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
			$receiveQTY = 0;
			$deliverQTY = 0;
		}
		
		if($offerQTY == $deliverQTY)
			{
				$deff = "--";
			}
	
	}
	else if($resGetAllNewJob['jobtype'] == 'Stock')
	{
		$sqlGetOfferQTY="SELECT SUM(`descripqty`) AS OferQTY FROM `stockoffers` WHERE `jobref` = 
		$resGetAllNewJob[jobId]";
		$queryOfferQTY=mysqli_query($link,$sqlGetOfferQTY)or die("ERROR :01-AU_AU_S");
		$resOfferQTY= mysqli_fetch_assoc($queryOfferQTY);
		
		$offerQTY = $resOfferQTY['OferQTY'];
		
		$sqlGetDeliverQTY="SELECT SUM(`descripqty`) AS itemquantity FROM `stockoffers`
		 WHERE `jobref` = $resGetAllNewJob[jobId] AND `ref` = 2";
		$queryGetDeliverQTY=mysqli_query($link,$sqlGetDeliverQTY)or die("ERROR :01-AU_AU_S");
		$resGetDeliverQTY= mysqli_fetch_assoc($queryGetDeliverQTY);
		
		if($resGetDeliverQTY['itemquantity'] == "")
			{
				$deliverQTY = 0;
				$receiveQTY = 0;
			}
			else
			{
				$deliverQTY = $resGetDeliverQTY['itemquantity'];
				$receiveQTY =  0;
			}
			
			if($offerQTY == $deliverQTY)
			{
				$deff = "--";
			}	
		
	}
	else if($resGetAllNewJob['jobtype'] == 'Maintenance')
	{
		$sqlGetOfferQTY="SELECT SUM(`typeqty`) AS OferQTY FROM `maintoffers` WHERE `jobid` = 
		$resGetAllNewJob[jobId]";
		$queryOfferQTY=mysqli_query($link,$sqlGetOfferQTY)or die("ERROR :01-AU_AU_S");
		$resOfferQTY= mysqli_fetch_assoc($queryOfferQTY);
		
		$offerQTY = $resOfferQTY['OferQTY'];
		
		$receiveQTY = 0;
		$deliverQTY = 0;
		
		
	}
	
		if($Commission != 0)
		{
			echo "
			<tr>
				<td class='col-sm-0'>$ser</td>
				<td><span data-toggle='tooltip' data-placement='left' title='$resGetAllNewJob[jobtype]'>
				$resGetAllNewJob[projectName]</span></td>
				<td>$resGetCustomer[customername]</td>
				<td>$OrderStatus</td>
				<td>$resGetSales[username]</td>
				<td>$offerQTY</td>
				<td>$receiveQTY</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title=''>$deliverQTY</span></td>
				<td style='color:$color'><span data-toggle='tooltip' data-placement='left' 
				title='Deliver Date: $deffDate'>$deff<span</td>
				<td>$Commission</td>
            </tr>
			";
	
		$ser++;		
		}
	}

 ?>
    </tbody>  
    <tfoot>
    	  <th></th>
          <th></th>
          <th></th>
          <th></th>
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
				
				
 					
   
   var table = $('.myTableInstallComm').DataTable( {
	 
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
			title:'All_Attendance_outside_manpower_Staff '+datetime,
			filename: function () {
			return "All_Attendance_outside_manpower_Staff" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7,8,9]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Attendance_outside_manpower_Staff '+datetime,
			 filename: function () {
			return "All_Attendance_outside_manpower_Staff" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [ 0,1,2,3,4,5,6,7,8,9]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | All Attendance Outside Manpower Staff '+datetime,
	  footer: false,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5,6,7,8,9]
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
            .column( 9 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 9, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
			
        $(api.column( 9 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}	 
 
   });

		
});
 
 
 </script>  
