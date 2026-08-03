<?php
@session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 $refrance = $_POST['RPTRef'];
 $salesCode = $_SESSION['code'];
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
	
	$(".tooltip-inner").hide();
	$(".arrow").hide();
		
	var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();			
   
   var table = $('.myTableWon').DataTable( {
	 
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
			title:'history_Offers_Report '+datetime,
			filename: function () {
			return "history_Offers_Report" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6,7]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'history_Offers_Report '+datetime,
			 filename: function () {
			return "history_Offers_Report" },
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
	  title:'Maintenance Tracker System {Master Doors EG} | Offers History Report '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5,6,7]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
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
            .column( 6 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 6, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 6 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

   });
   
   $(".showAllOfferInfo").click(function(){
	   
	   var offerRID = $(this).val().split(',')[0];
	   var JobType = $(this).val().split(',')[1];
	   
	   if(JobType == "Doors")
	   {
	   
	   	$('.allOffersHist').html("");
		$('.allOffersHist').load("dist/php/WonOffersDetialsD.php",{jobRId:offerRID});
	   }
	   else if(JobType == "Automatic")
	   {
	   
	   	$('.allOffersHist').html("");
		$('.allOffersHist').load("dist/php/WonOffersDetialsA.php",{jobRId:offerRID});
	   }
	   else if(JobType == "Stock")
	   {
	   
	   	$('.allOffersHist').html("");
		$('.allOffersHist').load("dist/php/WonOffersDetialsS.php",{jobRId:offerRID});
	   } 
	   return false;
	   });

	   $(".PrintOldOffer").click(function(){
		   
		    var offerRID2 = $(this).val().split(',')[0];
	   		var JobType2 = $(this).val().split(',')[1];
			
		 if(JobType2 == "Doors")
		   {
		   
			var newDocPrint = window.open("dist/php/printItemOffer.php?&JobId="+offerRID2,"_balnk");							
				setTimeout(function(){
					newDocPrint.focus();
				}, 500);
		   }
		   else if(JobType2 == "Automatic")
		   {
		   
			var newAutoDocPrint = window.open("dist/php/printAutoOffer.php?&JobId="+offerRID2,"_balnk");							
				setTimeout(function(){
					newAutoDocPrint.focus();
				}, 500);
		   }
		   else if(JobType2 == "Stock")
		   {
		   
			var newAutoDocPrint = window.open("dist/php/printStockOffer.php?&JobId="+offerRID2,"_balnk");							
				setTimeout(function(){
				    newAutoDocPrint.focus();
				}, 500);
		   } 
		   else if(JobType2 == "Maintenance")
		   {
		   
			var newAutoDocPrint = window.open("dist/php/printFreeOffer.php?&JobId="+offerRID2,"_balnk");							
				setTimeout(function(){
				    newAutoDocPrint.focus();
				}, 500);
		   } 	
		   
		   return false;
		   });
	 	 
});

</script>
<input type="text" value="<?php echo $refrance;?>" id="pageRef" style="display:none"/>
<div class="table-responsive-sm">
 <table class="myTableWon table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Ref</th>
          <th>Start</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Type</th>
          <th>Value</th>
          <th>End</th>
          <th width="2%"></th>
          <th width="2%"></th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `projectName`,`customer`, `offerValue`, `responsible`,
	 `offerStatus`,	`jobtype`, `description`, `jobtype`, `lastupdate`, `poref`, `endDate` 
	 FROM `job` WHERE `jobref` = $refrance AND `salesman` = $salesCode";
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
				<td>$resGetAllNewJob[jobtype]</td>
				<td style='color:blue'>".number_format($resGetAllNewJob['offerValue'])."</td>
				<td>".date("d/m/Y", strtotime($resGetAllNewJob['endDate']))."</td>
              	<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='All Info'>
					<button class='btn btn-xm btn-link showAllOfferInfo' 
					value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
					<i class='fa fa-search-plus' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</span></td>
				<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Print'>
					<button class='btn btn-xm btn-link PrintOldOffer' 
					value='$resGetAllNewJob[jobId],$resGetAllNewJob[jobtype]'>
					<i class='fa fa-print' aria-hidden='true' style='font-size:18px;color:#0275d8'></i>
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


