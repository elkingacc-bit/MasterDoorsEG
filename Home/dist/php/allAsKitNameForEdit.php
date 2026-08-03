<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
?>


<div>
	<table class="myTable table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    	<thead class="bg-warning">
       		 <th class='col-sm-4'>Assembly Kit Name</th>
        	 <th class='col-sm-1'>Edit</th>
             <th class='col-sm-1'>Remove</th>
        </thead>
        <tbody>
<?php

			
	$sqlGetAsKitName="SELECT `id`, `kitName` FROM `assemblykits` ORDER BY `kitName` ASC";
$queryGetAsKitName=mysqli_query($link,$sqlGetAsKitName)or die("ERROR :01-AU_AU_S");
while($resGetAsKitsName = mysqli_fetch_assoc($queryGetAsKitName))
	
		{
			
			echo "
				<tr>
					<td>$resGetAsKitsName[kitName]</td>
					<td>
						<button class='btn btn-link btn-xs editAsKitVal'  
						value='$resGetAsKitsName[id]'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
						</button>
					</td>
					<td>
						<button class='btn btn-link btn-xs removeAsKitVal'  
						value='$resGetAsKitsName[id]'>
						<i class='fas fa-trash-alt' aria-hidden='true' style='font-size:20px;color:#d9534f'></i>
						</button>
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

	$(".editAsKitVal").click(function(){
		
		var AsKitRowId = $(this).val();
		
			$.ajax({
				
			url:"dist/php/editAsKitForm.php",
			type:"POST",
			data:{IRowID:AsKitRowId},
			beforeSend: function(){
				$(".editAsKitVal").prop('disabled', true);	
			},
			success: function(EditGroupNameFrom){
				
				$(".editAsKitVal").prop('disabled', false);	
				$(".ShowEditForm").html("");
				$(".myModal").modal('toggle');
				$(".ShowEditForm").html(EditGroupNameFrom);
			}
				
				});
		
		
		return false;
		});
		
		$(".removeAsKitVal").click(function(){
		
		var AsKitRowIdRmv = $(this).val();
		var closetTr = $(this).closest('tr');
		 	closetTr.addClass('text-primary');
		var deleteConfirm = confirm("Please Confirm Delete Assembly Lit Name and Components?");
		 
		 if(deleteConfirm === true)
		 {	
			$.ajax({
				
			url:"dist/php/RemoveAsKitAll.php",
			type:"POST",
			data:{RmvAsKitRowID:AsKitRowIdRmv},
			beforeSend: function(){
				$(".removeAsKitVal").prop('disabled', true);	
			},
			success: function(rmoveAsKitName){
			
				if(rmoveAsKitName == 1)
				{
					alert("Data Saved");
					$(".dataToEdit").html('');
					closetTr.removeClass('text-primary');	
					setTimeout(function(){
						$(".dataToEdit").load("dist/php/allAsKitNameForEdit.php");			
					}, 500);
					
				}
				else
				{
					alert(rmoveAsKitName);
					setTimeout(function(){
						closetTr.removeClass('text-primary');				
					}, 1500);
					$(".removeAsKitVal").prop('disabled', false);	
				}
				
			}
				
			});
		 }
		 else
		 {
			setTimeout(function(){
				closetTr.removeClass('text-primary');				
			}, 1500);
			$(".removeAsKitVal").prop('disabled', false);	 
		 }
		
		
		return false;
		});
  
    });
	
	
	
	

</script>
