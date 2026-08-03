// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$("#showSuppInvo").load("dist/php/checkPendReceivingInvo.php");

$("#InvoNo").change(function(){
	
	var invoNumDL = $(this).val();
	//alert(invoNumDL);
	if(invoNumDL != "")
	{
	  
	$.ajax({
		
			url:"dist/php/checkInvoReceiving.php",
			type:"POST",
			data:{invoForCheckQTY:invoNumDL},
			success: function(checkResult){
			
				if(checkResult == 0)
				{
					alert("No pending Item/s for recieving in Invoice No. "+invoNumDL);
					$("#InvoNo").css("border-color","red");
					setTimeout(function(){
					   $("#InvoNo").css("border-color","#EBEBEB");    						
					   $("#InvoNo").focus();
					   $("#InvoNo").val('');							
					}, 1500);
				}
				else
				{
					$("#AllPartNum").load("dist/php/allPartNumDListInvo.php",{invNumDl:invoNumDL});
					$("#showAllItems").load("dist/php/allItemsDListInvo.php",{invNumDl:invoNumDL});
					$("#InvoNo").prop("readonly", true);
				}
			}
		
		});
	}
	
	return false;
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
		
	
	$("#saveReceiveStock").click(function(){
		
		var data = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#showAllItems option").val());
	var DecripName = $("#ItemName").val();
	var invoItemRID = $('#showAllItems [value="' + DecripName + '"]').data('value');
	
	var partNumber = $("#partNo").val();
	var RecevQty = $("#descRecevQty").val();
	var PruchesInvo = $("#InvoNo").val();
	
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
	else if(PruchesInvo == "" || PruchesInvo == 0)
	{
		alert('Please add Pruchsing Invoice Number');
		$("#InvoNo").css("border-color","red");
		setTimeout(function(){
		   $("#InvoNo").css("border-color","#EBEBEB");    						
		   $("#InvoNo").focus();							
		}, 1500);
	}
	else if(RecevQty == "" || RecevQty == 0)
	{
		alert('Please add qty 0 value not accepted');
		$("#descRecevQty").css("border-color","red");
		setTimeout(function(){
		   $("#descRecevQty").css("border-color","#EBEBEB");    						
		   $("#descRecevQty").focus();							
		}, 1500);
	}
	
	else
	{
		$.ajax({
				
				url:"dist/php/saveReceiveStock.php",
				type:"POST",
				data:{RecPartNo:partNumber,RecItemName:DecripName,RecQty:RecevQty,RecInvo:PruchesInvo,tableRowId:invoItemRID},
				beforeSend: function(){
						$("#saveReceiveStock").prop('disabled', true);
						
					},
					success: function(doneReceiveStock)
					{
					if(doneReceiveStock == 0)
						{
							alert("Received QTY Large than the supply invoice QTY ");
							$(".descRecevQty").css("border-color","red");
							setTimeout(function(){				
								$(".descRecevQty").css("border-color","#EBEBEB");    						
		   						$(".descRecevQty").focus();	
								$("#saveReceiveStock").prop('disabled', false);
																
							}, 1500);												
						}
						
					else if(doneReceiveStock == 1)
						{
							alert("Data Saved");
							setTimeout(function(){				
								$("#saveReceiveStock").prop('disabled', false);
																
							}, 500);
							$("#partNo").val('');
							$("#ItemName").val('');
							$("#AllPartNum").html('');
							$("#showAllItems").html('');
							$("#AllPartNum").load("dist/php/allPartNumDListInvo.php",{invNumDl:PruchesInvo});
							$("#showAllItems").load("dist/php/allItemsDListInvo.php",{invNumDl:PruchesInvo});
							$(".allRecivedItems").html('');
							$(".allRecivedItems").show();
							$(".allRecivedItems").load("dist/php/allRecvedItems.php",{invNumTbl:PruchesInvo});
							$(".finishBTN").show();
							$(".descRecevQty").val("");
							
																					
						}
						
					else
						{
							alert(doneReceiveStock);
							$("#saveReceiveStock").prop('disabled', false);
						}
					}
			
			});
	}
	
		
		return false;
		});	
	
	$(".finishAndPrint").click(function(){
			
		var PruchesInvo2 = $("#InvoNo").val();	
		$(".finishAndPrint").prop('disabled', true);	
			/*$.ajax({
					url:"dist/php/saveEndReceiveStock.php",
					type:"POST",
					data:{PInvoice:PruchesInvo2},
					beforeSend: function(){
						
						
					},
					success: function(doneEndReceiveStock)
					{
						if(doneEndReceiveStock == 1)
						{*/				
							
							var newAutoDocPrint = window.open("dist/php/printImportStock.php?&InvoiceNo="+PruchesInvo2,"_balnk");							
							newAutoDocPrint.focus();
							setTimeout(function(){
								$(".finishAndPrint").prop('disabled', false);
								$(".finishBTN").hide();
								$("#7_1").click();
							}, 500);	
						/*}
						else
						{
							alert(doneEndReceiveStock);
							$(".finishAndPrint").prop('disabled', false);
						}
					}
				
				});*/
		
		return false;
		});
		
		
});