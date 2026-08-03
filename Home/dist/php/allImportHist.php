<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$btnRef = $_POST['bRef'];

?>
<div class="body">
<br>
<table class="myTableStockImport table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Doc No.</th>
          <th>Date</th>
          <th>Invoice</th>
          <th>Supplier</th>
          <th>Staff</th>
          <th></th>
    </thead>
    <tbody>    
<?php
	$ser = 1;	
	$sqlGetImportStock="SELECT  DATE(`date`) AS ImportDate, 
	TIME(`date`) AS ImportTime, `invoicenumber`, `supplier`, `responsible`, `docSerial` FROM `warehouse` 
	WHERE `supplier` != 0 AND `invoicenumber` != 0 GROUP BY `docSerial`";
	$queryGetImportStock=mysqli_query($link,$sqlGetImportStock)or die("ERROR :01-AU_AU_S");
	if(mysqli_num_rows($queryGetImportStock) > 0)
	{
	while($resGetImportStock= mysqli_fetch_assoc($queryGetImportStock))
		{
						
			$sqlGetSupp = "SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` =
			$resGetImportStock[supplier]";
			$queryGetSupp = mysqli_query($link,$sqlGetSupp)or die("ERROR :02-ANJ_GCN_S");
			$resultGetSupp = mysqli_fetch_array($queryGetSupp);	
			
				echo "
				<tr>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Document Information'>
				<button class='btn btn-link btn-xs docInfo' 
				value='$resGetImportStock[docSerial]'> $resGetImportStock[docSerial]</span></td>
				<td class='col-sm-3'><span data-toggle='tooltip' data-placement='left' 
				title='Time:$resGetImportStock[ImportTime]'>$resGetImportStock[ImportDate]</span></td>
				<td class='col-sm-3'> $resGetImportStock[invoicenumber]</td>
				<td class='col-sm-3'> $resultGetSupp[suppliername]</td>
				
				<td class='col-sm-3'> $resGetImportStock[responsible]</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Print Document'>
				<button class='btn btn-link btn-xs PrintDoc' 
				value='$resGetImportStock[docSerial]'>
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
		
		
   $(".docInfo").click(function(){
			
	$(".tooltip-inner").hide();
	$(".arrow").hide();		
			
			var DocNumber = $(this).val();
			
				$.ajax({
						url:"dist/php/showImportDocInfo.php",
						type:"POST",
						data:{DNum:DocNumber},
						beforeSend: function(){
							$(".docInfo").prop('disabled', true);		
						},
						success: function(doneShowDoc){
							
							$(".docInfo").prop('disabled', false);
 						 	$('.ShowStockDataHist').html('');
							$('.ShowStockDataHist').html(doneShowDoc);
							$(".myModal").modal('toggle');
							
						}
					});
			
			
			return false;
			});
			
	$(".PrintDoc").click(function(){
			
		$(".tooltip-inner").hide();
		$(".arrow").hide();		
		$(".PrintDoc").prop('disabled', true);		
			
			var DocNumber2 = $(this).val();
			
		var importDocPrint = window.open("dist/php/printImportDocInfo.php?&DocNumP="+DocNumber2,"_balnk");							
							importDocPrint.focus();
							setTimeout(function(){
								$(".PrintDoc").prop('disabled', false);	
							}, 500);							
			
			return false;
			});	
    });
 
 
 </script>  
