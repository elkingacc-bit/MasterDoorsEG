<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

?>
 <input type="text" value="<?php ?>" style="display:none" id="PoRId"/>
  <input type="text" value="<?php ?>" style="display:none" id="PoNumber"/>
  <input type="date" value="<?php ?>" style="display:none" id="chosenDate"/>  
 <div class="body">
 
<br>    
<table class="myTableAllStaff table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Part No.</th> 
          <th>Item</th>  
          <th>QTY</th>
          <th>Date</th>
          <th>Project</th>
          <th>Customer</th>
          <th>Staff</th>
          <th></th>
    </thead>
    <tbody>    
<?php
	$ser = 1;	
	$sqlGetExptStock="SELECT `warehouseId` ,`description`, `export`, DATE(`date`) AS expDate, 
	TIME(`date`) AS expTime, `responsible`, `custcode`, `poIdRef` FROM `warehouse` WHERE `whref` = 1 
	AND `custcode` IS NOT NULL AND `invoicenumber` = 0";
	$queryGetExptStock=mysqli_query($link,$sqlGetExptStock)or die("ERROR :01-AU_AU_S");
	if(mysqli_num_rows($queryGetExptStock) > 0)
	{
	while($resGetExptStock= mysqli_fetch_assoc($queryGetExptStock))
		{
			$sqlGetItemData = "SELECT `descriptionname`, `partnumber` FROM `stockitems` 
			WHERE `description` = $resGetExptStock[description]";
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_assoc($queryGetItemData);
			
			$sqlGetCust = "SELECT `customername` FROM `customers` WHERE `customercode` =
			$resGetExptStock[custcode]";
			$queryGetCust = mysqli_query($link,$sqlGetCust)or die("ERROR :02-ANJ_GCN_S");
			$resultGetCust = mysqli_fetch_array($queryGetCust);	
			
			$sqlGetJobRef = "SELECT `jobidref`, `PoNum` FROM `customerpo` WHERE `poId` =
			 $resGetExptStock[poIdRef]";
			$queryGetJobRef=mysqli_query($link,$sqlGetJobRef)or die("ERROR :03-AU_AU_S");
			$resGetJobRef= mysqli_fetch_assoc($queryGetJobRef);
			
			$sqlGetProject = "SELECT `projectName` FROM `job` WHERE `jobId` =
			 $resGetJobRef[jobidref]";
			$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :03-AU_AU_S");
			$resGetProject= mysqli_fetch_assoc($queryGetProject);
			
			$sqlCheckJobInvo = "SELECT `invoice` FROM `job` WHERE `jobId` = $resGetJobRef[jobidref] 
			AND `invoice` = 'No'";
			$queryCheckJobInvo=mysqli_query($link,$sqlCheckJobInvo)or die("ERROR :04-AU_AU_S");
			if(mysqli_num_rows($queryCheckJobInvo) > 0)
			{
				echo "
				<tr>
				<td class='col-sm-1' class='ItemTypeTh'> $ser</td>
				<td class='col-sm-3'> $resGetItemData[partnumber]</td>
				<td class='col-sm-3'> $resGetItemData[descriptionname]</td>
				<td class='col-sm-3'> $resGetExptStock[export]</td>
				<td class='col-sm-3'><span data-toggle='tooltip' data-placement='left' 
				title='Time:$resGetExptStock[expTime]'>$resGetExptStock[expDate]</span></td>
				<td class='col-sm-3'><span data-toggle='tooltip' data-placement='left' 
				title='$resGetJobRef[PoNum]'> $resGetProject[projectName]</span></td>
				<td class='col-sm-3'> $resultGetCust[customername]</td>
				<td class='col-sm-3'> $resGetExptStock[responsible]</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='return Stock to Warehouse'>
				<button class='btn btn-link btn-xs returnStock' 
				value='$resGetExptStock[warehouseId]'>
				<i class='fas fa-undo' aria-hidden='true' style='font-size:22px;color:#0275d8'></i>
				</button>
				</span>
				</td>
				</tr>
				";
	$ser++;
			}
	
		}
	}
 ?>
	</tbody>
    
 </table> 
  <input type="number"  style="display:none" id="back1"/>
  <input type="number"  style="display:none" id="back2"/>
 
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
   
   var table = $('.myTableAllStaff').DataTable( {
	 
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
			title:'All_outside_manpower_Staff '+datetime,
			filename: function () {
			return "All_outside_manpower_Staff" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_outside_manpower_Staff '+datetime,
			 filename: function () {
			return "All_outside_manpower_Staff" },
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
	  title:'Company Managment System {Master Doors EG} | All Outside Manpower Staff '+datetime,
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
		
		
   $(".returnStock").click(function(){
			
	$(".tooltip-inner").hide();
	$(".arrow").hide();		
			
			var whRowID = $(this).val();
			
			var confirmReturn = confirm("Confirm Return exported QTY to stock ?");		
						
			if(confirmReturn === true)
		    {
				$.ajax({
						url:"dist/php/saveReturnStock.php",
						type:"POST",
						data:{WRID:whRowID},
						beforeSend: function(){
							$(".returnStock").prop('disabled', true);		
						},
						success: function(doneReturnStock){
							
							if(doneReturnStock == 1)
							{
								alert("Data Saved");
								$(".returnStock").prop('disabled', false);	
								
								$('.allExported').	html('');	
								$('.allExported').load("dist/php/allExportedItems.php");
							}
							else
							{
								alert(doneReturnStock);
								$(".returnStock").prop('disabled', false);	
							}
							
						}
					});
			}
			
			
			return false;
			});
    });
 
 
 </script>  
