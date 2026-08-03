<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
	  
 $orderRowId = $_POST['orderRowId'];
 $orderNum = $_POST['orderNum'];
 $offerProperRowId = $_POST['OPRID'];
 $Partnum = $_POST['PartNo'];
 $ItemName = $_POST['ItemName'];
 $orderQTY = $_POST['OQTY'];
 $Export = $_POST['Expt'];
 
 $reminQTY = ($orderQTY - $Export);
 
 
 	$sqlGetItemCode="SELECT `descripcode` FROM `offerproperties` WHERE `offproId` = $offerProperRowId";
	$queryGetItemCode=mysqli_query($link,$sqlGetItemCode)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetItemCode= mysqli_fetch_assoc($queryGetItemCode);
	$descCode = $resGetItemCode['descripcode'];
	
	$sqlGetAvalibleStock="SELECT `warehouse` FROM `lookupstock` WHERE `descriptioncode` = $descCode";
	$queryGetAvalibleStock=mysqli_query($link,$sqlGetAvalibleStock)or die("ERROR :01-AM_AMDL_S"
	.mysqli_error($link));
	$resGetAvalibleStock= mysqli_fetch_assoc($queryGetAvalibleStock);
 
		 
?>
 <div class="modal-header">
      <p class="modal-title">
      <!--<button class="btn btn-link btn-sm" id="replaceItemBTN" data-toggle='tooltip' data-placement='left'
       title='Change Item'>
      <li class="fas fa-exchange-alt" aria-hidden="true" style='font-size:26px;color:#d9534f'></li>
      </button>-->
    <label class="switch">
      <input type="checkbox" id="replaceItemBTN" data-toggle='tooltip' data-placement='left'
       title='Change Item'>
      <span class="slider"></span>
    </label>
      <center>
      <span class="itemNameTitel">Add Export QTY for</span> <span style="color:blue;">
      <b><?php echo $Partnum ." <br> ". 
	   $ItemName;?></b></span><br><span class="avlStock"> Avalible Stock = <span style="color:blue;"><b>
		<?php echo  $resGetAvalibleStock['warehouse'];?></b></span></span>
        </center>
        </p>
      
      </div>
       <div class="modal-body ">
       
       <div class="ExptQTYDiv" style=" width:100%">
       		
            <table class="table table-sm expQtyTable">
            <thead class="bg-info">
            	<th>Export QTY</th>
            </thead>
            <tbody>    
                <tr>
                	<td>
                    <input type="number" class="form-control newExptQTY" min="1" id="newExptQTY" 
                    max="<?php echo $reminQTY;?>" min="1" />
                    </td>
                </tr>
                <tr>
                	<td align="center">
                    <button class="btn btn-success btn-sm" id="saveExptItemQTYBTN">Save</button>
                    </td>
                </tr>
             </tbody>   
            </table>
       	
       </div>
       
 
 <input type="text" value="<?php echo $orderRowId;?>" style="display:none" id="PORowId"/>
  <input type="text" value="<?php echo $ItemName;?>" style="display:none" id="ItemName"/>
  <input type="text" value="<?php echo $Partnum;?>" style="display:none" id="PartNum"/>
  <input type="text" value="<?php echo $offerProperRowId;?>" style="display:none" id="HWRowId"/>
  <input type="text" value="<?php echo $orderQTY;?>" style="display:none" id="OfferQTY"/>
  <input type="text" value="<?php echo $Export;?>" style="display:none" id="ExptQTY"/>
  <input type="text" value="<?php echo $orderNum;?>" style="display:none" id="orderNo"/>
  </div>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  
	
		
$("#saveExptItemQTYBTN").click(function(){

	
	var exptHWRID = $("#HWRowId").val();
	var exptItemName = $("#ItemName").val();
	var exptPartNo = $("#PartNum").val();
	var exptOrderRID = $("#PORowId").val();
	var exptNewQTY = $("#newExptQTY").val();
	var exportedQTY = $("#ExptQTY").val();
	var OfferQTY = $("#OfferQTY").val();
	var orderNum = $("#orderNo").val();
	
	if(exptNewQTY == "" || exptNewQTY == 0)
	{
		alert('missing faild');
		$("#newExptQTY").css("border-color","red");
		setTimeout(function(){
		   $("#newExptQTY").css("border-color","#EBEBEB");    						
		   $("#newExptQTY").focus();							
		}, 1500);
	}
	
	else
	{
		$.ajax({
				
				url:"dist/php/saveExportStock.php",
				type:"POST",
				data:{expPartNo:exptPartNo,expItemName:exptItemName,expHWRID:exptHWRID,ExpQty:exptNewQTY,expPoRowId:exptOrderRID,PONUM:orderNum},
				beforeSend: function(){
						$("#saveExptItemQTYBTN").prop('disabled', true);
						$("#allValidPO").prop("disabled", true);
					},
					success: function(doneExportStock)
					{
						
						 if(doneExportStock == 0)
						{
							alert("No Avlibile Stock to Export");
							$("#saveExptItemQTYBTN").prop('disabled', false);
							$(".allValidPO").prop("disabled", false);
							$("#newExptQTY").css("border-color","red");
							setTimeout(function(){
							   $("#newExptQTY").css("border-color","#EBEBEB");    						
							   $("#newExptQTY").focus();							
							}, 1500);																
						}
						 else if(doneExportStock == 1)
						{
							alert("Data Saved");
							$("#saveExptItemQTYBTN").prop('disabled', false);
								//$("#AllCustPo").prop("disabled", false);
								
								$(".finishBTN").show();
								$(".allValidPO").prop("disabled", true);
							setTimeout(function(){				
								
								$(".expItemsTable").html('');
		$(".expItemsTable").load("dist/php/allPOStockExp.php",{PoNumGet:orderNum, PoIdGet:exptOrderRID});
								$('.ShowHWDataHist').html('');
								$(".myModal").modal('toggle');
																
							}, 1000);
																	
						}
						else if(doneExportStock == 2)
						{
							var confChangeItem = confirm("Continue with Replace Item?");
							if(confChangeItem === true)
							{
								$(".ExptQTYDiv").html('');
								//$(".ExptQTYDiv").html('test');
								$(".modal-dialog").removeClass("modal-sm");
								$(".modal-dialog").addClass("modal-lg");
								$(".itemNameTitel").html('');
								$(".itemNameTitel").html('Replace Item: ');
								$(".avlStock").html('');
								$("#replaceItemBTN").prop('disabled', true); 
								$(".ExptQTYDiv").load("dist/php/exptiItemFromG.php",{refHWRowID:exptHWRID,refPoRowID:exptOrderRID,refItemName:exptItemName});
							}
							else
							{
								alert("Data Saved");
							$("#saveExptItemQTYBTN").prop('disabled', false);
								//$("#AllCustPo").prop("disabled", false);
								
								$(".finishBTN").show();
								$(".allValidPO").prop("disabled", true);
							
								setTimeout(function(){				
									
									$(".expItemsTable").html('');
		$(".expItemsTable").load("dist/php/allPOStockExp.php",{PoNumGet:orderNum, PoIdGet:exptOrderRID});
									$('.ShowHWDataHist').html('');
									$(".myModal").modal('toggle');
																	
								}, 1000);
							}
							
						}
						else
						{
							alert(doneExportStock);
							$("#saveExptItemQTYBTN").prop('disabled', false);
							$(".allValidPO").prop("disabled", false);
						}
					}
			
			});

	}
	
	return false; 
	});
	
	$("#replaceItemBTN").change(function(){
		
		$(this).prop('disabled', true);
		
		var refHWRowID2 = $("#HWRowId").val();
		var refItemName2 = $("#ItemName").val();
		var refPoRowID2 = $("#PORowId").val();
		
		//$(".expQtyTable").hide();
		$(".ExptQTYDiv").html('');
		//$(".ExptQTYDiv").html('test');
		$(".modal-dialog").removeClass("modal-sm");
		$(".modal-dialog").addClass("modal-lg");
		$(".itemNameTitel").html('');
		$(".itemNameTitel").html('Replace Item: ');
		$(".avlStock").html('');
		
		$(".ExptQTYDiv").load("dist/php/exptiItemFromG.php",{refHWRowID:refHWRowID2,refPoRowID:refPoRowID2,refItemName:refItemName2});
		
		 
		//alert("test");
		return false;
		});

// solve chrome error for hiden-area focas;
		
	document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener('hide.bs.modal', function (event) {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});
// end functions 
	
});
 
 </script>
 
 <?php
 }
 ?>
 