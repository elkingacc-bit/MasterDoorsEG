// JavaScript Document
$(document).ready(function(){

"use strict";


	$("#AllPartNum").load("dist/php/allPartNumDListAK.php");
	$("#showAllItems").load("dist/php/allItemsDListAK.php");
	
	$("#allAskKitNames").change(function(){
		var AsKitNameChanged = $(this).val();
	
		if(AsKitNameChanged != "")
		{
			
			$(".StockAsKit").show("");
			$(".AddedItems").html("");
			
			var dataAsKitLoad = {};
			$("#AsKitList option").each(function(i,el) {  
  			 dataAsKitLoad[$(el).data("value")] = $(el).val();
			});
			console.log(dataAsKitLoad, $("#AsKitList option").val());
			var AsKitIDValLoad = $('#AsKitList [value="' + AsKitNameChanged + '"]').data('value');
			
			$(".AddedItems").load("dist/php/AsKitOldAddItems.php",{RefAsKitId:AsKitIDValLoad});	
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
//add to databaes;;//
	$("#AddToAsKitBTN").click(function(){
		
		var dataAsKit = {};
			$("#AsKitList option").each(function(i,el) {  
  			 dataAsKit[$(el).data("value")] = $(el).val();
			});
		console.log(dataAsKit, $("#AsKitList option").val());
	var AsKitID = $("#allAskKitNames").val();
	var AsKitIDVal = $('#AsKitList [value="' + AsKitID + '"]').data('value');
	
	var data = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#showAllItems option").val());
	var DecripCode = $("#ItemName").val();
	var Description = $('#showAllItems [value="' + DecripCode + '"]').data('value');
	var partNumber = $("#partNo").val();
	var requierQTY = $("#requierQTY").val();
	
	if(AsKitID == "")
	{
		alert('Please Choose Valid Name form the list');
		$("#allAskKitNames").css("border-color","red");
		setTimeout(function(){
		   $("#allAskKitNames").css("border-color","#EBEBEB");    						
		   $("#allAskKitNames").val('');	
		   $("#allAskKitNames").focus();							
		}, 1500);
	}
	else if(partNumber == "")
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
				
				url:"dist/php/saveAssignQTYtoAsKit.php",
				type:"POST",
				data:{AsKitRID:AsKitIDVal, partNo:partNumber, descCode:Description, descName:DecripCode,AsKitQty:requierQTY, AsKitName:AsKitID },
				beforeSend: function(){
				$("#exportStockBTN").prop('AddToAsKitBTN', true);	
				},
				success: function(doneAddAsKitItem){
					
					if(doneAddAsKitItem == 0)
					{
						alert("Item is already exist for this Assembly Kit.");
						$("#ItemName").css("border-color","red");
						setTimeout(function(){
						   $("#ItemName").css("border-color","#EBEBEB");    						
						   $("#ItemName").focus();							
						}, 1500);
						
					}
					else if(doneAddAsKitItem == 1)
					{
						alert("Data Saved");
						setTimeout(function(){
						$("#partNo").val('');
						$("#ItemName").val('');
						$("#requierQTY").val('');	
						$("#exportStockBTN").prop('disabled', false);
						 $("#itemImage").attr("src","dist/img/items/defaultItem.jpg");
						$(".AddedItems").html("");
						$(".AddedItems").load("dist/php/AsKitOldAddItems.php",{RefAsKitId:AsKitIDVal});										
						}, 1000);
						
					}
					
					else if(doneAddAsKitItem == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						alert(doneAddAsKitItem);
						$("#exportStockBTN").prop('disabled', false);
					}
					
				}
			
			});
	}
	
	
		
		return false;
		});

});