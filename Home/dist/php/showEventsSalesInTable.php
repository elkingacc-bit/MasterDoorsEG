
 <table>
  <td >
  <button class="btn btn-danger btn-sm" id="backToClander">back</button>
  </td>
  </table>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");


$sqlGetAllEvents= "SELECT  `customerName`, `userCode`, `title`, `description` FROM `events` 
WHERE `ref` = 0 AND `title` != 'Start App'";
$queryGetAllEvents = mysqli_query($link,$sqlGetAllEvents)or die("ERROR :01-ANJ_GCN_S");
$ser= 1;
	echo "
	<table class='table table-sm table-striped myTableSalesEvent' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>No</th>
				<th>Title No</th>
                <th>Customer</th>
                <th>Description</th>
				<th>Added By</th>
             </thead>
			 <tbody class='table-bordered'>
	";
if(mysqli_num_rows($queryGetAllEvents) > 0)
{
	

while($resGetAllEvents = mysqli_fetch_assoc($queryGetAllEvents))
{
	
	$sqlGetUser = "SELECT  `fullname` FROM `users` WHERE  `codeid` 
	= '$resGetAllEvents[userCode]'";
	$queryGetUser = mysqli_query($link,$sqlGetUser)or die("ERROR :01-ANJ_GCN_S");
	$resGetUser = mysqli_fetch_assoc($queryGetUser);
	
		echo "
		<tr>
			<td class='col-sm-1'>$ser</td>
			<td class='col-sm-2'>$resGetAllEvents[title]</td>
			<td class='col-sm-2'>$resGetAllEvents[customerName]</td>
			<td class='col-sm-3'>$resGetAllEvents[description]</td>
			<td class='col-sm-2'>$resGetUser[fullname]</td>
			
		</tr>
	
	";
$ser++;
	}
	echo "
	</tbody>
	<tfoot class='bg-light'>
       	   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		  <th></th>
           
    </tfoot>
</table>
	";
}
?>		
      
      
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 
 
	  var table = $('.myTableSalesEvent').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: true ,

	});
	
	$("#backToClander").click(function(){
		
				 
				$('.data_display').html('');
				setTimeout(function(){		
	 			$('.data_display').load('dist/php/showEventsSales.php');
				}, 500);			
			return false;
		
		});		
	
});
 
 </script>