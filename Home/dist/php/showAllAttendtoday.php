<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$date = $_POST['DateVal'];

//date("Y-m-d");
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
<div class="body">
 <table class="myTableEditStaff table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Name</th> 
          <th>Position</th> 
          <th>Customer</th>  
          <th>Project</th>
          <th>Pelanty</th>
          <th>Reword</th>
          <th>edit</th>
    </thead>
    <tbody>    
<?php
	$ser = 1;	
	$sqlCheckAttend = "SELECT `id`, `staffRId`, `attendDate`, `poRowId`, `penalty`, `Reward` 
	FROM `outsidemanpower` WHERE `attendDate` = '$date'";
	$queryCheckAttend=mysqli_query($link,$sqlCheckAttend)or die("ERROR :01-AU_AU_S");
	while($resCheckAttend= mysqli_fetch_assoc($queryCheckAttend))
	{		
	$sqlGetFreeStaff="SELECT `staffname`, `staffposition` FROM `allstaff` 
	WHERE `id` = $resCheckAttend[staffRId]";
	$queryGetFreeStaff=mysqli_query($link,$sqlGetFreeStaff)or die("ERROR :01-AU_AU_S");
	$resGetFreeStaff= mysqli_fetch_assoc($queryGetFreeStaff);
	
	$sqlGetPOData="SELECT `custCode`, `PoNum`, `jobidref` FROM `customerpo` 
	WHERE `poId` = $resCheckAttend[poRowId]";
	$queryGetPOData=mysqli_query($link,$sqlGetPOData)or die("ERROR :01-AU_AU_S");
	$resGetPOData= mysqli_fetch_assoc($queryGetPOData);
	
	$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` = $resGetPOData[jobidref]";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :01-AU_AU_S");
	$resGetProject= mysqli_fetch_assoc($queryGetProject);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetPOData[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	
	if($resCheckAttend['penalty'] == '.25')
	{
		$pelntyVal = '1/4 day';
	}
	else if($resCheckAttend['penalty'] == '.5')
	{
		$pelntyVal = '1/2 day';
	}
	else if($resCheckAttend['penalty'] == '1')
	{
		$pelntyVal = '1 day';
	}
	else if($resCheckAttend['penalty'] == '1.5')
	{
		$pelntyVal = '1 & 1/2 day';
	}
	else if($resCheckAttend['penalty'] == '2')
	{
		$pelntyVal = '2 days';
	}
	else if($resCheckAttend['penalty'] == '0')
	{
		$pelntyVal = '--';
	}
	
	if($resCheckAttend['Reward'] == '.25')
	{
		$RewardVal = '1/4 day';
	}
	else if($resCheckAttend['Reward'] == '.5')
	{
		$RewardVal = '1/2 day';
	}
	else if($resCheckAttend['Reward'] == '1')
	{
		$RewardVal = '1 day';
	}
	else if($resCheckAttend['Reward'] == '1.5')
	{
		$RewardVal = '1 & 1/2 day';
	}
	else if($resCheckAttend['Reward'] == '2')
	{
		$RewardVal = '2 days';
	}
	else if($resCheckAttend['Reward'] == '0')
	{
		$RewardVal = '--';
	}
		
				echo "
				<tr>
				<td class='col-sm-1' class='ItemTypeTh'> $ser</td>
				<td class='col-sm-3'> $resGetFreeStaff[staffname]</td>
				<td class='col-sm-3'> $resGetFreeStaff[staffposition]</td>
				<td class='col-sm-3'> $resGetCustomer[customername]</td>
				<td class='col-sm-3'> $resGetProject[projectName]</td>
				<td class='col-sm-3'> $pelntyVal</td>
				<td class='col-sm-3'> $RewardVal</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='Edit staff Attend'>
				<button class='btn btn-link btn-xs editStaffAttend' 
				value='$resCheckAttend[id]'>
				<i class='fas fa-edit' aria-hidden='true' style='font-size:22px;color:#0275d8'></i>
				</button>
				</span>
				</td>
				</tr>
				";
	$ser++;
			}
	
 ?>
	</tbody>
    
 </table> 
  <input type="date"  style="display:none" id="oldDate" value="<?php echo $date?>"/>
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
   
   var table = $('.myTableEditStaff').DataTable( {
	 
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
				
              columns: [  0,1,2,3,4]
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
				
              columns: [ 0,1,2,3,4]
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
		   
                   columns: [ 0,1,2,3,4]
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
		
		
   $(".editStaffAttend").click(function(){
			
	$(".tooltip-inner").hide();
	$(".arrow").hide();		
			
	var staffRowIdEdit = $(this).val();
	var oldSelectedDate = $("#oldDate").val();
			
	$.ajax({
                url:'dist/php/editStaffAttendoModel.php',
                type:'POST',
                data:{attendRowId:staffRowIdEdit,OldDateVal:oldSelectedDate},
                
				success: function(showAttendForm)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showAttendForm);
				$(".myModal").modal('toggle');
				
				}         
        	}); 		
						
			
			return false;
			});
    });
 
 
 </script>  
