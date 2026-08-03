// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$("#AllPartNum").load("dist/php/allPartNumDListAK.php");
$("#showAllItems").load("dist/php/allItemsDListAK.php");

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
				
				$.ajax({
					
						url:"dist/php/showStockHist.php",
						type:"POST",
						data:{ItemCode:showPNData.ItemCode},
						beforeSend: function(){
							$(".allStockHist").html('');
								
						},
						success: function(showStockHistory){
						
						$(".allStockHist").show();
						$(".allStockHist").html(showStockHistory);	
						
						}
					
					}); 
				 
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


$('.allStockHist').load("dist/php/allExportedItems.php");
	
});