<?php
@session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 $salse = $_SESSION['code'];
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
   
   var table = $('.myTableExpt').DataTable( {
	 
	  fixedHeader: false,
             scrollY:'35vh',
			 scrollX: true,
			  deferRender:true,
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
			
  		}

   });
	 
	$(".returnOffersToEdit").click(function(){
		
		var jobRowIdReturn = $(this).val();
		 var validReturnToEdit = confirm("Please Confirm Send Offer back to Edit Section?");
		 
		 if(validReturnToEdit === true)
		 {
			 $.ajax({
				 
				 	url:"dist/php/returnJobOffer.php",
					type:"POST",
					data:{jRIDReturn:jobRowIdReturn},
					beforeSend: function(){
					$(".returnOffersToEdit").prop('disabled', true);	
					},
					success: function(doneRetuenJob){
						
						if(doneRetuenJob == 1)
						{
							alert('Successfully Transfer Job To Exporting');
							$('.allOffersExpt').html('');
							$('.allOffersExpt').load("dist/php/allOffersForExport.php");
							
						}
						else
						{
							alert(doneRetuenJob);
						}
					}
				 });
		 }
		 
		return false;
		});
		
	
	$(".exportOffers").click(function(){
		
		var jobRowIdExport = $(this).val().split(',')[0];
		var jobType = $(this).val().split(',')[1];
		
	if(jobType == "Doors")
	{
		$('.allOffersExpt').html("");
		$('.allOffersExpt').load("dist/php/exportOfferForm.php",{JRIDforExport:jobRowIdExport});
	}
	else if (jobType == "Automatic")
	{
		$('.allOffersExpt').html("");
		$('.allOffersExpt').load("dist/php/exportAutoOfferForm.php",{JRIDforExport:jobRowIdExport});
	}
	else if (jobType == "Stock")
	{
		$('.allOffersExpt').html("");
		$('.allOffersExpt').load("dist/php/exportStockOfferForm.php",{JRIDforExport:jobRowIdExport});
	}
	else if(jobType == 'Maintenance')
	{
		$('.allOffersExpt').html("");
		$('.allOffersExpt').load("dist/php/exportFreeOfferForm.php",{JRIDforExport:jobRowIdExport});
	}
	else
	{
		alert("Unexpected Error !!!");
	}
		return false;
		});	
		
	$(".updateOffers").click(function(){
		
		var updateJRowId = $(this).val();
		
		$.ajax({
                url:'dist/php/modleOfferUpdateStatus.php',
                type:'POST',
                data:{ModelJobRIDUp:updateJRowId},
                
				success: function(showOfferOptions)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showOfferOptions);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		
		return false;
		});	
	 
    });

</script>
<div class="table-responsive-sm">
 <table class="myTableExpt table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Ref</th>
          <th>Type</th>
          <th>Start</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Status</th>
          <th>Price</th>
          <th width="3%"></th>
          <th width="3%"></th>
          <th width="3%"></th>
    </thead>
    <tbody >
            
<?php
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `projectName`,`customer`, `offerValue`, `responsible`,
	 `offerStatus`,	`jobtype`, `description`, `jobtype`, `lastupdate`, `poref` FROM `job`
	  WHERE `jobref` = 2 AND `salesman` = $salse";
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
				<td>$resGetAllNewJob[jobtype]</td>
				<td>".date("d/m/Y", strtotime($resGetAllNewJob['startDate']))."</td>
				<td>$resGetCustomer[customername]</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetAllNewJob[offerStatus]</td>
				<td style='color:blue;'>".number_format($resGetAllNewJob['offerValue'])."</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Return to Edit'>
					<button class='btn btn-xm btn-link returnOffersToEdit' 
					value='$resGetAllNewJob[jobId]'>
						<i class='fas fa-undo' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Export'>
					<button class='btn btn-xm btn-link exportOffers' 
					value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
					<i class='fa fa-file-pdf' aria-hidden='true' style='font-size:20px;color:#14A44D'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Update Status'>
					<button class='btn btn-xm btn-link updateOffers' 
					value='$resGetAllNewJob[jobId]'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:20px;color:#292b2c'></i>
					</button>
				</span></td>	
            </tr>
		
		
			";
	$ser++;		
	}

 ?>
    </tbody> 
    <tfoot class='bg-light'>
    	  <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th ></th>
          <th></th>
          <th></th>
          <th></th>
    </tfoot> 
 </table>
 </div>


