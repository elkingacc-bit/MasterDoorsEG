<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

//
$offerPorpRIDRef=$_POST['refHWRowID'];
$PORID2 = $_POST['refPoRowID']; 
$oldItemName = $_POST['refItemName'];  

$sqlGetDescCode = "SELECT `descripcode`, `ioidref`, `jobidref` FROM `offerproperties` 
WHERE `offproId` = $offerPorpRIDRef";
$queryGetDescCode = mysqli_query($link,$sqlGetDescCode)or die("ERROR :02-AM_AMDL_S".mysqli_error($link));
$resGetDescCode = mysqli_fetch_assoc($queryGetDescCode);

$ItemCode=$resGetDescCode['descripcode'];
$ItemRID = $resGetDescCode['ioidref'];
$jobRID = $resGetDescCode['jobidref'];


$sqlGetExptQTY="SELECT SUM(`export`) AS ExptQTY FROM `warehouse`
	WHERE `description` = $ItemCode AND `poIdRef` = $PORID2";
	$queryGetExptQTY=mysqli_query($link,$sqlGetExptQTY)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetExptQTY= mysqli_fetch_assoc($queryGetExptQTY);
	
	if($resGetExptQTY['ExptQTY'] == "")
	{
		$exported = 0;
	}
	else
	{
		$exported = $resGetExptQTY['ExptQTY'];
	}
	
	$sqlGetRExptQTY2="SELECT SUM(`exptqty`) AS replaceExptQTY FROM `replacedexpt`
	WHERE `porefrowid` = $PORID2 AND `offereditemcode` = $ItemCode";
	$queryGetRExptQTY2=mysqli_query($link,$sqlGetRExptQTY2)or 
	die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetRExptQTY2= mysqli_fetch_assoc($queryGetRExptQTY2);
	
	if($resGetRExptQTY2['replaceExptQTY'] == "")
	{
		$exportedRStock = 0;
	}
	else
	{
		$exportedRStock = $resGetRExptQTY2['replaceExptQTY'];
	}
	
	$allExported= ($exported + $exportedRStock);
		
	$sqlGetAllQTY="SELECT SUM(`descripquantity`) AS QTY FROM `offerproperties`
	WHERE `descripcode` = $ItemCode AND `jobidref` = $jobRID";
	$queryGetAllQTY=mysqli_query($link,$sqlGetAllQTY)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetAllQTY= mysqli_fetch_assoc($queryGetAllQTY);

	$hwOfferQTY = $resGetAllQTY['QTY'];
	
	$AllreminQTY = ($hwOfferQTY - $allExported);
	$catgLenght=substr($ItemCode,0,4);
	
?>

 <div class="replaceExptQTYDiv" style=" width:100%">
       		
            <table class="table table-sm expReplaceQtyTable">
            <thead class="bg-info">
            	<th>Item</th>
                <th>Export QTY</th>
            </thead>
            <tbody>    
                <tr>
                	<td>
                    <input type="text" class="form-control allLinkedInGroup" id="replacedItemCode" 
                    list="AllLinkedItems"/>
                    	<datalist id="AllLinkedItems">
                        <?php
						if($catgLenght != 1611)
						{
							$sqlGetAllLinkedItems = "SELECT `descriptioncode`, `itemname`, `partno`, 
							`warehouse` FROM `lookupstock` WHERE `descriptioncode` LIKE('$catgLenght%')
							AND `descriptioncode` != $ItemCode";
							$queryGetAllLinkedItems=mysqli_query($link,$sqlGetAllLinkedItems)or 
							die("ERROR :01-AM_AMDL_S".mysqli_error($link));
							while($resGetAllLinkedItems= mysqli_fetch_assoc($queryGetAllLinkedItems))
							{
								echo "
									<option data-value='$resGetAllLinkedItems[descriptioncode]' 
									value='$resGetAllLinkedItems[itemname]'> 
									$resGetAllLinkedItems[partno], $resGetAllLinkedItems[warehouse]
								";
							}
								
						}
							?>
                        </datalist>
                    
                    </td>
                    
                    <td>
                    <input type="number" class="form-control newExptQTY" min="1" id="newReplaceExptQTY" 
                    max="<?php echo $AllreminQTY;?>" min="1" />
                    </td>
                </tr>
                <tr>
                	<td align="center" colspan="2">
                    <button class="btn btn-success btn-sm" id="saveReplaceExptItemQTYBTN">Save</button>
                    </td>
                </tr>
             </tbody>   
            </table>
       	 <input type="number" value="<?php echo $AllreminQTY;?>" style="display:none" id="qtyRemining"/>
       </div>
       
<script type="text/javascript">

$(document).ready(function() {
	
	
	$("#saveReplaceExptItemQTYBTN").click(function(){
		
	var exptHWRIDR = $("#HWRowId").val();
	var exptOrderRIDR = $("#PORowId").val();
	var exptNewQTYR = $("#newReplaceExptQTY").val();
	var exportedQTYR = $("#ExptQTY").val();
	var OfferQTYR = $("#OfferQTY").val();
	var orderNumR = $("#orderNo").val();
	var qtyRemain = $("#qtyRemining").val();
	
	var selectedRDescripName = $("#replacedItemCode").val();
	var DescripRChosenValideate = $('#AllLinkedItems [value="' + selectedRDescripName + '"]');
	var data1 = {};
			$("#AllLinkedItems option").each(function(i,el) {  
  			 data1[$(el).data("value")] = $(el).val();
			});
		console.log(data1, $("#AllLinkedItems option").val());
	var DescripRCode = $('#AllLinkedItems [value="' + selectedRDescripName + '"]').data('value');
	
	if(DescripRChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Item from List');
			$("#replacedItemCode").css("border-color","red");
		  setTimeout(function(){
		   $("#replacedItemCode").css("border-color","#EBEBEB");    						
		   $("#replacedItemCode").val('');	
		   $("#replacedItemCode").focus();							
		  }, 1500);
		}
	else if(exptNewQTYR == 0 || exptNewQTYR == "")
	   {
			alert('Please add exported QTY');
			$("#newReplaceExptQTY").css("border-color","red");
		  setTimeout(function(){
		   $("#newReplaceExptQTY").css("border-color","#EBEBEB");    						
		   $("#newReplaceExptQTY").focus();							
		  }, 1500);
		}
	else if(Number(exptNewQTYR) > Number(qtyRemain))
	   {
			alert('QTY requested more than the remining QTY which is equal= ' + exptNewQTYR +' ' +qtyRemain);
			$("#newReplaceExptQTY").css("border-color","red");
		  setTimeout(function(){
		   $("#newReplaceExptQTY").css("border-color","#EBEBEB");    						
		   $("#newReplaceExptQTY").focus();							
		  }, 1500);
		}	
	else
	{
		$.ajax({
			 
				url:"dist/php/saveExportReplceHW.php",
				type:"POST",
				data:{offerPropRID:exptHWRIDR, orderRIDR:exptOrderRIDR,expedQTYR:exportedQTYR,offerdQTYHW:OfferQTYR,newExptQTYR:exptNewQTYR,orderNoR:orderNumR,replacedHWCode:DescripRCode,replacedHWName:selectedRDescripName},
				beforeSend: function(){
						$("#saveReplaceExptItemQTYBTN").prop('disabled', true);
						$("#allValidPO").prop("disabled", true);
					},
					 
				success: function(doneExportRStock)
					{
						
						 if(doneExportRStock == 0)
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
						 else if(doneExportRStock == 1)
						{
							alert("Data Saved");
							$("#saveReplaceExptItemQTYBTN").prop('disabled', false);
								//$("#AllCustPo").prop("disabled", false);
								
								$(".finishBTN").show();
								$(".allValidPO").prop("disabled", true);
							setTimeout(function(){				
								;
								$(".expItemsTable").html('');
		$(".expItemsTable").load("dist/php/allPOStockExp.php",{PoNumGet:orderNumR, PoIdGet:exptOrderRIDR});
								$('.ShowHWDataHist').html('');
								$(".myModal").modal('toggle');
																
							}, 1000);
																	
						}
						else
						{
							alert(doneExportStock);
							$("#saveReplaceExptItemQTYBTN").prop('disabled', false);
							$(".allValidPO").prop("disabled", false);
						}
					}	
			
			});
	}
		
	return false;	
		});
    
});

</script>