<?php
@session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
?>
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
 
 /* "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 7 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 7, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 7 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}*/

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
	 
	 
	 $(".endOffers").click(function(){
		 
		 var jobRowIdTConf = $(this).val();
		 var validTConfirm = confirm("Please Confirm Send Offer to Quotation Approval Section?");
		 
		 if(validTConfirm === true)
		 {
			 $.ajax({
				 
				 	url:"dist/php/confirmTechnicalOffer.php",
					type:"POST",
					data:{jRIDTConf:jobRowIdTConf},
					beforeSend: function(){
					$(".endOffers").prop('disabled', true);	
					},
					success: function(doneConfirmTJob){
						
						if(doneConfirmTJob == 1)
						{
							alert('Successfully Transfer Offer To Quotation Approval');
							$('.allNewOffers').html('');
							$('.allNewOffers').load("dist/php/allOffersForEditSales.php");
							$(".endOffers").prop('disabled', false);	
							
						}
						else
						{
							alert(doneConfirmTJob);
							$(".endOffers").prop('disabled', false);	
						}
					}
				 });
		 }
		 
		 return false;
		 });
    });

</script>
<div class="table-responsive-sm">
 <table class="myTable table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Ref</th>
          <th>Start</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Status</th>
          <th>Type</th>
          
          <th width="3%"></th>
          <th width="3%"></th>
    </thead>
    <tbody >
            
<?php
$ser = 1;
$sales =  $_SESSION['code'];
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `projectName`,`customer`, `offerValue`, `responsible`,
	 `offerStatus`,	`jobtype`, `description`, `jobtype`, `lastupdate`, `poref` FROM `job`
	  WHERE `jobref` IS NULL";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	while($resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob))
	{
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
		
		echo "
			<tr>
				<td class='col-sm-0'>$ser</td>
				<td><span data-toggle='tooltip' data-placement='left' title='$resGetAllNewJob[localref]'>
				$resGetAllNewJob[projectName]</span></td>
				<td>".date("d/m/Y", strtotime($resGetAllNewJob['startDate']))."</td>
				<td>$resGetCustomer[customername]</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetAllNewJob[offerStatus]</td>
				<td>$resGetAllNewJob[jobtype]</td>
				
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Edit'>
					<button class='btn btn-xm btn-link editOffers' 
					value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Confirm'>
					<button class='btn btn-xm btn-link endOffers' 
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
   
 </table>
 </div>


