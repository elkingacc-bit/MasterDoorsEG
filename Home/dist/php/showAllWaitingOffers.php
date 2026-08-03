
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
							$('.data_display').html('');
							$('.data_display').load("dist/php/showAllWaitingOffers.php");
							
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
 <table class="myTable table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Ref</th>
          <th>Start</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Sales</th>
          <th>Status</th>
          <th>Type</th>
          <th>Value</th>
          <th width="4%"></th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `customer`, `offerValue`, `responsible`,
	 `offerStatus`,	`jobtype`, `description`, `jobtype`, `salesman`, `lastupdate`, `poref` FROM `job`
	  WHERE `jobref` = 1 ";
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
				<td class='col-sm-0'>$ser</td>
				<td><span data-toggle='tooltip' data-placement='left' 
				title='Show Offer Info'><button class='btn btn-link showOrderInfo' 
				value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>$resGetAllNewJob[localref]
				</button></span></td>
				<td>".date("d/m/Y", strtotime($resGetAllNewJob['startDate']))."</td>
				<td>$resGetCustomer[customername]</td>
				<td class='col-sm-2'>$resGetAllNewJob[description]</td>
				<td>$resGetSales[username]</td>
				<td>$resGetAllNewJob[offerStatus]</td>
				<td>$resGetAllNewJob[jobtype]</td>
				<td style='color:blue'>".number_format($resGetAllNewJob['offerValue'])."</td>
              	
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
    </tfoot>
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

