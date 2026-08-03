<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['assignHWGroupJobId'];
echo "
<table class='table table-sm'>


<caption>Assign Hardware Group <button class='btn btn-sm btn-link backToAddHW' data-toggle='tooltip' data-placement='left' title='back to add new Hardware Group'><i class='fa fa-undo' aria-hidden='true' style='font-size:20px;color:#0275d8'></button></caption>";
 
	$sqlGetAllGroups = "SELECT `offerItemRef` FROM `offerproperties` WHERE `jobidref` = $jobRowId GROUP BY 
	`offerItemRef`";
	$queryGetAllGroups = mysqli_query($link,$sqlGetAllGroups)or die("ERROR :01-ANJ_GCN_S");
	if(mysqli_num_rows($queryGetAllGroups) > 0)
	{
		echo "  <th class='col-sm-2'>HW Group</th>
				
				<td class='col-sm-2'>
					<input type='text' class='form-control allGroup' list='allHWGroupList' />
					<datalist id='allHWGroupList'>
			";
		while($resGetAllGroups = mysqli_fetch_assoc($queryGetAllGroups))
		{
			echo "
				<option value='$resGetAllGroups[offerItemRef]'>
			";
		}
		
		echo "</datalist>
		</td>
		
		<td class='col-sm-2'>
		<button class='btn btn-sm btn-success' id='assignHWGroupRef'>Save</button>
		</td>";
	}
	else
	{
		echo "
			<th  class='col-md-4'>No any Hardware group added Please retuen to add New Group</th>
		"; 
	}
	
echo "</table>";	
?>

<script type="text/javascript">
	$(document).ready(function() {

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
       
	$("#assignHWGroupRef").click(function(){
		
		var assignedRef = $(".allGroup").val();
		var assignedJobRowId = $("#jRowId").val();
		var assignedItemRowId = $(".itemNewRowId").val();
		var assignedItemName = $("#itemName").val();
		var assignedItemType = $("#itemType").val();
		var assignedRefChosenValideate = $('#allHWGroupList [value="' + assignedRef + '"]');
		//alert(assignedItemRowId);
	if(assignedRefChosenValideate.length <= 0)
	{
		alert('Please Choose Valid Group Ref form the list');
		$(".allGroup").css("border-color","red");
		setTimeout(function(){
		   $(".allGroup").css("border-color","#EBEBEB");    						
		   $(".allGroup").val('');	
		   $(".allGroup").focus();							
		}, 1500);
	}
	else
	{
		
		$.ajax({
			
				url:"dist/php/saveAddAssignedRefToItem.php",
				type:"POST",
				data:{JobRowIdRef:assignedJobRowId,AsgnRef:assignedRef,AsgnItemRID:assignedItemRowId},
				beforeSend: function(){
				$("#assignHWGroupRef").prop('disabled', true);
					
				},
				success: function(doneAssignGroupRef){
				
					if(doneAssignGroupRef == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
								$("#addItemHWBtn").prop('disabled', false);
								$(".addMoreItemToOfferTR").show();
								$("#addMoreItemToOfferBtn").show();
							}, 1500);
							$(".sndForm").val("");
							$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:assignedJobRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
							 
							$(".addHWRefCalss").hide();
							$(".addHWRefCalss").html('');
							$(".selectedHW").show("");
							$(".HWadded").html('');
							$(".HWadded").show();
							$(".AssignAsKit").hide();
							$(".AssignHWRef").hide();
							
							$(".HWadded").load("dist/php/showAllAddHWtoItem.php",{tableJobId:assignedJobRowId,tableItemName:assignedItemName,tableItemType:assignedItemType, tableItemRowId:assignedItemRowId});
							
							$(".oldAddItems").html("");
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:assignedJobRowId});	
					 
					}
					else
					{
						alert(doneAssignGroupRef);
						$("#assignHWGroupRef").prop('disabled', false);
					}
					
				}
			});
	}
		return false; 
		});	
$(".backToAddHW").click(function(){
	
	$(".selectedHW").show();
	$(".addHWRefCalss").hide();
	
	return false; 
	});
	    
});

</script>