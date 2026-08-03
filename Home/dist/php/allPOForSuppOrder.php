
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
   
   var table = $('.myTableTS').DataTable( {
	 
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
			title:'All_New_PO '+datetime,
			filename: function () {
			return "All_New_PO" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_New_PO '+datetime,
			 filename: function () {
			return "All_New_PO" },
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
	  title:'Company Managment System {Master Doors EG} | All New PO '+datetime,
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
	 
	$(".editPO").click(function(){
		
		var jobRowID =  $(this).val().split(',')[0];
		var jobType = $(this).val().split(',')[1];
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();	
		$(".BackFromEdit").show();
		
			if(jobType == 'Doors')
			{
				$(".allCustPO").html("");
				$(".allCustPO").load("dist/php/AddSuppDoorPO.php",{jobRId:jobRowID} );
			}
			else if(jobType == 'Automatic')
			{
				$(".allCustPO").html("");
				$(".allCustPO").load("dist/php/AddSuppAutomaticPO.php",{jobRId:jobRowID} );
			}
			
		return false;
		});
	 
	 
	 $(".confirmOffers").click(function(){
		 
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
						
						if(doneConfirmJob == 1)
						{
							alert('Successfully Transfer Job To Exporting');
							$('.allNewOffers').html('');
							$('.allNewOffers').load("dist/php/allOffersForEdit.php");
							
						}
						else
						{
							alert(doneConfirmJob);
						}
					}
				 });
		 }
		 
		 return false;
		 });
    });

</script>
<div class="table-responsive-sm">
 <table class="myTableTS table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>PO</th>
          <th>Type</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Sales</th>
          <th>Delivery</th>
          <th><span data-toggle='tooltip' data-placement='left' title='Remaining for Delivery'>RFD</span></th>
          <th width="4%"></th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `customer`, `responsible`,
	 `jobtype`, `description`, `jobtype`, `salesman`,  `poref` FROM `job`
	  WHERE (`jobref` = 3 AND `jobtype` = 'Doors') OR (`jobref` = 3 AND `jobtype` = 'Automatic') ";
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
	$resGetPolicy= mysqli_fetch_assoc($queryGetPolicy);
	
	$sqlGetCustPO="SELECT `poId`, `PoNum` FROM `customerpo` 
	WHERE `jobidref` = $resGetAllNewJob[jobId]";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :01-AU_AU_S");
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
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
				title='Offer Number: $resGetAllNewJob[localref]'>$resGetCustPO[PoNum]</span></td>
				<td>$resGetAllNewJob[jobtype]</td>
				<td>$resGetCustomer[customername]</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetSales[username]</td>
				<td>$resGetPolicy[deliverydate]</td>
				<td style='color:$color'>$resGetPolicy[Deff]</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' 
				title='Add Supplier Order'>
					<button class='btn btn-xm btn-link editPO' 
					value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
						<i class='fa fa-cogs' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</span></td>
            </tr>
		
		
			";
	$ser++;		
	}

 ?>
    </tbody>  
 </table>
 </div>


