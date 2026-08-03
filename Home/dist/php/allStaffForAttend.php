<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$PoRowId = $_POST['PoRowId'];
$PoNum = $_POST['PoNum'];
$chosenDate = $_POST['attendDate'];

$date = date("Y-m-d");

?>
 <input type="text" value="<?php echo $PoRowId;?>" style="display:none" id="PoRId"/>
  <input type="text" value="<?php echo $PoNum;?>" style="display:none" id="PoNumber"/>
  <input type="date" value="<?php echo $chosenDate;?>" style="display:none" id="chosenDate"/>  
 <div class="body">
 
<br>    
<table class="myTableAllStaff table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Name</th> 
          <th>Position</th>  
          <th>Attend</th>
    </thead>
    <tbody>    
<?php
	$ser = 1;	
	$sqlGetFreeStaff="SELECT `id`,`staffname`, `staffposition` FROM `allstaff`";
	$queryGetFreeStaff=mysqli_query($link,$sqlGetFreeStaff)or die("ERROR :01-AU_AU_S");
	while($resGetFreeStaff= mysqli_fetch_assoc($queryGetFreeStaff))
		{
			$sqlCheckAttend = "SELECT `id` FROM `outsidemanpower` WHERE `attendDate` = '$chosenDate' 
			AND `staffRId` = $resGetFreeStaff[id]";
			$queryCheckAttend=mysqli_query($link,$sqlCheckAttend)or die("ERROR :01-AU_AU_S");
			if(mysqli_num_rows($queryCheckAttend) == 0)
			{
				echo "
				<tr>
				<td class='col-sm-1' class='ItemTypeTh'> $ser</td>
				<td class='col-sm-3'> $resGetFreeStaff[staffname]</td>
				<td class='col-sm-3'> $resGetFreeStaff[staffposition]</td>
				<td class='col-sm-1'>
				<span data-toggle='tooltip' data-placement='left' title='add staff Attend'>
				<button class='btn btn-link btn-xs addStaffAttend' 
				value='$resGetFreeStaff[id]'>
				<i class='fas fa-calendar-check' aria-hidden='true' style='font-size:22px;color:#0275d8'></i>
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
		
		
   $(".addStaffAttend").click(function(){
			
	$(".tooltip-inner").hide();
	$(".arrow").hide();		
			
			var staffRowId = $(this).val();
			var poRowId = $("#PoRId").val();
			var PoNumber = $("#PoNumber").val();
			var CustPOAttend2 = $("#allValidPO").val();
			var dateChosen = $("#chosenDate").val();
			var PoChosenValideate2 = $('#AllCustPo [value="' + CustPOAttend2 + '"]');		
						
			if(PoChosenValideate2.length <= 0)
		    {
				alert('Please Choose Valid Customer name / PO Number form the list');
				$("#allValidPO").css("border-color","red");
			  setTimeout(function(){
			   $("#allValidPO").css("border-color","#EBEBEB");    						
			   $("#allValidPO").val('');	
			   $("#allValidPO").focus();							
			  }, 1500);
			}
			else 
			{
				$.ajax({
						url:"dist/php/saveAddStaffAttend.php",
						type:"POST",
						data:{poRID:poRowId,poNo:PoNumber,staffAttedRID:staffRowId,selectedDate:dateChosen},
						beforeSend: function(){
							$(".addStaffAttend").prop('disabled', true);		
						},
						success: function(doneAddStaffAttend){
							
							if(doneAddStaffAttend == 1)
							{
								alert("Data Saved");
								$("#allValidPO").val('');
								$(".addStaffAttend").prop('disabled', false);	
								
								$(".AddAttend").html('');
								$(".AddAttend").load("dist/php/allStaffForAttend.php",{PoNum:PoNumber, PoRowId:poRowId, attendDate:dateChosen});
							}
							else
							{
								alert(doneAddStaffAttend);
								$(".addStaffAttend").prop('disabled', false);	
							}
							
						}
					});
			}
			
			
			return false;
			});
    });
 
 
 </script>  
