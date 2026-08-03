<?php 
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$KitRowId = $_POST['IRowID'];

$sqlGetKitName="SELECT  `kitName` FROM `assemblykits` WHERE `id` = $KitRowId ";
$queryGetKitName=mysqli_query($link,$sqlGetKitName)or die("ERROR :01-AU_AU_S");
$resGetKitName = mysqli_fetch_assoc($queryGetKitName);




?>
<div>
<input type="number" id="kitRowId" value="<?php echo $KitRowId;?>" style="display:none" />
	<table class="table">
    	<thead>
        	<td align="center"><b>Assembly Kit Name</b></td>
        </thead>
        <tbody>
        	<td>
            	<input type="text" id="KitNameText" class="form-control" 
                value="<?php echo $resGetKitName['kitName'];?>" />
            </td>
          <tr>
            <td align="center">
            	<button class="btn btn-success btn-sm" id="editKitNameBTN">save</button>
            </td>  	
          </tr>
        </tbody>
    </table>	

</div>

<script type="text/javascript">

	$(document).ready(function() {
        
		$("#editKitNameBTN").click(function(){
			
			var KitRowId = $("#kitRowId").val();
			var editedText = $("#KitNameText").val();
			
		if(editedText == "")
		{
			alert('missing field');
			$('#KitNameText').css("border-color","red");
			setTimeout(function(){
           		$('#KitNameText').css("border-color","#EBEBEB");
				$('#KitNameText').focus();		
				}, 1500);
		}
		else
		{
			$.ajax({
				
					url:"dist/php/saveEditAsKitText.php",
					type:"POST",
					data:{AsKitIRowId:KitRowId, textEdited:editedText},
					beforeSend: function(){
						
					$("#editKitNameBTN").prop('disabled', true);
				},
				
			success: function(doneEditAsKitText){
				if(doneEditAsKitText == 0)
					{
						alert("Edited Name Is Already existing in Database.!");
						$("#editKitNameBTN").prop('disabled', false);
					}
					else if(doneEditAsKitText == 1)
					{
						alert("Data Saved");
						$(".ShowEditForm").html("");
						$(".dataToEdit").html("");
						$(".myModal").modal('toggle');
						setTimeout(function(){				
							$("#editKitNameBTN").prop('disabled', false);
							
						$(".dataToEdit").load("dist/php/allAsKitNameForEdit.php");
      					}, 500);
						
					}
					
					else if(doneEditAsKitText == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#editKitNameBTN").prop('disabled', false);
						alert(doneEditAsKitText);
					}
			}
				
		});
	}
			
			return false;
			});
    });
</script>