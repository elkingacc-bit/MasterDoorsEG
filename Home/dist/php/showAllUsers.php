<table class="table table-sm table-striped table-bordered myTableUsers" cellspacing="0" width="99%">
	<thead class="bg-warning">
    	<th>Name</th>
        <th>User Name</th>
        <th>Department</th>
        <th>Status</th>
   
    </thead>
	<tbody >
<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$sqlCatchUsers ="SELECT  `userid`, `fullname`,  `email`, `username`, `department`, `userType`,  `empphone`
, `validation`, `ref` FROM `users`";
$queryCatchUsers=mysqli_query($link,$sqlCatchUsers) or die("ERROR :01-SCU_SAE_S");

while($totalData=mysqli_fetch_array($queryCatchUsers))
{
	$locked="<button title='Disable User' class='btn btn-dangre btn-sm blokUser' value='$totalData[0]'><span style='color:red' class='fas fa-lock'></span></button>";
	if($totalData[7] == 6)
	{
		$status="<button class='btn btn-primary btn-sm blokedU hidden-print' value='$totalData[0]'>Locked</button>";
	}
	else if($totalData[8] == NULL)
	{
		$status="<span style='color:red'>Desiccative $locked</span>";
	}
	else if($totalData[8] == 1)
	{
		$status="<span style='color:green'>Activeted  $locked</span>";
	}
	//<td>$totalData[2]</td><td>$totalData[6]</td><td>$totalData[5]</td>
	
	echo "
		<tr>
			<td>$totalData[1]</td>
			<td>$totalData[3]</td>
			
			<td>$totalData[4]</td>
			
			
			<td align='center'>$status </td>
		</tr>
	";
}

?>

  </tbody>
</table>
<script type="text/javascript">

var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();			
   
   var table = $('.myTableUsers').DataTable( {
	 
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
			title:'All_Users_Report '+datetime,
			filename: function () {
			return "All_Users_Report" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Users_Report '+datetime,
			 filename: function () {
			return "All_Users_Report" },
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
	  title:'Maintenance Tracker System {Master Doors EG} | All Users Report '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
	}
 ],			 
 
   });


$(".blokedU").click(function(){
		var unLockUID=$(this).val();
		$(".blockedUsers").load("dist/php/blockedUser.php?UID="+unLockUID);
$(document).keydown(function(e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        $(".blockedUsers").empty();
    }
});

		});
		
		$(".blokUser").click(function(){
		var LockUID=$(this).val();
		var confLock=confirm("Are you sure you want lock this user?");
			if(confLock == true)
			{
				$.ajax({
						url:"dist/php/lockuser.php",
						type:"POST",
						data:{lockUID:LockUID},
						success: function(LokedUser){
							if(LokedUser == 1)
							{
							$("#1_2").click();
							}
							else
							{
								alert(LokedUser);
							}
						}
					});
			}
		});
</script>

