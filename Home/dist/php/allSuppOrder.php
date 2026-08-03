
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
	 
	$(".editSuppOrder").click(function(){
		
		var SuppORowID =  $(this).val().split(',')[0];
		var jobType = $(this).val().split(',')[1];
		var jobRowId = $(this).val().split(',')[2];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		 if(jobType == 'Doors')
		 {
			$.ajax({
                url:'dist/php/addSupplyItemNew.php',
                type:'POST',
                data:{ModelSuppORID:SuppORowID, MoadelOrderType:jobType,JRID:jobRowId},
                
				success: function(showSuppForm)
				{
				//alert(showHWData);
                $('.SuppOrderEdit').html('');
				$('.SuppOrderEdit').html(showSuppForm);
				
				}         
        	}); 
		 }
		 else
		 {
			 $.ajax({
                url:'dist/php/addSupplyItemModel.php',
                type:'POST',
                data:{ModelSuppORID:SuppORowID, MoadelOrderType:jobType,JRID:jobRowId},
                
				success: function(showSuppForm2)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showSuppForm2);
				$(".myModal").modal('toggle');
				
				}         
        	});
		 }
		return false;
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
	 
	$(".EditSuppQTY").click(function(){
		
		var OrderRowIdEdit = $(this).val().split(',')[0];
		var OrderTypeEdit = $(this).val().split(',')[1];
		var jobRowIdEdit = $(this).val().split(',')[2];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/editSuppItemQTYForm.php',
                type:'POST',
                data:{SORowId:OrderRowIdEdit, SupplyOType:OrderTypeEdit,SuppEditJobRID:jobRowIdEdit},
                
				success: function(showItemForEditQTY)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showItemForEditQTY);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		
		return false;
		});	 
		
		$(".EditReceiveQTY").click(function(){
		
		var OrderRowIdRecev = $(this).val().split(',')[0];
		var OrderTypeRecev = $(this).val().split(',')[1];
		var jobRowIdRecev = $(this).val().split(',')[2];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/recevieSuppItemQTYForm.php',
                type:'POST',
                data:{SORowIdR:OrderRowIdRecev, SupplyOTypeR:OrderTypeRecev,SuppRJobRID:jobRowIdRecev},
                
				success: function(showItemForRecevQTY)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showItemForRecevQTY);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		
		return false;
		});
		
	$(".ExptSuppOrder").click(function(){
		 
		var OrderRowIdExpt = $(this).val().split(',')[0];
		var OrderTypeExpt = $(this).val().split(',')[1];
		var jobRowIdExpt = $(this).val().split(',')[2];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/exportSuppItemForm.php',
                type:'POST',
                data:{ExptSORowId:OrderRowIdExpt, ExpotOTypeR:OrderTypeExpt,ExptRJobRID:jobRowIdExpt},
                
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
		
	$(".EditSuppNotes").click(function(){
		
		var OrderRowIdNote = $(this).val();
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		//$(".BackFromEdit").show();
		
		
			$.ajax({
                url:'dist/php/EditSuppOrderNotesModal.php',
                type:'POST',
                data:{noteSORowId:OrderRowIdNote},
                
				success: function(showNotesForm)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showNotesForm);
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
          <th>Manufacture</th>
          <th>Project</th>
          <th>Note</th>
          <th>Order QTY</th>
          <th>Sent QTY</th>
          <th>Receive</th>
          <th>Remaining</th>
          <th><span data-toggle='tooltip' data-placement='left' title='PO Delivery Date'>PODD</span></th>
          <th><span data-toggle='tooltip' data-placement='left' title='Remaining for Supply Delivery'>RFD
          </span></th>
          <th width="2%"></th>
          <th width="2%"></th>
    </thead>
    <tbody  style="font-size:12px">
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetAllNewSuppOrder="SELECT `SOId`, `SuppCode`, `OrderNumber`, `date`, `deliveryDate`, `totalAmout`,
	 `authUser`, `orderNotes`, `custPOId`, DATEDIFF(`deliveryDate`, NOW()) AS Deff FROM `supplierorder` 
	 WHERE ( `SORef` = 0) OR (`SORef` = 1) OR (`SORef` = 2) ";
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

	
	$sqlGetProject="SELECT `projectName`, `jobref` FROM `job` WHERE `jobId` = $resGetCustPO[jobidref]";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :05_1-AU_AU_S");
	$resGetProject= mysqli_fetch_assoc($queryGetProject);
	
	$project = $resGetProject['projectName'];
	$jobRef= $resGetProject['jobref'];
		
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
		$SuppColor = "red";
	}
	
	if($suppQTY != 0)
	{
		$suppQTYTD = $suppQTY;
			/*"<span data-toggle='tooltip' data-placement='left' 
			 	title='Edit Supply Item'><button class='btn btn-link EditSuppQTY' 
				value='$resGetAllSuppOrder[SOId],$resGetCustPO[orderType],$resGetCustPO[jobidref]'>
				$suppQTY</button></span>";*/
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
		$receiceQTYTD = "<span data-toggle='tooltip' data-placement='left' 
				title='Add Receive Item QTY'><button class='btn btn-link EditReceiveQTY' 
				value='$resGetAllSuppOrder[SOId],$resGetCustPO[orderType],$resGetCustPO[jobidref]'>
				$suppReceived</button></span>";
				$suppQTYTD = $suppQTY;
	}
		if($jobRef == 3)
		{
		echo "
			<tr>
				<td class='col-sm-0' style='font-size=11px;'>$ser</td>
				<td style='font-size=10px;'><span data-toggle='tooltip' data-placement='left' 
				title='Show Order Info'><button class='btn btn-link showOrderInfo' 
				value='$resGetCustPO[jobidref],$resGetCustPO[orderType]'>
				 ".$resGetAllSuppOrder['OrderNumber']."</button></span></td>
				<td style='font-size=11px;'>$resGetCustPO[orderType]</td>
				<td style='font-size=10px;'>$resGetSupplier[suppliername]</td>
				<td style='font-size=11px;'><span data-toggle='tooltip' data-placement='left' 
				title='Order Number: $resGetCustPO[PoNum] | Customer: $resGetCustomer[customername]'>
				$project</span></td>
				<td class='col-sm-2' style='font-size=11px;' align='center'>
				<span data-toggle='tooltip' data-placement='left' 
				title='Edit Supply Notes'><button class='btn btn-link EditSuppNotes' 
				value='$resGetAllSuppOrder[SOId]'>".
				$resGetAllSuppOrder['orderNotes']."</button></span></td>
				<td style='font-size=11px;'>$OrderQTY</td>
				<td style='font-size=11px;'>$suppQTYTD</td>
				<td style='font-size=11px;'>$receiceQTYTD</td>
				<td style='font-size=11px;'>$suppReminig</td>
				<td style='color:$color ; font-size=11px;'>$resGetPolicy[Deff]</td>
				<td style='color:$SuppColor; font-size=11px;'>$resGetAllSuppOrder[Deff]</td>
              	<td class='col-sm-0' ><span data-toggle='tooltip' data-placement='left' 
				title='Add Supply Items' >
					<button class='btn btn-xm btn-link editSuppOrder' 
					value='$resGetAllSuppOrder[SOId],$resGetCustPO[orderType],$resGetCustPO[jobidref]'>
						<i class='fa fa-cogs' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title='Export Order Doc'>
					<button class='btn btn-xm btn-link ExptSuppOrder' 
					value='$resGetAllSuppOrder[SOId],$resGetCustPO[orderType],$resGetCustPO[jobidref]'>
						<i class='fa fa-industry' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
					</button>
				</span></td>
            </tr>
		
		
			";
	$ser++;	
		}
	}
 ?>
    </tbody>  
 </table>
 </div>


