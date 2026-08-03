<?php 
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$itemRowId = $_POST['unitRowID'];

$sqlGetAsKitItems = "SELECT `descripcode`, `Quantity`, `assemplyRowId` FROM `kitscomponents` 
WHERE `id` = $itemRowId";
$queryGetAsKitItems=mysqli_query($link,$sqlGetAsKitItems)or die("ERROR :01-AU_AU_S");
$resGetAsKitItems = mysqli_fetch_assoc($queryGetAsKitItems);

		$sqlGetKitName = "SELECT `kitName` FROM `assemblykits` WHERE `id` 
		= $resGetAsKitItems[assemplyRowId]";
		$queryGetKitName=mysqli_query($link,$sqlGetKitName)or die("ERROR :02-AU_AU_S");
		$resGetKitName = mysqli_fetch_assoc($queryGetKitName);
		$KitName = $resGetKitName['kitName'];
		$KitRowId = $resGetAsKitItems['assemplyRowId'];
		
		$QTY = $resGetAsKitItems['Quantity'];
		$sqlGetItemName = "SELECT `descriptionname`, `imagename`, `partnumber` FROM `stockitems` 
		WHERE `description`	= $resGetAsKitItems[descripcode]";
		$queryGetItemName=mysqli_query($link,$sqlGetItemName)or die("ERROR :02-AU_AU_S");
		$resGetItemName = mysqli_fetch_assoc($queryGetItemName);
		$itemName = $resGetItemName['descriptionname'];
		$Image = $resGetItemName['imagename'];
		$partNo = $resGetItemName['partnumber'];

?>
<style>
.img-editItemAk {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 60px;
  height:40px;
  
}
.toast {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>
<div>
<input type="number" id="uintRowId" value="<?php echo $itemRowId;?>" style="display:none" />
<input type="text" id="kitname" value="<?php echo $KitName;?>" style="display:none" />
<input type="number" id="kitId" value="<?php echo $KitRowId;?>" style="display:none" />

	<table class="table">
    	<thead>
        	<th>Part No.</th>
            <th>Name</th>
            <th>QTY</th>
            <th>Image</th>
        </thead>
        <tbody>
          <tr>
          		<td>
					<input type='text' id='partNo' name='partNo' class='form-control' 
                     autocomplete='off' style='font-weight:bold' value='<?php echo $partNo;?>' 
                     list="AllPartNum"/>
                     <datalist id="AllPartNum">
                     </datalist>
				</td>
				<td>
					<input type='text' id='ItemName' name='ItemName' class='form-control' 
                     autocomplete='off' style='font-weight:bold' value='<?php echo $itemName;?>' 
                     list="showAllItems"/>
                     <datalist id="showAllItems">
                     </datalist>
				</td>
                 <td>
              		<input type="number" id="requierQTY" name="requierQTY" class="form-control" 
                    min="1" autocomplete="off" data-toggle="tooltip" data-placement="top" 
                    value="<?php echo $QTY;?>"/>
				</td>
                <td><img data-enlargeable alt='Item' src='dist/img/items/<?php echo $Image;?>' id="itemImage" 
                class='img-thumbnail img-editItemAk' style='cursor: zoom-in;'/>
                </td>
          </tr>
          <tr>
            <td align="center" colspan="4">
            	<button class="btn btn-success btn-sm" id="editUnitBTN">save</button>
            </td>  	
          </tr>
        </tbody>
    </table>

</div>

<script type="text/javascript">

$(document).ready(function() {
        

	$("#AllPartNum").load("dist/php/allPartNumDListAK.php");
	$("#showAllItems").load("dist/php/allItemsDListAK.php");		

	$('img[data-enlargeable]').addClass('img-enlargeable').click(function() {
  var src = $(this).attr('src');
  var modal;

  function removeModal() {
    modal.remove();
    $('body').off('keyup.modal-close');
  }
  modal = $('<div>').css({
    background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center',
    backgroundSize: 'contain',
    width: '100%',
    height: '100%',
    position: 'fixed',
    zIndex: '10000',
    top: '0',
    left: '0',
    cursor: 'zoom-out'
  }).click(function() {
    removeModal();
  }).appendTo('body');
  //handling ESC
  $('body').on('keyup.modal-close', function(e) {
    if (e.key === 'Escape') {
      removeModal();
    }
  });
});	


	$("#partNo").change(function(){
	
var selectedPartNum = $(this).val();
	
var PartNumChosenValideate = $('#AllPartNum [value="' + selectedPartNum + '"]');
if(selectedPartNum != "")
{
	
	if(PartNumChosenValideate.length <= 0)
	{
		alert('Please Choose Valid Part Number form the list');
		$("#partNo").css("border-color","red");
		setTimeout(function(){
		   $("#partNo").css("border-color","#EBEBEB");    						
		   $("#partNo").val('');	
		   $("#partNo").focus();							
		}, 1500);
	}
	
	else 
	{
		
		$.ajax({
				
			url:"dist/php/getPartNoDataExport.php",
			type:"POST",
			data:{sPartNum:selectedPartNum},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$("#partNo").prop("readonly", true);
				$("#itemImage").attr("src",'');
			},
			success: function(showPNData){
				
				 $("#partNo").prop("readonly", false);
				 
				 $("#ItemName").val(showPNData.ItemName);
				 $("#itemImage").attr("src","dist/img/items/"+showPNData.ItemImage);
				 
				 
			}
			
			
			});
		
	}
}
else if(selectedPartNum == "")
{
	 $("#ItemName").val("");
	 $("#itemImage").attr("src","");
	 $("#itemImage").attr("src","dist/img/items/defaultItem.jpg");
}
});

	$("#ItemName").change(function(){
	
var selectedDescrip = $(this).val();
	
var DescripChosenValideate = $('#showAllItems [value="' + selectedDescrip + '"]');
if(selectedDescrip != "")
{
	
	if(DescripChosenValideate.length <= 0)
	{
		alert('Please Choose Valid Item form the list');
		$("#ItemName").css("border-color","red");
		setTimeout(function(){
		   $("#ItemName").css("border-color","#EBEBEB");    						
		   $("#ItemName").val('');	
		   $("#ItemName").focus();							
		}, 1500);
	}
	
	else 
	{
		
		var data1 = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data1[$(el).data("value")] = $(el).val();
			});
		console.log(data1, $("#showAllItems option").val());
		var DescripforCheck = $('#showAllItems [value="' + selectedDescrip + '"]').data('value');
		
		$.ajax({
				
			url:"dist/php/getDescripDataExport.php",
			type:"POST",
			data:{sDescrip:DescripforCheck},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$("#ItemName").prop("readonly", true);
				$("#itemImage").attr("src",'');
			},
			success: function(showDescripData){
				
				$("#ItemName").prop("readonly", false);
				 
			$("#partNo").val(showDescripData.partNumGet);
			$("#itemImage").attr("src","dist/img/items/"+showDescripData.ItemImage);
			}
			
			
			});
		
	}
}
else if(selectedDescrip == "")
{
	 $("#partNo").val("");
	 $("#itemImage").attr("src","");	 
	 $("#itemImage").attr("src","dist/img/items/defaultItem.jpg");
}
});
		
		
		
	$("#editUnitBTN").click(function(){
			
		var data = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#showAllItems option").val());
	var DecripCode = $("#ItemName").val();
	var Description = $('#showAllItems [value="' + DecripCode + '"]').data('value');
	var partNumber = $("#partNo").val();
	var requierQTY = $("#requierQTY").val();
	var AsKName = $("#kitname").val();
	var AsKIdR = $("#kitId").val();
	var itemEditedRowId = $("#uintRowId").val();
		
			
		if(partNumber == "")
	{
		alert('Please Choose Valid Part Number form the list');
		$("#partNo").css("border-color","red");
		setTimeout(function(){
		   $("#partNo").css("border-color","#EBEBEB");    						
		   $("#partNo").val('');	
		   $("#partNo").focus();							
		}, 1500);
	}
	else if(DecripCode == "")
	{
		alert('Please Choose Valid Item form the list');
		$("#ItemName").css("border-color","red");
		setTimeout(function(){
		   $("#ItemName").css("border-color","#EBEBEB");    						
		   $("#ItemName").val('');	
		   $("#ItemName").focus();							
		}, 1500);
	}
	else if(requierQTY == "" || requierQTY == 0)
	{
		alert('Please add qty 0 value not accepted');
		$("#requierQTY").css("border-color","red");
		setTimeout(function(){
		   $("#requierQTY").css("border-color","#EBEBEB");    						
		   $("#requierQTY").val('');	
		   $("#requierQTY").focus();							
		}, 1500);
	}
	else
	{
		$.ajax({
				
				url:"dist/php/saveEditUnitInAsKit.php",
				type:"POST",
				data:{uintRID:itemEditedRowId, partNo:partNumber, descCode:Description, descName:DecripCode,AsKitQty:requierQTY, AsKitName:AsKName },
				beforeSend: function(){
				$("#editUnitBTN").prop('disabled', true);	
				},
				success: function(doneEditedItem){
					
					if(doneEditedItem == 1)
					{
						alert("Data Saved");
						$(".ShowEditForm").html("");
						$(".myModal").modal('toggle');
						$(".KitCompntsData").html("");
						setTimeout(function(){
						$(".KitCompntsData").load("dist/php/ShowAllKitCompnts.php", {AsKitRID:AsKIdR, AsKitNamePass:AsKName});
													
						}, 300);
						
					}
					
					else if(doneEditedItem == 2)
					{
						
						$(".ShowEditForm").html("");
						$(".myModal").modal('toggle');
						$('.toast').toast('show');			
						
					}
					
					else if(doneEditedItem == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						alert(doneEditedItem);
						$("#exportStockBTN").prop('disabled', false);
					}
					
				}
			
			});
	}
					
		return false;
	});
});
</script>