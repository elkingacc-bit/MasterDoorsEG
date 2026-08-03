<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
$itemRowId = $_POST['itemRId'];
?>
<style>
.img-editItem {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 100px;
  
}
.toast {
  position: absolute;
  bottom: 0;
  right: 0;
}

</style>
<form id="editItemForm" enctype="multipart/form-data" method="post">
<input type='number' id='RowId' name='RowId' value='<?php echo $itemRowId;?>' style='display:none;'/>
<table class=" table" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Part No.</th>
          <th>Item Name</th>
          <th>Manufctuer</th>
         <!-- <th>Supplier</th>-->
          <th colspan="2">Image</th>
    </thead>
    <tbody >

<?php

	$sqlGetItem="SELECT `description`, `descriptionname`, `imagename`, `partnumber`,  `manufacturing`, 
	`technicalsheet` FROM `stockitems` WHERE `itemsid` = $itemRowId";
	$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01-AU_AU_S");
	$resGetItem = mysqli_fetch_assoc($queryGetItem);
	
	if($resGetItem['manufacturing'] == 0 || $resGetItem['manufacturing'] == "")
	{
		$manufactuer = "N/A";
		$manufactuerCode = 0;
	}
	else
	{
		$sqlGetManufact="SELECT `manufactuername`, `manufactuercode` FROM `allmanufactuers` 
		WHERE `manufactuercode` = $resGetItem[manufacturing] ";
		$queryGetManufact=mysqli_query($link,$sqlGetManufact)or die("ERROR :03-AU_AU_S");
		$resGetManufact= mysqli_fetch_assoc($queryGetManufact);
		
		$manufactuer = $resGetManufact['manufactuername'];
		$manufactuerCode = $resGetManufact['manufactuercode'];
	}
	
/*	$sqlGetItemData="SELECT  `supplier` FROM `warehouse` WHERE `description` = $resGetItem[description] ";
	$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
	
	
	if(mysqli_num_rows($queryGetItemData) == 0)
	{
		$supplier = "N/A";
		$supplierCode = 0;
	}
	else
	{
		$resGetItemData= mysqli_fetch_assoc($queryGetItemData);
		
		$sqlGetSupplier="SELECT `suppliercode`, `suppliername` FROM `allsuppliers` 
		WHERE `suppliercode` = $resGetItemData[supplier] ";
		$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :04-AU_AU_S");
		$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);
		
		$supplier = $resGetSupplier['suppliername'];
		$supplierCode = $resGetSupplier['suppliercode'];
		
		 <td>
              		<input type='text' id='allSupplier' name='allSupplier' class='form-control' 
                     autocomplete='off' list='allSupplierList'  value='$supplier'
					 data-value ='$supplierCode' style='font-weight:bold'/>
                     <datalist id='allSupplierList'>
                     </datalist>	
				</td>
	}
	*/
	$descrip = trim($resGetItem['technicalsheet']);
		echo "
				<td>
					<input type='text' id='partNo' name='partNo' class='form-control' 
                     autocomplete='off' style='font-weight:bold' value='$resGetItem[partnumber]'/>
				</td>
				<td>
					<input type='text' id='ItemName' name='ItemName' class='form-control' 
                     autocomplete='off' style='font-weight:bold' value='$resGetItem[descriptionname]'/>
				</td>
				<td>
              		<input type='text' id='allManufactuer' name='allManufactuer' class='form-control' 
                     autocomplete='off' list='allManufactuerList' value='$manufactuer'
					 data-value ='$manufactuerCode' style='font-weight:bold'/>
                     <datalist id='allManufactuerList'>
                     </datalist>	
				</td>
               
				
				<td><img data-enlargeable alt='Item Image' 
				src='dist/img/items/$resGetItem[imagename]'
				class='img-thumbnail img-editItem' style='cursor: zoom-in;'/></td>
                <td>
                    <input class='form-control-file' type='file' id='linePhoto' name='sourceFile'>
                </td>
              </tr>  
              <tr>
			    <td class='bg-warning' colspan='5'><b>Description</b></td>
              </tr>
              <tr>
			 	
              	<td colspan='5'>
                	<textarea type='text' id='itemDesc' name='itemDesc' class='form-control' 
                    autocomplete='off' style='font-weight:bold'>$descrip</textarea>
                </td>
              </tr>
			";

 ?>
 <tr>
 	<td colspan='5' align="center" >
 		<input type="submit" value="Save" class="btn btn-sm btn-success" id="saveEditItemBTN"/>
 	</td>	
 </tr>
    </tbody>  
 </table>
 </form>
 
 <div class="toast align-items-center text-white bg-dark border-0 " role="alert"  aria-live="polite" aria-atomic="true" data-delay="1">
  <div class="d-flex" >
    <div class="toast-body">
      
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">x</button>
  </div>
</div>

 
 <script type="text/javascript">
 	$(document).ready(function() {
   //$('.toast').toast('show');
	
		
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


$("#allManufactuerList").load("dist/php/allManufDDList.php");
$("#allSupplierList").load("dist/php/allSuppDDList.php");
//$(".allSelectedMachine").load("dist/php/checkLinkedMachines.php",{itemRowIdM:itemIdForMachine});


	$("#editItemForm").submit(function(){
		
		var partNo = $("#partNo").val();
		var itemName = $("#ItemName").val();
		itemName = itemName.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		var itemDescrip = $("#itemDesc").val();
		itemDescrip = itemDescrip.replace(/^\s+|\s+$|\s+(?=\s)/g, "");

		
	if(partNo == "" || null )
		{
			alert('missing field');
			$('#partNo').css("border-color","red");
			setTimeout(function(){
           		$('#partNo').css("border-color","#EBEBEB");
				$("#partNo").focus();				
				}, 1500);				
		}

	else if(itemName == "" || null )
		{
			alert('missing field');
			$('#ItemName').css("border-color","red");
			setTimeout(function(){
           		$('#ItemName').css("border-color","#EBEBEB");
				$('#ItemName').focus();				
				}, 1500);
								
			
		}
		
	else
		{
			
		$.ajax({
			url:"dist/php/saveEdittems.php",
			type:"POST",
			data: new FormData(this),
			contentType: false,
        	cache: false,
   			processData:false,
			beforeSend: function(){
				
				$("#saveEditItemBTN").prop('disabled', true);
				},
				
			success: function(saveEditItem){
				
				if(saveEditItem == 0)
					{
						alert("Item Name Is Already existing in Database.!");
						$("#saveEditItemBTN").prop('disabled', false);
					}
					else if(saveEditItem == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveEditItemBTN").prop('disabled', false);
      					}, 1500);
						$("#eidtItem").click();
					}
					else if(saveEditItem == 2)
					{
						alert("Error it is look like not allowed image estension!!");
						$('#ItemPhoto').css("background-color","red");
						$("#saveEditItemBTN").prop('disabled', false);
						setTimeout(function(){
							$('#ItemPhoto').css("background-color","#EBEBEB");
											
						}, 1500);
					}
					else if(saveEditItem == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveEditItemBTN").prop('disabled', false);
						alert(saveEditItem);
					}
			}
		});
	}
		return false;
		});
		
    });
 </script>
