<?php
 @session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 
 $Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$action = "";
	
	
}
else
{
	$diplay = "none";
	$action = "disabled";
	
}
 
 ?>
<style>
h1 {font-size:14px; font-weight:bold;
}

.dataTables_wrapper .dt-buttons {
  float:right;  
  text-align:right;
  padding-left:3%;
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
		
	 
	$(".editBiscData").click(function(){
		
		var orderRowIdEdit = $(this).val();
		
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
	
		
			$.ajax({
                url:'dist/php/showEditOrderFormModel.php',
                type:'POST',
                data:{ORIDEdit:orderRowIdEdit},
                
				success: function(showOrderDateForm)
				{
				//alert(showHWData);
				$('.ShowData').html('');
                $('.ShowData').html(showOrderDateForm);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		
		return false;
		});	

 
$(".AddCommiosn").click(function(){
		
		var OrderRIDAdd = $(this).val();
		var refAdd = 1;
		
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
	
		
			$.ajax({
                url:'dist/php/saveUpdateInstalComssion.php',
                type:'POST',
                data:{PORIDIC:OrderRIDAdd, Ref:refAdd},
                
				success: function(doneAddComssion)
				{
				if(doneAddComssion == 1)
				{
					alert("Date Saved");
					$('.custOrderDiv').html('');
					$('.custOrderDiv').load("dist/php/custOrderRpt.php");
				}
				else
				{
					alert(doneAddComssion);
				}
				
				}         
        	}); 
		
		return false;
		});

	$(".removeCommiosn").click(function(){
		
		var OrderRIDDelete = $(this).val();
		var refRemove = 2;
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
	
		
			$.ajax({
                url:'dist/php/saveUpdateInstalComssion.php',
                type:'POST',
                data:{PORIDIC:OrderRIDDelete,Ref:refRemove},
                
				success: function(doneRemoveComssion)
				{
				if(doneRemoveComssion == 1)
				
				{
					alert("Date Saved");
					$('.custOrderDiv').html('');
					$('.custOrderDiv').load("dist/php/custOrderRpt.php");
				}
				else
				{
					alert(doneRemoveComssion);
				}
				
				}         
        	}); 
		
		return false;
		});
		
	 $(".deliverNotePO").click(function(){
		
		var custOrderRowIdExpt = $(this).val().split(',')[0];
		var OrderTypeExpt = $(this).val().split(',')[1];
		var jobRowIdExpt = $(this).val().split(',')[2];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/exportDeliveryNoteForm.php',
                type:'POST',
                data:{ExptPORowId:custOrderRowIdExpt, ExpotOTypeR:OrderTypeExpt,ExptRJobRID:jobRowIdExpt},
                
				success: function(showItemForExport)
				{
				//alert(showHWData);
				
                $('.ShowData').html('');
                $('.ShowData').html(showItemForExport);
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
          <th>Project</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Note</th>
          <th>Sales</th>
          <th><span data-toggle='tooltip' data-placement='left' title='Offer QTY'>QTY</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Supplied Request Quantity'>SQTY
          </span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Customer Deliveried QTY'>DQTY</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Remaining for Customer Delivery'>RFD
          </span></th>
          <th></th>
          <th style="display:<?php echo $diplay;?>"></th>
          
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

	$sqlGetCustPO="SELECT `poId`, `PoNum`, `poRef`,`InstallationComsion` FROM `customerpo` 
	WHERE `jobidref` = $resGetAllNewJob[jobId]";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :01-AU_AU_S");
	
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
	
	if($resGetCustPO['InstallationComsion'] == 0)
	{
		$button = "
					<span data-toggle='tooltip' data-placement='left' title='Add Installation Comssion' >
					<button class='btn btn-xm btn-link AddCommiosn' 
					value='$resGetCustPO[poId]'>
					<i class='fas fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
					</button>";
	}
	else
	{
		
		$button = "
					<span data-toggle='tooltip' data-placement='left' title='remove Installation Comssion' >
					<button class='btn btn-xm btn-link removeCommiosn' 
					value='$resGetCustPO[poId]'>
					<i class='fas fa-trash' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
					</button>";
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
	
	
		echo "
			<tr>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title='Edit Bisc Data'>
				<button class='btn btn-link editBiscData' value='$resGetCustPO[poId]' $action>$ser</button>
				</span></td>
				<td><span data-toggle='tooltip' data-placement='left' 
				title='Offer Number: $resGetAllNewJob[localref]'>
				<button class='btn btn-link showOrderInfo' 
				value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
				 $resGetCustPO[PoNum]</button></span>
				</td>
				<td><span data-toggle='tooltip' data-placement='left' title='$resGetAllNewJob[jobtype]'>
				$resGetAllNewJob[projectName]</span></td>
				
				<td>$resGetCustomer[customername]</td>
				<td>$OrderStatus</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetSales[username]</td>
				<td>$offerQTY</td>
				<td>$receiveQTY</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title=''>$deliverQTY</span></td>
								
				<td style='color:$color'><span data-toggle='tooltip' data-placement='left' 
				title='Deliver Date: $deffDate'>$deff<span</td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title='Delivery Note'>
					<button class='btn btn-xm btn-link deliverNotePO' 
					value='$resGetCustPO[poId],$resGetAllNewJob[jobtype],$resGetAllNewJob[jobId]'>
						<i class='fa fa-file-pdf' aria-hidden='true' 
						style='font-size:20px;color:#14A44D'></i>
					</button>
				</span></td>
				<td style='display:$diplay'>
				$button
				</td>
            </tr>
		
		
			";
	$ser++;		
	}

 ?>
    </tbody>  
 </table>
 </div>


