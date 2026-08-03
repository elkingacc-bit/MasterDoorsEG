<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
$AsKitRowId = $_POST['AsKitRID'];
$AsKitName = $_POST['AsKitNamePass'];
?>

<style>
.img-edittable {
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
<input type="number" id="assemblyKitRowId" style="display:none" value="<?php echo $AsKitRowId;?>" />
<input type="text" id="assemblyKitname" style="display:none" value="<?php echo $AsKitName;?>" />

<div class="addToKit" align="right" style="margin-top:-8%; padding-right:4%">
<button class="btn btn-link btn-xs" id="addItemToAsKit" value="<?php echo $AsKitRowId;?>" 
data-toggle='tooltip' data-placement='top' title='Add Item to assembly Kit'>
<i class='fas fa-pen' style='font-size:21px;color:red'></i>
</button>
</div>
<br>
<table class="myTable table table-striped table-bordered" cellspacing="0" width="99%">
           		<thead class="bg-warning">
        			<th>Part No.</th>
					<th>Name</th>
					<th>QTY</th>
					<th>Image</th>
					<th style="width: 4%"></th>
                    <th style="width: 4%"></th>
			   </thead>
			   <tbody>

<?php
	
$sqlGetAsKitItems = "SELECT `id`, `descripcode`, `Quantity` FROM `kitscomponents` 
WHERE `assemplyRowId` = $AsKitRowId";
$queryGetAsKitItems=mysqli_query($link,$sqlGetAsKitItems)or die("ERROR :01-AU_AU_S");
  if(mysqli_num_rows($queryGetAsKitItems) > 0)
  {	
 
	while($resGetAsKitItems = mysqli_fetch_assoc($queryGetAsKitItems))
	{
		$QTY = $resGetAsKitItems['Quantity'];
		$sqlGetItemName = "SELECT `descriptionname`, `imagename`, `partnumber` FROM `stockitems` 
		WHERE `description` = $resGetAsKitItems[descripcode]";
		$queryGetItemName=mysqli_query($link,$sqlGetItemName)or die("ERROR :02-AU_AU_S");
		$resGetItemName = mysqli_fetch_assoc($queryGetItemName);
		$itemName = $resGetItemName['descriptionname'];
		$Image = $resGetItemName['imagename'];
		
		$partNo = $resGetItemName['partnumber'];
		
		
       echo " 
	   		   <tr>
           		<td>$partNo</td>
				<td>$itemName</td>
                 <td>$QTY</td>
                <td><img data-enlargeable alt='Item' src='dist/img/items/$Image' 
                class='img-thumbnail img-edittable' style='cursor: zoom-in;'/>
                </td>
				<td>
					<button class='btn btn-link btn-xs deleteItemsAsKit' value='$resGetAsKitItems[id]'
					data-toggle='tooltip' data-placement='top' title='Remove'>
					<i class='fas fa-trash-alt' aria-hidden='true' style='font-size:20px;color:#d9534f'>
					</i></button>
					</td>
					<td>
					<button class='btn btn-link btn-xs editItemsAsKit' value='$resGetAsKitItems[id]'
					data-toggle='tooltip' data-placement='top' title='edit'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'>
					</i></button>
				</td>
         	   </tr>
	  
	  ";
	}
  }
?>
	</tbody> 
</table>
    
 
<script type="text/javascript">

$(document).ready(function() {
        
	$(function () {
		  $('[data-toggle="tooltip"]').tooltip();
	});

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

 	var table = $('.myTable').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'35vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "asc"]], 
 

});
		
	$(".deleteItemsAsKit").click(function(){
		
		var itemRowId = $(this).val();
		var deleteKitRowId = $("#assemblyKitRowId").val();
		var deleteKitName = $("#assemblyKitname").val();
		var confirmDelete = confirm("Confirm delete this item form Assembly-Kit components");
		
		if(confirmDelete === true)
		{
			$.ajax({
				
					url:"dist/php/deleteFromAsKitCompnts.php",
					type:"POST",
					data:{ItemRId:itemRowId, KitRowIdDelete:deleteKitRowId},
					beforeSend: function(){
						
						$(".deleteItemsAsKit").prop('disabled', true);
						
					},
					success: function(doneDeletedItem){
						if(doneDeletedItem == 1)
						{
							alert("Data Saved");
							$(".AddedItems").html("");
							setTimeout(function(){				
								$(".deleteItemsAsKit").prop('disabled', false);
								$(".KitCompntsData").html("");
								$(".KitCompntsData").load("dist/php/ShowAllKitCompnts.php", {AsKitRID:deleteKitRowId, AsKitNamePass:deleteKitName});
								}, 300);
							
						
						}
					
					else if(doneDeletedItem == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$(".deleteItemsAsKit").prop('disabled', false);
						alert(doneDeletedItem);
					}
			}
				});
		}
		return false; 
	});	

	$(".editItemsAsKit").click(function(){
		
		var AsKitItemRowId = $(this).val();
		
			$.ajax({
				
			url:"dist/php/editAsKitItemForm.php",
			type:"POST",
			data:{unitRowID:AsKitItemRowId},
			beforeSend: function(){
				$(".editItemsAsKit").prop('disabled', true);	
			},
			success: function(showEditUintFrom){
				
				$(".editItemsAsKit").prop('disabled', false);	
				$(".ShowEditForm").html("");
				$(".myModal").modal('toggle');
				$(".ShowEditForm").html(showEditUintFrom);
			}
				
				});
		
		
		return false;
	});
	
	
	$("#addItemToAsKit").click(function(){
		
		var addToAsKitItemRowId = $(this).val();
		
			$.ajax({
				
			url:"dist/php/AddItemToAsKitForm.php",
			type:"POST",
			data:{unitAddRowID:addToAsKitItemRowId},
			beforeSend: function(){
				$("#addItemToAsKit").prop('disabled', true);	
			},
			success: function(showAddUintFrom){
				
				$("#addItemToAsKit").prop('disabled', false);	
				$(".ShowEditForm").html("");
				$(".myModal").modal('toggle');
				$(".ShowEditForm").html(showAddUintFrom);
			}
				
				});
		
		
		return false;
	});
});
</script>