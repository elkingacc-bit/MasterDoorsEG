<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
$RefVal = $_POST['Ref'];

if($RefVal == 1)
{
	$cloumn = "categoryname";
	$th = "Group Name";
	$default = "default";
}
else if($RefVal == 2)
{
	$cloumn = "subcategoryname";
	$th = "Sub Group Name";
	$default = "S-default";
}

else if($RefVal == 3)
{
	$cloumn = "subSCatgName";
	$th = "Sub Sub Group Name";
	$default = "S-S-default";
}

?>


<div>
	<table class="myTable table table-striped table-bordered" cellspacing="0" width="99%">
    	<thead class="bg-warning">
       		 <th><?php echo $th ; ?></th>
        	 <th></th>
        </thead>
        <tbody>
<?php

			
	$sqlGetGroupsName="SELECT `itemsid`, `$cloumn` FROM `stockitems` WHERE $cloumn
	IS NOT NULL AND $cloumn != '$default' ORDER BY `$cloumn` ASC";
$queryGetGroupsName=mysqli_query($link,$sqlGetGroupsName)or die("ERROR :01-AU_AU_S");
while($resGetGroupsName = mysqli_fetch_assoc($queryGetGroupsName))
	
		{
			
			echo "
				<tr>
					<td>$resGetGroupsName[$cloumn]</td>
					<td>
						<button class='btn btn-link btn-xs editGroupName'  
						value='$resGetGroupsName[itemsid],$RefVal'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'>
						</i></button>
					</td>
				</tr>
			
			";
		}
		
?>
	</tbody>
    </table>

</div>
<script type="text/javascript">
	$(document).ready(function() {
       var table = $('.myTable').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'35vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "asc"]], 
 

});

	$(".editGroupName").click(function(){
		
		var ItemRowId = $(this).val();
		
			$.ajax({
				
			url:"dist/php/editGroupForm.php",
			type:"POST",
			data:{IRowIDandVal:ItemRowId},
			beforeSend: function(){
				$(".editGroupName").prop('disabled', true);	
			},
			success: function(EditGroupNameFrom){
				
				$(".editGroupName").prop('disabled', false);	
				$(".ShowEditForm").html("");
				$(".myModal").modal('toggle');
				$(".ShowEditForm").html(EditGroupNameFrom);
			}
				
				});
		
		
		return false;
		});
    });

</script>
