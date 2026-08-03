<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 $staffRowId = $_POST['staffRId'];
?>
<style>

</style>
<input type='text' id='RowId' name='RowId' value='<?php echo $staffRowId;?>' style='display:none;'/>
<table class=" table" cellspacing="0" width="99%">
    <thead class="bg-warning">
         	<th>Name</th>
            <th>Position</th>
            <th>Day Value</th>
    </thead>
    <tbody >

<?php

	$sqlGetStaff="SELECT `staffname`, `staffposition`, `dayVal` FROM `allstaff`WHERE `id` = $staffRowId";
	$queryGetStaff=mysqli_query($link,$sqlGetStaff)or die("ERROR :01-AU_AU_S");
	$resGetStaff= mysqli_fetch_assoc($queryGetStaff);
	
	
		echo "
				
               
                <td>
              		<input type='text' id='staffName' name='staffName' class='form-control' 
                    placeholder='Manufactier Name' autocomplete='off' value='$resGetStaff[staffname]'/>
				</td>
				<td>
              		<input type='text' id='choosePosi' name='choosePosi' class='form-control' 
                     autocomplete='off' list='AllPosiList' value='$resGetStaff[staffposition]'>
                     <datalist id='AllPosiList'></datalist>	
				</td>
				<td>
              		<input type='number' id='DayVal' name='DayVal' class='form-control' 
                     autocomplete='off' value='$resGetStaff[dayVal]'>
                     
				</td>
				
				
			";
			
 ?>
 <tr>
   	<td colspan="3" align="center">
        	<button type="button" id="editStaffButton" class="btn btn-success">Save</button>
    </td>         
 </tr>
    </tbody>  
 </table> 
 <script type="text/javascript">
 	$(document).ready(function() {
   		 
$("#AllPosiList").load("dist/php/allPosiDDList.php");
$("#AllTeamsList").load("dist/php/allTeamsDDList.php");
	
	$("#editStaffButton").click(function(){
		
		var RowId = $("#RowId").val();
		var staffName = $("#staffName").val();
		staffName = staffName.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		var Position = $("#choosePosi").val();
		var DVal = $("#DayVal").val();
		
	
	if(staffName == "" || null )
		{
			alert('missing field');
			$('#staffName').css("border-color","red");
			setTimeout(function(){
           		$('#staffName').css("border-color","#EBEBEB");
				$('#staffName').focus();				
				}, 1500);
		}
	 
	else if(Position == "" || null )
		{
			alert('missing field');
			$('#choosePosi').css("border-color","red");
			setTimeout(function(){
           		$('#choosePosi').css("border-color","#EBEBEB");
				$("#choosePosi").focus();				
				}, 1500);
								
			
		}
	
	else if(DVal == "" || null )
		{
			alert('missing field');
			$('#DayVal').css("border-color","red");
			setTimeout(function(){
           		$('#DayVal').css("border-color","#EBEBEB");
				$("#DayVal").focus();				
				}, 1500);
								
			
		}	
	else
		{
			
		$.ajax({
			url:"dist/php/saveEditStaff.php",
			type:"POST",
			data: {sRowId:RowId,StfName:staffName, StfPosition:Position, dayAmount:DVal},
			beforeSend: function(){
				
				$("#editStaffButton").prop('disabled', true);
				},
				
			success: function(doneEditStaff){
				if(doneEditStaff == 0)
					{
						alert("Staff Name Is Already existing in Database.!");
						$("#editStaffButton").prop('disabled', false);
						$('#staffName').css("border-color","red");
						setTimeout(function(){
							$('#staffName').css("border-color","#EBEBEB");
							$('#staffName').focus();				
							}, 1500);
					}
					else if(doneEditStaff == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#editStaffButton").prop('disabled', false);
      					}, 1500);
						$("#editStaff").click();
					}
					
					else if(doneEditStaff == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#editStaffButton").prop('disabled', false);
						alert(doneEditStaff);
					}
			}
		});
	}
		return false;
		});		
    });
 </script>
