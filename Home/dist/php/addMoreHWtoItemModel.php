<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 5;
	$colspan1 = 2;
	$colspan2 = 4;
	$colspan3 = 4;
	
}
else
{
	$diplay = "none";
	$colspan = 3;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
}	 

$jobRowId = $_POST['AHWJRID'];
$GroupRef = $_POST['AHWIRef'];
$ItemName = $_POST['AHWIName'];
$ItemRIDAdd = $_POST['AHWIRID'];

$sqlGetJobData = "SELECT `startDate`, `customer`, `salesman`, `description` FROM `job` 
WHERE `jobId` = $jobRowId";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_array($queryGetJobData);

$sqlGetCustName = "SELECT `customername` FROM `customers` WHERE `customercode` = $resultGetJobData[customer]";
$queryGetCustName = mysqli_query($link,$sqlGetCustName)or die("ERROR :02-ANJ_GCN_S");
$resultGetCustName = mysqli_fetch_array($queryGetCustName);

$sqlGetSalesName = "SELECT `username` FROM `users` WHERE `codeid` = $resultGetJobData[salesman]";
$queryGetSalesName = mysqli_query($link,$sqlGetSalesName)or die("ERROR :03-ANJ_GCN_S");
$resultGetSalesName = mysqli_fetch_array($queryGetSalesName);


?>

<input type="text" value="<?php echo $jobRowId;?>" id="HWjRowId" style="display:none"/>		
<input type="text" value="<?php echo $resultGetJobData['customer'];?>" id="HWCustCode" style="display:none"/>		
<input type="text" value="<?php echo $resultGetCustName['customername'];?>" id="HWCustName" style="display:none"/>
<input type="text" value="<?php echo $ItemName;?>" id="HWItemName" style="display:none"/>
<input type="text" value="<?php echo $GroupRef;?>" id="HWGroupRef" style="display:none"/>
<input type="text" value="<?php echo $ItemRIDAdd;?>" id="ItemRIDFADD" style="display:none"/>
<input type="text" value="<?php echo $Permissiom;?>" id="usPermission" style="display:none"/>
 <input type="text" style="display:none;" class="UserPermiss" value="<?php echo $Permissiom?>"/>

<table class="table caption-top" style=" width:100%">
      <caption align="center">Add all reqiered HW for One Door and system will applied it for all Door QTY
      </caption>
      	<thead class="bg-info">
        	<th>Part No</th>
            <th>Name</th>
            <th>QTY</th>
            <th style="display:<?php echo $diplay;?>">Price</th>
            <th style="display:<?php echo $diplay;?>">Total</th>
        </thead>
      	<tbody> 
        	<td  class='col-sm-2'>
            	<input type="text" id="partNoAHW" class="form-control partNo sndForm" list="AllPartNum"/>
                <datalist id="AHWAllPartNum"></datalist>
            </td>
            <td class='col-sm-2'>
            	<input type="text" id="ItemNameAHW" class="form-control ItemName sndForm" list="showAllItems"/>
                <datalist id="AHWshowAllItems"></datalist>
            </td>
            <td class='col-sm-1'>
        		<input type="number" id="descQtyAHW" class="form-control descQty sndForm" min="1" />
       	 	</td>
            <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        		<input type="number" id="descPriceAHW" class="form-control descPrice sndForm" min="0.0" 
                step="0.0" value="0"/>
       	 	</td>
            <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        		<input type="number" id="totalPriceAHW" class="form-control-plaintext totalPrice sndForm" 
                readonly value="0"/>
       	 	</td>
            <tr>
            <td colspan="<?php echo $colspan;?>" align="center">
            	<button class="btn btn-sm btn-success" id="addItemHWBtnMDL">Save</button>
            </td>
            </tr>
        </tbody>
      </table>

<script type="text/javascript">
// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

$(".descPrice").dblclick(function(){
		
		var Premission2 = $(".UserPermiss").val();
		
		if(Premission2 == "Admin" || Premission2 == "Manager")
		{
			$(this).removeAttr('readonly');
		}
		
		
		return false;
		});	

$("#AHWAllPartNum").load("dist/php/allPartNumDListAK.php");
$("#AHWshowAllItems").load("dist/php/allItemsDListAK.php");


$("#descQtyAHW").keyup(function(){
		
		var netPriceAWH = $(this).val();
		
		var hardwareQTYAWH = $("#descPriceAHW").val();
		if(hardwareQTYAWH != "")
		{
			netPriceAWH = parseFloat(netPriceAWH).toFixed(1);
			
			var totalPriceAHW = parseFloat(hardwareQTYAWH * netPriceAWH).toFixed(1);
			
			$("#totalPriceAHW").val(totalPriceAHW).css("font-weight","bold");
		}
		
		});	

$("#descPriceAHW").keyup(function(){
		
		var hardwareQTYAWH2 = $(this).val();
		
		var netPriceAWH2 = $("#descQtyAHW").val();
		if(netPriceAWH2 != "")
		{
			netPriceAWH2 = parseFloat(netPriceAWH2).toFixed(1);
			
			var totalPriceAHW2 = parseFloat(hardwareQTYAWH2 * netPriceAWH2).toFixed(1);
			
			$("#totalPriceAHW").val(totalPriceAHW2).css("font-weight","bold");
		}
		
		});	


	$("#partNoAHW").change(function(){
	
var selectedPartNum = $(this).val();
	
var PartNumChosenValideate = $('#AHWAllPartNum [value="' + selectedPartNum + '"]');
if(selectedPartNum != "")
{
	
	if(PartNumChosenValideate.length <= 0)
	{
		alert('Please Choose Valid Part Number form the list');
		$("#partNoAHW").css("border-color","red");
		setTimeout(function(){
		   $("#partNoAHW").css("border-color","#EBEBEB");    						
		   $("#partNoAHW").val('');	
		   $("#partNoAHW").focus();							
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
				
				$("#partNoAHW").prop("readonly", true);
				$(".descPrice").val('');
				
			},
			success: function(showPNData){
				
				 $("#partNoAHW").prop("readonly", false);
				// $(".descPrice").prop("readonly", true);
				 $("#ItemNameAHW").val(showPNData.ItemName);
				 $(".descPrice").val(showPNData.ItemPrice);
				
				 
				 
			}
			
			
			});
		
	}
}
else if(selectedPartNum == "")
{
	 $("#ItemNameAHW").val("");
	
}
});//partNum

	$("#ItemNameAHW").change(function(){
	
var selectedDescrip = $(this).val();
	
var DescripChosenValideate = $('#AHWshowAllItems [value="' + selectedDescrip + '"]');
if(selectedDescrip != "")
{
	
	if(DescripChosenValideate.length <= 0)
	{
		alert('Please Choose Valid Item form the list');
		$("#ItemNameAHW").css("border-color","red");
		setTimeout(function(){
		   $("#ItemNameAHW").css("border-color","#EBEBEB");    						
		   $("#ItemNameAHW").val('');	
		   $("#ItemNameAHW").focus();							
		}, 1500);
	}
	
	else 
	{
		
		var data1 = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data1[$(el).data("value")] = $(el).val();
			});
		console.log(data1, $("#AHWshowAllItems option").val());
		var DescripforCheck = $('#AHWshowAllItems [value="' + selectedDescrip + '"]').data('value');
		
		$.ajax({
				
			url:"dist/php/getDescripDataExport.php",
			type:"POST",
			data:{sDescrip:DescripforCheck},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$("#ItemNameAHW").prop("readonly", true);
				$(".descPrice").val('');	
			},
			success: function(showDescripData){
				
				$("#ItemNameAHW").prop("readonly", false);
				 //$(".descPrice").prop("readonly", true);
			$("#partNoAHW").val(showDescripData.partNumGet);
			$(".descPrice").val(showDescripData.ItemPrice);
			}
			
			
			});
		
	}
}
else if(selectedDescrip == "")
{
	 $("#partNoAHW").val("");
}
});//Description

	$("#addItemHWBtnMDL").click(function(){
		
	var usPermission = $("#usPermission").val();
	var AHWrequItemName = $("#HWItemName").val();	
	var AHWGroupRef = $("#HWGroupRef").val();	
		var data = {};
			$("#AHWshowAllItems option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#AHWshowAllItems option").val());
	var AHWDecripName = $("#ItemNameAHW").val();
	var AHWDescription = $('#AHWshowAllItems [value="' + AHWDecripName + '"]').data('value');
	var AHWpartNumber = $("#partNoAHW").val();
	var AHWRequQTY = $("#descQtyAHW").val();
	var AHWItemPrice = $("#descPriceAHW").val();
	var AHMWTotalPrice = $("#totalPriceAHW").val();
	var AHWJobRowId = $("#HWjRowId").val();
	var AHWCustCode = $("#HWCustCode").val();
	var AHWCustName = $("#HWCustName").val();
	var AHWItemRowID = $("#ItemRIDFADD").val();
	
	if(AHWpartNumber == "")
	{
		alert('Please Choose Valid Part Number form the list');
		$("#partNo").css("border-color","red");
		setTimeout(function(){
		   $("#partNo").css("border-color","#EBEBEB");    						
		   $("#partNo").val('');	
		   $("#partNo").focus();							
		}, 1500);
	}
	else if(AHWDecripName == "")
	{
		alert('Please Choose Valid Item form the list');
		$("#ItemNameAHW").css("border-color","red");
		setTimeout(function(){
		   $("#ItemNameAHW").css("border-color","#EBEBEB");    						
		   $("#ItemNameAHW").val('');	
		   $("#ItemNameAHW").focus();							
		}, 1500);
	}
	else if(AHWRequQTY == "" || AHWRequQTY == 0)
	{
		alert('Please add qty 0 value not accepted');
		$("#descQtyAHW").css("border-color","red");
		setTimeout(function(){
		   $("#descQtyAHW").css("border-color","#EBEBEB");    						
		   $("#descQtyAHW").focus();							
		}, 1500);
	}
	else
	{
		if(usPermission == "Admin"  || usPermission == "Manager")
		{
			if(AHWItemPrice == "" || AHWItemPrice == 0)
			{
				alert('Please add Price 0 value not accepted');
				$("#descPriceAHW").css("border-color","red");
				setTimeout(function(){
				   $("#descPriceAHW").css("border-color","#EBEBEB");    						
				   $("#descPriceAHW").focus();							
				}, 1500);
			}
			else
			{
				$.ajax({
				
				url:"dist/php/saveAddHWToItem.php",
				type:"POST",
				data:{HWname:AHWDecripName,HWCode:AHWDescription,HWPartNo:AHWpartNumber,HWQTY:AHWRequQTY,HWPrice:AHWItemPrice,HWItemTPrice:AHMWTotalPrice,HWJobId:AHWJobRowId,HWCustCode:AHWCustCode,HWCustName:AHWCustName, hwItemName:AHWrequItemName,HWItemRowId:AHWItemRowID},
				beforeSend: function(){
						$("#addItemHWBtnMDL").prop('disabled', true);
						
					},
					success: function(doneAddHWToItemMDL)
					{
						if(doneAddHWToItemMDL == 0)
						{
							alert("Hardware selected Is Already existing in this Item.!");
							$("#addItemHWBtnMDL").prop('disabled', false);
							$("#partNoAHW").css("border-color","red");
							$('#ItemNameAHW').css("border-color","red");
							setTimeout(function(){
								$('#ItemNameAHW').css("border-color","#EBEBEB");
								 $("#partNoAHW").css("border-color","#EBEBEB");    
								$('#ItemNameAHW').focus();				
							}, 1500);
						}
						else if(doneAddHWToItemMDL == 1)
						{
							alert("Data Saved");
							//$("#EditItemOfferBtn").hide();
							setTimeout(function(){				
								$("#addItemHWBtn").prop('disabled', false);
								 $('.ShowHWData').html('');
								 $('.ShowHWData').load("dist/php/showAssignedHWModel.php",{ModelJobRID:AHWJobRowId, ModelItemHWRef:AHWGroupRef, ModelItemRID:AHWItemRowID});
								 
							}, 500);
							//$(".sndForm").val("");
							$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:AHWJobRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
							
							$(".oldAddItems").html("");
							//$(".HWadded").show("");
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:AHWJobRowId});
						 	
						}
						else
						{
							alert(doneAddHWToItemMDL);
							$("#addItemHWBtnMDL").prop('disabled', false);
						}
					}
			
			});
			}
		}
		else
		{
				$.ajax({
				
				url:"dist/php/saveAddHWToItem.php",
				type:"POST",
				data:{HWname:AHWDecripName,HWCode:AHWDescription,HWPartNo:AHWpartNumber,HWQTY:AHWRequQTY,HWPrice:AHWItemPrice,HWItemTPrice:AHMWTotalPrice,HWJobId:AHWJobRowId,HWCustCode:AHWCustCode,HWCustName:AHWCustName, hwItemName:AHWrequItemName,HWItemRowId:AHWItemRowID},
				beforeSend: function(){
						$("#addItemHWBtnMDL").prop('disabled', true);
						
					},
					success: function(doneAddHWToItemMDL)
					{
						if(doneAddHWToItemMDL == 0)
						{
							alert("Hardware selected Is Already existing in this Item.!");
							$("#addItemHWBtnMDL").prop('disabled', false);
							$("#partNoAHW").css("border-color","red");
							$('#ItemNameAHW').css("border-color","red");
							setTimeout(function(){
								$('#ItemNameAHW').css("border-color","#EBEBEB");
								 $("#partNoAHW").css("border-color","#EBEBEB");    
								$('#ItemNameAHW').focus();				
							}, 1500);
						}
						else if(doneAddHWToItemMDL == 1)
						{
							alert("Data Saved");
							//$("#EditItemOfferBtn").hide();
							setTimeout(function(){				
								$("#addItemHWBtn").prop('disabled', false);
								 $('.ShowHWData').html('');
								 $('.ShowHWData').load("dist/php/showAssignedHWModel.php",{ModelJobRID:AHWJobRowId, ModelItemHWRef:AHWGroupRef, ModelItemRID:AHWItemRowID});
								 
							}, 500);
							//$(".sndForm").val("");
							$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:AHWJobRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
							
							$(".oldAddItems").html("");
							//$(".HWadded").show("");
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:AHWJobRowId});
							
							
						}
						else
						{
							alert(doneAddHWToItemMDL);
							$("#addItemHWBtnMDL").prop('disabled', false);
						}
					}
			
			});

		}
	}	
		
		return false;
		});

});


</script>