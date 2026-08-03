<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
$AsKitRowId = $_POST['RefAsKitId'];
?>

<style>
.img-OldAk {
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
<input type="number" id="KitRowIdOld" style="display:none" value="<?php echo $AsKitRowId;?>" />
<?php
	
$sqlGetAsKitItems = "SELECT `id`, `descripcode`, `Quantity` FROM `kitscomponents` 
WHERE `assemplyRowId` = $AsKitRowId";
$queryGetAsKitItems=mysqli_query($link,$sqlGetAsKitItems)or die("ERROR :01-AU_AU_S");
  if(mysqli_num_rows($queryGetAsKitItems) > 0)
  {	
  	echo "<div >
      		<table class='table table-responsive-sm' style='width:50%'>
           		<thead class='bg-warning'>
        			<th>Part No.</th>
					<th>Name</th>
					<th>QTY</th>
					<th>Image</th>
					<th></th>
			   </thead>
			   <tbody>";
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
                <td><img data-enlargeable alt='Item' src='dist/img/items/$Image' id='itemImage' 
                class='img-thumbnail img-OldAk' style='cursor: zoom-in;'/>
                </td>
				<td>
					<button class='btn btn-link btn-xs deleteItemsAsKit' value='$resGetAsKitItems[id]'
					data-toggle='tooltip' data-placement='top' title='Remove'>
					<i class='fas fa-trash-alt' aria-hidden='true' style='font-size:20px;color:#d9534f'>
					</i></button>
				</td>
         	   </tr>
	  
	  ";
	}
	echo "
		  </tbody> 
        </table>
      </div>  
	";
  }
?> 
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

		
	$(".deleteItemsAsKit").click(function(){
		
		var itemRowId = $(this).val();
		var KitRowId = $("#KitRowIdOld").val();
		var confirmDelete = confirm("Confirm delete this item form Assembly-Kit components");
		
		if(confirmDelete === true)
		{
			$.ajax({
				
					url:"dist/php/deleteFromAsKitCompnts.php",
					type:"POST",
					data:{ItemRId:itemRowId, KitRowIdDelete:KitRowId},
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
								$(".AddedItems").load("dist/php/AsKitOldAddItems.php",{RefAsKitId:KitRowId});
								}, 1500);
							
						
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
});
</script>	    
