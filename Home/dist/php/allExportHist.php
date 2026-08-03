<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$btnRef = $_POST['bRef2'];

?>
<div class="body">
  <br>
<table class="myTableStockImport table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Doc No.</th>
          <th>Date</th>
          <th>Project</th>
          <th>Customer</th>
          <th>Staff</th>
          <th></th>
    </thead>
    <tbody>    
<?php
	$ser = 1;	
	$sqlGetExportStock="SELECT  DATE(`date`) AS ExportDate, 
	TIME(`date`) AS ExportTime, `poIdRef`, `custcode`, `responsible`, `docSerial` FROM `warehouse` 
	WHERE `export` != 0 AND `custcode` IS NOT NULL GROUP BY `docSerial`";
	$queryGetExportStock=mysqli_query($link,$sqlGetExportStock)or die("ERROR :01-AU_AU_S");
	if(mysqli_num_rows($queryGetExportStock) > 0)
	{
	while($resGetExportStock= mysqli_fetch_assoc($queryGetExportStock))
		{
						
	$sqlGetCust = "SELECT `customername` FROM `customers` WHERE `customercode` =
	$resGetExportStock[custcode]";
	$queryGetCust = mysqli_query($link,$sqlGetCust)or die("ERROR :02-ANJ_GCN_S");
	$resultGetCust = mysqli_fetch_array($queryGetCust);	
			
	$sqlGetJobRef = "SELECT `jobidref`, `PoNum`, `projectName` FROM `customerpo`, `job` WHERE `poId` =
	$resGetExportStock[poIdRef] AND `jobidref` = `jobId`";
	$queryGetJobRef=mysqli_query($link,$sqlGetJobRef)or die("ERROR :03-AU_AU_S");
	$resGetJobRef= mysqli_fetch_assoc($queryGetJobRef);
			
				echo "
				<tr>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Document Information'>
				<button class='btn btn-link btn-xs docInfo2' 
				value='$resGetExportStock[docSerial]'> $resGetExportStock[docSerial]</span></td>
				<td class='col-sm-3'><span data-toggle='tooltip' data-placement='left' 
				title='Time:$resGetExportStock[ExportTime]'>$resGetExportStock[ExportDate]</span></td>
				<td class='col-sm-3'><span data-toggle='tooltip' data-placement='left' 
				title='Time:$resGetJobRef[PoNum]'> $resGetJobRef[projectName]</span></td>
				<td class='col-sm-3'> $resultGetCust[customername]</td>
				
				<td class='col-sm-3'> $resGetExportStock[responsible]</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Print Document'>
				<button class='btn btn-link btn-xs PrintDoc2' 
				value='$resGetExportStock[docSerial]'>
				<i class='fas fa-print' aria-hidden='true' style='font-size:22px;color:#0275d8'></i>
				</button>
				</span>
				</td>
				</tr>
				";
	$ser++;
			}
	
	}
 ?>
	</tbody>
    
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
   
   var table = $('.myTableStockImport').DataTable( {
	 
	  fixedHeader: false,
             scrollY:'35vh',
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[1, "desc"]], 
		 
 dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'Stock_Import_History '+datetime,
			filename: function () {
			return "Stock_Import_History" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'Stock_Import_History '+datetime,
			 filename: function () {
			return "Stock_Import_History" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | Stock Import History '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1]
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
		
		
   $(".docInfo2").click(function(){
			
	$(".tooltip-inner").hide();
	$(".arrow").hide();		
			
			var DocNumberExpt = $(this).val();
			
				$.ajax({
						url:"dist/php/showExportDocInfo.php",
						type:"POST",
						data:{DNumExpt:DocNumberExpt},
						beforeSend: function(){
							$(".docInfo2").prop('disabled', true);		
						},
						success: function(doneShowDoc2){
							
							$(".docInfo2").prop('disabled', false);
 						 	$('.ShowStockDataHist').html('');
							$('.ShowStockDataHist').html(doneShowDoc2);
							$(".myModal").modal('toggle');
							
						}
					});
			
			
			return false;
			});
			
	$(".PrintDoc2").click(function(){
			
		$(".tooltip-inner").hide();
		$(".arrow").hide();		
		$(".PrintDoc2").prop('disabled', true);		
			
			var DocNumberExpt = $(this).val();
			
		var exportDocPrint = window.open("dist/php/printExportDocInfo.php?&DocNumPEx="+DocNumberExpt,"_balnk");							
							exportDocPrint.focus();
							setTimeout(function(){
								$(".PrintDoc2").prop('disabled', false);	
							}, 500);							
			
			return false;
			});	
    });
 
 
 </script>  
