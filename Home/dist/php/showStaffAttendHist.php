<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$date = date("Y-m-d");

?>
<style type="text/css">

h1{
	font-size:14px;
	}
</style>
<div class="body">
 <table class="myTableStaffAHist table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Name</th> 
          <th>Position</th> 
          <th>Customer</th>  
          <th>Project</th>
          <th>Date</th>
    </thead>
    <tbody>    
<?php
	$ser = 1;	
	$sqlCheckAttend = "SELECT `id`, `staffRId`, `poRowId`, `attendDate` FROM `outsidemanpower`
	ORDER BY `attendDate` DESC";
	$queryCheckAttend=mysqli_query($link,$sqlCheckAttend)or die("ERROR :01-AU_AU_S");
	while($resCheckAttend= mysqli_fetch_assoc($queryCheckAttend))
	{		
	$sqlGetFreeStaff="SELECT `staffname`, `staffposition` FROM `allstaff` 
	WHERE `id` = $resCheckAttend[staffRId]";
	$queryGetFreeStaff=mysqli_query($link,$sqlGetFreeStaff)or die("ERROR :01-AU_AU_S");
	$resGetFreeStaff= mysqli_fetch_assoc($queryGetFreeStaff);
	
	$sqlGetPOData="SELECT `custCode`, `PoNum`, `projectName` FROM `customerpo`, `job` 
	WHERE `poId` = $resCheckAttend[poRowId] AND `jobId` = `jobidref`";
	$queryGetPOData=mysqli_query($link,$sqlGetPOData)or die("ERROR :01-AU_AU_S");
	if(mysqli_num_rows($queryGetPOData) > 0)
	{
	$resGetPOData= mysqli_fetch_assoc($queryGetPOData);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetPOData[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
		
				echo "
				<tr>
				<td class='col-sm-0' class='ItemTypeTh'> $ser</td>
				<td class='col-sm-2'> $resGetFreeStaff[staffname]</td>
				<td class='col-sm-2'> $resGetFreeStaff[staffposition]</td>
				<td class='col-sm-3'> $resGetCustomer[customername]</td>
				<td class='col-sm-2'><span data-toggle='tooltip' data-placement='left' 
				title='$resGetPOData[PoNum]'>$resGetPOData[projectName]</span></td>
				<td class='col-sm-3'>".date("d/m/Y", strtotime($resCheckAttend['attendDate']))."</td>
				</tr>
				";
	}
	$ser++;
	
			}
	
 ?>
	</tbody>
    <tfoot>
    	  <th>No.</th>
          <th>Name</th> 
          <th>Position</th> 
          <th>Customer</th>  
          <th>Project</th>
          <th>Date</th>
    </tfoot>
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
				
				
 $('.myTableStaffAHist tfoot th').each( function () {
        var title = $(this).text();
		if(title === 'Name' || title === 'Position' || title === 'Customer' || title === 'Project' || title === 'Date')
		{
        	$(this).html( '<input type="text" class="form-control" placeholder="'+title+'" />' );
		}
    } );					
   
   var table = $('.myTableStaffAHist').DataTable( {
	 
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
			title:'All_Attendance_outside_manpower_Staff '+datetime,
			filename: function () {
			return "All_Attendance_outside_manpower_Staff" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Attendance_outside_manpower_Staff '+datetime,
			 filename: function () {
			return "All_Attendance_outside_manpower_Staff" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [ 0,1,2,3,4,5]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | All Attendance Outside Manpower Staff '+datetime,
	  footer: false,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5]
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

  table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
					});
		
});
 
 
 </script>  
