<?php 
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$pageRef = $_POST['IRowIDandVal'];
$itemRowId = substr($pageRef, 0,strrpos($pageRef, ','));
$itemRowId = trim($itemRowId);
$refVal = substr($pageRef, strpos($pageRef, ',') + 1);
$refVal = trim($refVal);

if($refVal == 1)
{
	$cloumn = "categoryname";
	$th = "Group Name";
}
else if($refVal == 2)
{
	$cloumn = "subcategoryname";
	$th = "Sub Group Name";
}

else if($refVal == 3)
{
	$cloumn = "subSCatgName";
	$th = "Sub Sub Group Name";
}




$sqlGetGroupsName="SELECT  `".$cloumn."` FROM `stockitems` WHERE `itemsid` = $itemRowId ";
$queryGetGroupsName=mysqli_query($link,$sqlGetGroupsName)or die("ERROR :01-AU_AU_S");
$resGetGroupsName = mysqli_fetch_assoc($queryGetGroupsName);




?>
<div>
<input type="number" id="RefVal" value="<?php echo $refVal;?>" style="display:none" />
<input type="number" id="STRowId" value="<?php echo $itemRowId;?>" style="display:none" />
	<table class="table">
    	<thead>
        	<td align="center"><b><?php echo $th; ?></b></td>
        </thead>
        <tbody>
        	<td>
            	<input type="text" id="GroupNameText" class="form-control" 
                value="<?php echo $resGetGroupsName[$cloumn];?>" />
            </td>
          <tr>
            <td align="center">
            	<button class="btn btn-success btn-sm" id="editGruopNameBTN">save</button>
            </td>  	
          </tr>
        </tbody>
    </table>	

</div>

<script type="text/javascript">

	$(document).ready(function() {
        
		$("#editGruopNameBTN").click(function(){
			
			var stRowId = $("#STRowId").val();
			var BTNRefVal = $("#RefVal").val();
			var editedText = $("#GroupNameText").val();
			
		if(editedText == "")
		{
			alert('missing field');
			$('#GroupNameText').css("border-color","red");
			setTimeout(function(){
           		$('#GroupNameText').css("border-color","#EBEBEB");
				$('#GroupNameText').focus();		
				}, 1500);
		}
		else
		{
			$.ajax({
				
					url:"dist/php/saveEditGroupingText.php",
					type:"POST",
					data:{StockIRowId:stRowId, BtnVal:BTNRefVal, tetxEdited:editedText},
					beforeSend: function(){
						
					$("#editGruopNameBTN").prop('disabled', true);
				},
				
			success: function(doneEditGroupingText){
				if(doneEditGroupingText == 0)
					{
						alert("Edited Name Is Already existing in Database.!");
						$("#editGruopNameBTN").prop('disabled', false);
					}
					else if(doneEditGroupingText == 1)
					{
						alert("Data Saved");
						$(".ShowEditForm").html("");
						$(".formToEdit").html("");
						$(".myModal").modal('toggle');
						setTimeout(function(){				
							$("#editGruopNameBTN").prop('disabled', false);
							
						$(".formToEdit").load("dist/php/editGroup.php", {Ref:BTNRefVal});
      					}, 500);
						
					}
					
					else if(doneEditGroupingText == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#editGruopNameBTN").prop('disabled', false);
						alert(doneEditGroupingText);
					}
			}
				
		});
	}
			
			return false;
			});
    });
</script>