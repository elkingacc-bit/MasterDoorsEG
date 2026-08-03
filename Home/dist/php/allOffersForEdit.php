
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
   
   var table = $('.myTable').DataTable( {
	 
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
			title:'All_New_Offers '+datetime,
			filename: function () {
			return "All_New_Offers" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_New_Offers '+datetime,
			 filename: function () {
			return "All_New_Offers" },
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
	  title:'Maintenance Tracker System {Master Doors EG} | All New Offers '+datetime,
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
 
  "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 8 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 8, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 8 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

   });
	 
	$(".editOffers").click(function(){
		
		var jobRowID =  $(this).val().split(',')[0];
		var jobType = $(this).val().split(',')[1];
		
			
			if(jobType == 'Doors')
			{
				$(".allNewOffers").html("");
				$(".allNewOffers").load("dist/php/editDoorOfferData.php",{jobRId:jobRowID} );
			}
			else if(jobType == 'Automatic')
			{
				$(".allNewOffers").html("");
				$(".allNewOffers").load("dist/php/editAutomaticOfferData.php",{jobRId:jobRowID} );
			}
			else if(jobType == 'Stock')
			{
				$(".allNewOffers").html("");
				$(".allNewOffers").load("dist/php/editStockOfferData.php",{jobRId:jobRowID} );
			}
			else if(jobType == 'Maintenance')
			{
				$(".allNewOffers").html("");
				$(".allNewOffers").load("dist/php/editFreeOfferData.php",{jobRId:jobRowID} );
			}
		return false;
		});
	 
	 $(".confirmOffers").click(function(){
		 
		 var closetTr = $(this).closest('tr');
		 	closetTr.addClass('text-primary');
			
		 var jobRowIdConf = $(this).val();
		 var validConfirm = confirm("Please Confirm Send Offer to Export Section?");
		 
		 if(validConfirm === true)
		 {
			 $.ajax({
				 
				 	url:"dist/php/confirmJobOffer.php",
					type:"POST",
					data:{jRIDConf:jobRowIdConf},
					beforeSend: function(){
					$(".confirmOffers").prop('disabled', true);	
					},
					success: function(doneConfirmJob){
						
						
						if(doneConfirmJob == 0)
						{
							alert('Not Completed Offer some Item missing Price');
							$(".confirmOffers").prop('disabled', false);	
							setTimeout(function(){
								closetTr.removeClass('text-primary');				
							}, 1500);							 
						}
						else if(doneConfirmJob == 3)
						{
							alert('Not Completed Offer some Hardware missing Price');
							$(".confirmOffers").prop('disabled', false);	
							setTimeout(function(){
								closetTr.removeClass('text-primary');				
							}, 1500);							 
						}
						else if(doneConfirmJob == 1)
						{
							alert('Successfully Transfer Job To Exporting');
							$('.allNewOffers').html('');
							$('.allNewOffers').load("dist/php/allOffersForEdit.php");
							
						}
						else
						{
							alert(doneConfirmJob);
							$(".confirmOffers").prop('disabled', false);
						}
					}
				 });
		 }
		 
		 return false;
		 });
		 
	 $(".editSF").click(function(){
			  
			$(".tooltip-inner").hide();
			$(".arrow").hide(); 
			
			$(".modal-dialog").removeClass("modal-xl");
			$(".modal-dialog").addClass("modal-lg");
			 var editSFJobRID = $(this).val().split(',')[0];
			 var editSFJobType = $(this).val().split(',')[1];
			if(editSFJobType == 'Doors')
			{
				 $.ajax({
					url:'dist/php/editSFDoorFormModel.php',
					type:'POST',
					data:{ModelEditSFJobRID:editSFJobRID,ModelEditSFJobType:editSFJobType},
					
					success: function(showFormforEditSF)
					{
					//alert(showHWData);
					$('.ShowHWData').html('');
					$('.ShowHWData').html(showFormforEditSF);
					$(".myModal").modal('toggle');
					
					}          
				}); 
			}
			else if(editSFJobType == 'Automatic')
			{
				$(".tooltip-inner").hide();
				$(".arrow").hide();
				 $.ajax({
					url:'dist/php/editSFAutoFormModel.php',
					type:'POST',
					data:{ModelEditSFJobRID:editSFJobRID,ModelEditSFJobType:editSFJobType},
					
					success: function(showFormforEditSF)
					{
					//alert(showHWData);
					$('.ShowHWData').html('');
					$('.ShowHWData').html(showFormforEditSF);
					$(".myModal").modal('toggle');
				
					}         
				}); 
			}
			else
			{
				$(".tooltip-inner").hide();
				$(".arrow").hide();
				alert("Stock and Maintenance Offer Not Allowed");
			}
			 
			 return false;
			 });
 
		 $(".EditBasicInof").click(function(){
			 
			 var editBasicJobRID = $(this).val();
			$(".modal-dialog").removeClass("modal-lg");
			$(".modal-dialog").addClass("modal-xl");
			 
			 $.ajax({
                url:'dist/php/editBaiscInfoFormModel.php',
                type:'POST',
                data:{ModelEditBIJobRID:editBasicJobRID},
                
				success: function(showFormforEditBI)
				{
				//alert(showHWData);
                $('.ShowHWData').html('');
                $('.ShowHWData').html(showFormforEditBI);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
			 
			 return false;
			 });
    
	});
	

</script>
<div class="table-responsive-sm">
 <table class="myTable table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Project</th>
          <th>Start</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Sales</th>
          <th>Status</th>
          <th>Type</th>
          <th>Value</th>
          <th width="4%"></th>
          <th width="4%"></th>
          <th width="4%"></th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `projectName`,`customer`, `offerValue`, `responsible`,
	 `offerStatus`,	`jobtype`, `description`, `jobtype`, `salesman`, `lastupdate`, `poref` FROM `job`
	  WHERE (`jobref` = 1) OR  (`jobref` IS NULL)";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	while($resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob))
	{
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetAllNewJob[salesman]";
	$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
	$resGetSales= mysqli_fetch_assoc($queryGetSales);
	
		echo "
			<tr>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Edit Basic Inof'>
				<button class='btn btn-xm btn-link EditBasicInof' 
				value='$resGetAllNewJob[jobId]'>
				$ser</button>
				</span></td>
				<td><span data-toggle='tooltip' data-placement='left' title='$resGetAllNewJob[localref]'>
				$resGetAllNewJob[projectName]</span></td>
				<td>".date("d/m/Y", strtotime($resGetAllNewJob['startDate']))."</td>
				<td>$resGetCustomer[customername]</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetSales[username]</td>
				<td>$resGetAllNewJob[offerStatus]</td>
				<td>$resGetAllNewJob[jobtype]</td>
				<td style='color:blue'>".number_format($resGetAllNewJob['offerValue'])."</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Edit'>
					<button class='btn btn-xm btn-link editOffers' 
					value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Sales Factor'>
				<button class='btn btn-xm btn-link editSF' 
				value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]' >
						<i class='fas fa-money-bill-wave' aria-hidden='true' 
						style='font-size:20px;color:#f0ad4e'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Confirm'>
					<button class='btn btn-xm btn-link confirmOffers' 
					value='$resGetAllNewJob[jobId]'>
						<i class='fas fa-check' aria-hidden='true' style='font-size:20px;color:#14A44D'></i>
					</button>
				</span></td>
            </tr>
		
		
			";
	$ser++;		
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
          <th></th>
          <th></th>
    </tfoot>
 </table>
 </div>


