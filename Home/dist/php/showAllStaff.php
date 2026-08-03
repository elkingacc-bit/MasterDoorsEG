
<style>
h1 {font-size:14px; font-weight:bold;
}
</style>

<script type="text/javascript">
	$(document).ready(function() {
		
		
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
			title:'All_Staff_Info '+datetime,
			filename: function () {
			return "All_Staff_Info" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Staff_Info '+datetime,
			 filename: function () {
			return "All_Staff_Info" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Maintenance Tracker System {Master Doors EG} | All Staff Info '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3]
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
		
		var staffRowID = $(this).val();
			$(".showData").html("");
			$(".showData").load("dist/php/editStaffForm.php",{staffRId:staffRowID} );
		
		return false;
		});
	 
    });

</script>
 <table class="myTable table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Name</th>
          <th>Position</th>
          <th>Day Value</th>
          <th>Edit</th>
    </thead>
    <tbody >
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$ser = 1;
	$sqlGetStaff="SELECT `id`, `staffname`, `staffposition`, `dayVal` FROM `allstaff`";
	$queryGetStaff=mysqli_query($link,$sqlGetStaff)or die("ERROR :01-AU_AU_S");
	while($resGetStaff= mysqli_fetch_assoc($queryGetStaff))
	{
	
		echo "
			<tr>
				<td>$ser</td>
				<td>$resGetStaff[staffname]</td>
				<td>$resGetStaff[staffposition]</td>
              	<td>$resGetStaff[dayVal]</td>
				<td>
					<button class='btn btn-xm btn-link editSupplier' value='$resGetStaff[id]'>
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


