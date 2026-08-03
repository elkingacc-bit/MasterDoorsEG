<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['assignHWGroupJobId'];
echo "
<table class='table table-sm'>


<caption>Assign Assembly Kit <button class='btn btn-sm btn-link backToAddHW' data-toggle='tooltip' data-placement='left' title='back to add new Hardware Group'><i class='fa fa-undo' aria-hidden='true' style='font-size:20px;color:#0275d8'></button></caption>";

	$sqlGetAllKits = "SELECT `id`,`kitName` FROM `assemblykits` WHERE `id` IN (SELECT `assemplyRowId` 
	FROM `kitscomponents`) ORDER BY `kitName` ASC";
	$queryGetAllKits = mysqli_query($link,$sqlGetAllKits)or die("ERROR :01-ANJ_GCN_S");
	if(mysqli_num_rows($queryGetAllKits) > 0)
	{
		echo "  <th class='col-sm-2'>HW Group</th>
				
				<td class='col-sm-2'>
					<input type='text' class='form-control allKits' list='allKitsList' />
					<datalist id='allKitsList'>
			";
		while($resGetAllKits = mysqli_fetch_assoc($queryGetAllKits))
		{
			echo "
				<option data-value='$resGetAllKits[id]' value='$resGetAllKits[kitName]'>
			";
		}
		
		echo "</datalist>
		</td>
		
		<td class='col-sm-2'>
		<button class='btn btn-sm btn-success' id='assignAsKitBtn'>Save</button>
		</td>";
	}
	else
	{
		echo "
			<th  class='col-md-4'>No any Assembly Kit Created yet.</th>
		"; 
	}
	
echo "</table>";	
?>

<script type="text/javascript">
	$(document).ready(function() {

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
       
	$("#assignAsKitBtn").click(function(){
		
		var assignedKitName = $(".allKits").val();
		var assignedJobRowId = $("#jRowId").val();
		var assignedItemRowId = $(".itemNewRowId").val();
		var assignedItemName = $("#itemName").val();
		var assignedItemType = $("#itemType").val();
		var AsKitfChosenValideate = $('#allKitsList [value="' + assignedKitName + '"]');
		
		
		var data = {};
			$("#allKitsList option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#allKitsList option").val());
		var AsKitRID = $('#allKitsList [value="' + assignedKitName + '"]').data('value');
		
		
		//alert(assignedItemRowId);
	if(AsKitfChosenValideate.length <= 0)
	{
		alert('Please Choose Valid Assembly Kit form the list');
		$(".allKits").css("border-color","red");
		setTimeout(function(){
		   $(".allKits").css("border-color","#EBEBEB");    						
		   $(".allKits").val('');	
		   $(".allKits").focus();							
		}, 1500);
	}
	else
	{
		
		$.ajax({
			
				url:"dist/php/saveAddAsKitToOfferItem.php",
				type:"POST",
				data:{JobRowIdRef:assignedJobRowId,AsKitID:AsKitRID,AsgnItemRID:assignedItemRowId, AsKitName:assignedKitName},
				beforeSend: function(){
				$("#assignAsKitBtn").prop('disabled', true);
					
				},
				success: function(doneAssignAsKit){
				
					if(doneAssignAsKit == 1)
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
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:assignedItemRowId});	
					
					}
					else
					{
						alert(doneAssignAsKit);
						$("#assignAsKitBtn").prop('disabled', false);
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