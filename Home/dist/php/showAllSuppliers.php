
<style>
h1 {font-size:14px; font-weight:bold;
}
</style>

<script type="text/javascript">
	$(document).ready(function() {
		
$("#AllManufList").load("dist/php/allManufDDList.php");		
		
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
			title:'All_Suppliers_Info '+datetime,
			filename: function () {
			return "All_Suppliers_Info" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Suppliers_Info '+datetime,
			 filename: function () {
			return "All_Suppliers_Info" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Maintenance Tracker System {Kandil Glass Manufacturing} | All Suppliers Info '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2]
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
	 
	$(".editSupplier").click(function(){
		
		var supplierID = $(this).val();
			$(".showData").html("");
			$(".showData").load("dist/php/editSupplierForm.php",{supplierRId:supplierID} );
		
		return false;
		});
	 
    });

</script>
 <table class="myTable table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Supplier Name</th>
          <th>Manufctuer</th>
          <th>Location</th>
          <th>Edit</th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetSuppliers="SELECT `id`, `suppliercode`, `suppliername`, `suppcountry` FROM `allsuppliers`";
	$queryGetSuppliers=mysqli_query($link,$sqlGetSuppliers)or die("ERROR :01-AU_AU_S");
	while($resGetSuppliers = mysqli_fetch_assoc($queryGetSuppliers))
	{
		$ManufOldCode = substr($resGetSuppliers['suppliercode'], 0, 6);
		
	$sqlGetAllCode="SELECT `allcodeid` FROM `allcode` WHERE `code` 
	= $resGetSuppliers[suppliercode]";
	$queryGetAllCode=mysqli_query($link,$sqlGetAllCode)or die("ERROR :02-AU_AU_S");
	$resGetAllCode = mysqli_fetch_assoc($queryGetAllCode);
	
	$sqlGetManuf="SELECT `manufactuername` FROM `allmanufactuers` WHERE `manufactuercode` 
	= $ManufOldCode";
	$queryGetManuf=mysqli_query($link,$sqlGetManuf)or die("ERROR :03-AU_AU_S");
	$resGetManuf = mysqli_fetch_assoc($queryGetManuf);
		echo "
			<tr>
				<td>$ser</td>
				<td>$resGetSuppliers[suppliername]</td>
				<td>$resGetManuf[manufactuername]</td>
				<td>$resGetSuppliers[suppcountry]</td>
              	<td>
					<button class='btn btn-xm btn-link editSupplier' value='$resGetSuppliers[id],
					$resGetAllCode[allcodeid]'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</td>	
            </tr>
		
		
			";
	$ser++;		
	}

 ?>
    </tbody>  
 </table>


