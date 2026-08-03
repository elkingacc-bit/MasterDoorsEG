// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
var oldItemJobRID = $("#jRowId").val();

$("#AllPartNum").load("dist/php/allPartNumDListAK.php");
$("#showAllItems").load("dist/php/allItemsDListAK.php");
$(".addedStock").load("dist/php/allAddedStockinOffer.php",{OIJRID:oldItemJobRID});
	
		
	$(".descQty").keyup(function(){
		
		var itemQTY = $(this).val();
		
		var netItemPrice = $(".descPrice").val();
		if(netItemPrice != '' || netItemPrice != 0)
			{
				netItemPrice = parseFloat(netItemPrice).toFixed(2);
				
				var totalPrice = parseFloat(itemQTY * netItemPrice).toFixed(1);
				
				$(".totalPrice").val(totalPrice).css("font-weight","bold");
			}
		
		});
		
		
	$(".descPrice").keyup(function(){
		
		var netPriceWH = $(this).val();
		
		var hardwaareQTY = $(".descQty").val();
			if(hardwaareQTY != '' || hardwaareQTY != 0)
			{
				netPriceWH = parseFloat(netPriceWH).toFixed(1);
				
				var totalPriceHW = parseFloat(hardwaareQTY * netPriceWH).toFixed(1);
				
				$(".totalPrice").val(totalPriceHW).css("font-weight","bold");
			}
		
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
				
			},
			success: function(showPNData){
				
				 $("#partNo").prop("readonly", false);
				 
				 $("#ItemName").val(showPNData.ItemName);
				
				 
				 
			}
			
			
			});
		
	}
}
else if(selectedPartNum == "")
{
	 $("#ItemName").val("");
	
}
});//partNum

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
				
			},
			success: function(showDescripData){
				
				$("#ItemName").prop("readonly", false);
				 
			$("#partNo").val(showDescripData.partNumGet);
			}
			
			
			});
		
	}
}
else if(selectedDescrip == "")
{
	 $("#partNo").val("");
}
});//Description
		
	
	$("#addStockBtn").click(function(){
		
		var data = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#showAllItems option").val());
	var DecripName = $("#ItemName").val();
	var Description = $('#showAllItems [value="' + DecripName + '"]').data('value');
	var partNumber = $("#partNo").val();
	var RequQTY = $("#descQty").val();
	var requItemPrice = $("#descPrice").val();
	var requTotalPrice = $("#totalPrice").val();
	var requJobRowId = $("#jRowId").val();
	var requCustCode = $("#CustCode").val();
	var requCustName = $("#CustName").val();
	
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
	else if(DecripName == "")
	{
		alert('Please Choose Valid Item form the list');
		$("#ItemName").css("border-color","red");
		setTimeout(function(){
		   $("#ItemName").css("border-color","#EBEBEB");    						
		   $("#ItemName").val('');	
		   $("#ItemName").focus();							
		}, 1500);
	}
	else if(RequQTY == "" || RequQTY == 0)
	{
		alert('Please add qty 0 value not accepted');
		$("#descQty").css("border-color","red");
		setTimeout(function(){
		   $("#descQty").css("border-color","#EBEBEB");    						
		   $("#descQty").focus();							
		}, 1500);
	}
	else if(requItemPrice == "" )
	{
		alert('Please add Price 0 value not accepted');
		$("#descPrice").css("border-color","red");
		setTimeout(function(){
		   $("#descPrice").css("border-color","#EBEBEB");    						
		   $("#descPrice").focus();							
		}, 1500);
	}
	else
	{
		$.ajax({
				
				url:"dist/php/saveAddStockOffer.php",
				type:"POST",
				data:{HWname:DecripName,HWCode:Description,HWPartNo:partNumber,HWQTY:RequQTY,HWPrice:requItemPrice,HWItemTPrice:requTotalPrice,HWJobId:requJobRowId,HWCustCode:requCustCode,HWCustName:requCustName},
				beforeSend: function(){
						$("#addStockBtn").prop('disabled', true);
						
					},
					success: function(doneAddStock)
					{
						if(doneAddStock == 0)
						{
							alert("selected Item Is Already existing in this Item.!");
							$("#addStockBtn").prop('disabled', false);
							$("#partNo").css("border-color","red");
							$('#itemName').css("border-color","red");
							setTimeout(function(){
								$('#itemName').css("border-color","#EBEBEB");
								 $("#partNo").css("border-color","#EBEBEB");    
								$('#itemName').focus();				
							}, 1500);
						}
						else if(doneAddStock == 1)
						{
							alert("Data Saved");
							setTimeout(function(){				
								$("#addStockBtn").prop('disabled', false);
																
							}, 1500);
							$(".sndForm").val("");
							$(".TotalOffer").html('');
							$("#descPrice").val(0);
							$("#totalPrice").val(0);
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:requJobRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
							
							$(".addedStock").html("");
							$(".addedStock").show("");
							$(".addedStock").load("dist/php/allAddedStockinOffer.php",{OIJRID:requJobRowId});
							
						}
						else
						{
							alert(doneAddStock);
							$("#addStockBtn").prop('disabled', false);
						}
					}
			
			});
	}
	
		
		return false;
		});
		
		
	$("#EditStockinOfferBtn").click(function(){
		
			var data2 = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data2[$(el).data("value")] = $(el).val();
			});
			console.log(data2, $("#showAllItems option").val());
			var DecripName2 = $("#ItemName").val();
			var Description2 = $('#showAllItems [value="' + DecripName2 + '"]').data('value');
			var partNumber2 = $("#partNo").val();
			var RequQTY2 = $("#descQty").val();
			var requItemPrice2 = $("#descPrice").val();
			var requTotalPrice2 = $("#totalPrice").val();
			var requJobRowId2 = $("#jRowId").val();
			var requCustCode2 = $("#CustCode").val();
			var requCustName2 = $("#CustName").val();
			var requTRowId2 = $(".rowIdStockForEdit").val();
			
			if(partNumber2 == "")
			{
				alert('Please Choose Valid Part Number form the list');
				$("#partNo").css("border-color","red");
				setTimeout(function(){
				   $("#partNo").css("border-color","#EBEBEB");    						
				   $("#partNo").val('');	
				   $("#partNo").focus();							
				}, 1500);
			}
			else if(DecripName2 == "")
			{
				alert('Please Choose Valid Item form the list');
				$("#ItemName").css("border-color","red");
				setTimeout(function(){
				   $("#ItemName").css("border-color","#EBEBEB");    						
				   $("#ItemName").val('');	
				   $("#ItemName").focus();							
				}, 1500);
			}
			else if(RequQTY2 == "" || RequQTY2 == 0)
			{
				alert('Please add qty 0 value not accepted');
				$("#descQty").css("border-color","red");
				setTimeout(function(){
				   $("#descQty").css("border-color","#EBEBEB");    						
				   $("#descQty").focus();							
				}, 1500);
			}
			else if(requItemPrice2 == "" || requItemPrice2 == 0)
			{
				alert('Please add Price 0 value not accepted');
				$("#descPrice").css("border-color","red");
				setTimeout(function(){
				   $("#descPrice").css("border-color","#EBEBEB");    						
				   $("#descPrice").focus();							
				}, 1500);
			}
			else
			{
				$.ajax({
						
						url:"dist/php/saveEditStockinOffer.php",
						type:"POST",
						data:{HWname:DecripName2,HWCode:Description2,HWPartNo:partNumber2,HWQTY:RequQTY2,HWPrice:requItemPrice2,HWItemTPrice:requTotalPrice2,HWJobId:requJobRowId2,HWCustCode:requCustCode2,HWCustName:requCustName2,TRowId:requTRowId2},
					beforeSend: function(){
						$("#EditStockinOfferBtn").prop('disabled', true);
						
					},
					success: function(doneEditDescrip)
					{
						if(doneEditDescrip == 0)
						{
							alert("Item Name Is Already existing in this Offer.!");
							$("#EditStockinOfferBtn").prop('disabled', false);
							$('#itemName').css("border-color","red");
							setTimeout(function(){
								$('#itemName').css("border-color","#EBEBEB");
								$('#itemName').focus();				
							}, 1500);
						}
						else if(doneEditDescrip == 1)
						{
							alert("Data Saved");							
							$(".EditStockinOfferTR").hide();
							$(".EditStockinOfferBtn").hide();
							$(".AddItemOfferTR").show();
							$(".addStockBtn").show();
							$(".addedStock").html('');
							$(".addedStock").show();
							$(".sndForm").val('');
							
							setTimeout(function(){				
								$("#EditStockinOfferBtn").prop('disabled', false);
							}, 1500);
							//$(".fristForm").val('');
							//$(".selectedHW").show();
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:requJobRowId2},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							
							$(".addedStock").load("dist/php/allAddedStockinOffer.php",{OIJRID:requJobRowId2});
						}
						else
						{
							alert(doneEditDescrip);
							$("#EditStockinOfferBtn").prop('disabled', false);
						}
					}
					
					
					});
			}
		
		return false;
		});
	
	
	$(".backBTN2").click(function(){
	
	$(".sndForm").val('');
	$(".addedStock").show();
	$(".backBTN2").hide();
	$(".backBTN").show();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	return false; 
	});	
		
		
});