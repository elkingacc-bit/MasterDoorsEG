<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
$salseCode = $_SESSION['code'];
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
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","green");
			
  		}

   });
	 
 
	/*	 $(".EditBasicInof").click(function(){
			 
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
			 });*/
    
	});
	
</script>
<div class="table-responsive">
 <table class="myTable table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Project</th>
          <th>Start</th>
          <th>Customer</th>
          <th>Note</th>
          <th>Status</th>
          <th>Type</th>
          <th>Value</th>
          <th>Commission</th>
    </thead>
    <tbody >
            
<?php
$ser = 1;
	$sqlGetAllNewJob="SELECT `jobId`, `startDate`, `localref`, `projectName`,`customer`, `Commotion`,
	`offerValue`, `responsible`, `offerStatus`,	`jobtype`, `description`, `jobtype`, `lastupdate`
	, `poref` FROM `job`  WHERE (`jobref` = 1 AND `salesman` = $salseCode) OR  (`jobref` IS NULL AND `salesman` = $salseCode)";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	while($resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob))
	{
	
		$orderVal = $resGetAllNewJob['offerValue'];
		$commissionPresnt = $resGetAllNewJob['Commotion'];
		$commission = round($orderVal * $commissionPresnt);
		
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
				<td>".number_format($orderVal)."</td>
				<td data-toggle='tooltip' data-placement='left' title='Commission 
				= ".round($commissionPresnt * 100)."%'>".number_format($commission)."</td>
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
    </tfoot>
 </table>
 </div>


