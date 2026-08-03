// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$('#AllCustPo').load("dist/php/allCustPOExpt.php");


	$("#allValidPO").change(function(){
		var PoGetItemsVal = $(this).val();
		
		
if(PoGetItemsVal != "")
{
	
		var dataPo = {};
			$("#AllCustPo option").each(function(i,el) {  
  			 dataPo[$(el).data("value")] = $(el).val();
			});
		console.log(dataPo, $("#AllCustPo option").val());
	
		var PoIdGetItems = $('#AllCustPo [value="' + PoGetItemsVal + '"]').data('value');
		
		var PoGetItemsID = PoIdGetItems.split(',')[0];
		var PoGetItemsType = PoIdGetItems.split(',')[1];
		//alert(PoGetItemsID);
		
		var PoChosenValideate2 = $('#AllCustPo [value="' + PoGetItemsVal + '"]');
		if(PoChosenValideate2.length <= 0)
	   {
			alert('Please Choose Valid Customer name / PO Number form the list');
			$("#allValidPO").css("border-color","red");
		  setTimeout(function(){
		   $("#allValidPO").css("border-color","#EBEBEB");    						
		   $("#allValidPO").val('');	
		   $("#allValidPO").focus();							
		  }, 1500);
		}
		else
		{
	
		$("#AllCustPo").prop("disabled", true);
		if(PoGetItemsType == "Doors")
		{
			$(".itesmT").show();
			$("#poItems").load("dist/php/allItemsDListExp.php",{PoNumGet:PoGetItemsVal, PoIdGet:PoGetItemsID});
	
		}
		
		else if(PoGetItemsType == "Stock")
		{
			$(".insertItems").show();
			$("#AllPartNum").load("dist/php/allPartNumDListExp.php",
			{PoIdGetStk:PoGetItemsID});
			$("#showAllItems").load("dist/php/allItemsDListExpStk.php",
			{PoIdGetStk:PoGetItemsID});
			$(".expItemsTable").load("dist/php/showAllOldExptItemsTbl.php",{expCustPOIDWH:PoGetItemsID});
			$(".expItemsTable").show();
		}
	}
}
		return false;
  });
  
  
$("#relatedItems").change(function(){
	
	var itemRowVal = $(this).val();
	var dataItemRID = {};
			$("#poItems option").each(function(i,el) {  
  			 dataItemRID[$(el).data("value")] = $(el).val();
			});
		console.log(dataItemRID, $("#poItems option").val());
	
		var RowIDGetItems = $('#poItems [value="' + itemRowVal + '"]').data('value');
		var ItemChosenValideate2 = $('#poItems [value="' + itemRowVal + '"]');
		if(ItemChosenValideate2.length <= 0)
	   {
			alert('Please Choose Valid Item Type list');
			$("#poItems").css("border-color","red");
		  setTimeout(function(){
		   $("#poItems").css("border-color","#EBEBEB");    						
		   $("#poItems").val('');	
		   $("#poItems").focus();							
		  }, 1500);
		}
		else
		{
			$(".insertItems").show();
			
			$("#AllPartNum").load("dist/php/allPartNumDListExpHW.php",{ItemRowIdStock:RowIDGetItems});
			$("#showAllItems").load("dist/php/allItemsDListExpHW.php",{ItemRowIdStock:RowIDGetItems});
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
		
	
	$("#saveExportStock").click(function(){
		
		var itemRowVal2 = $("#relatedItems").val();
		var dataItemRID2 = {};
			$("#poItems option").each(function(i,el) {  
  			 dataItemRID2[$(el).data("value")] = $(el).val();
			});
		console.log(dataItemRID2, $("#poItems option").val());
	
		var RowIDGetItems2 = $('#poItems [value="' + itemRowVal2 + '"]').data('value');
		
		var dataItems = {};
			$("#showAllItems option").each(function(i,el) {  
  			 dataItems[$(el).data("value")] = $(el).val();
			});
		console.log(dataItems, $("#showAllItems option").val());
		
	var DecripName = $("#ItemName").val();
	var Description = $('#showAllItems [value="' + DecripName + '"]').data('value');
	
	var DescCodeDL = Description.split(',')[0];
	var DescQTYDl = Description.split(',')[1];
	
	var CustPOExport = $("#allValidPO").val();
	var custdata = {};
$("#AllCustPo option").each(function(i,el) {  
   custdata[$(el).data("value")] = $(el).val();
});
console.log(custdata, $("#AllCustPo option").val());

	var PoChosenValideate = $('#AllCustPo [value="' + CustPOExport + '"]');					
	var PoRID = $('#AllCustPo [value="' + CustPOExport + '"]').data('value');
	
	var PoSaveRowID = PoRID.split(',')[0];
	var PoSaveType = PoRID.split(',')[1];
	
	var partNumber = $("#partNo").val();
	var exptvQty = $("#descRecevQty").val();
	
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
	else if(CustPOExport == "" || CustPOExport == 0)
	{
		alert('Please Choose Valid Customer name / PO Number form the list');
		$("#allValidPO").css("border-color","red");
		setTimeout(function(){
		   $("#allValidPO").css("border-color","#EBEBEB");    						
		   $("#allValidPO").focus();							
		}, 1500);
	}
	if(PoChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer name / PO Number form the list');
			$("#allValidPO").css("border-color","red");
		  setTimeout(function(){
		   $("#allValidPO").css("border-color","#EBEBEB");    						
		   $("#allValidPO").val('');	
		   $("#allValidPO").focus();							
		  }, 1500);
		}
	else if(exptvQty == "" || exptvQty == 0)
	{
		alert('Please add qty 0 value not accepted');
		$("#descRecevQty").css("border-color","red");
		setTimeout(function(){
		   $("#descRecevQty").css("border-color","#EBEBEB");    						
		   $("#descRecevQty").focus();							
		}, 1500);
	}
	
	else if(exptvQty != DescQTYDl)
	{
		alert('QTY add not equal Order QTY expected = '+DescQTYDl);
		$("#descRecevQty").css("border-color","red");
		setTimeout(function(){
		   $("#descRecevQty").css("border-color","#EBEBEB");    						
		   $("#descRecevQty").focus();							
		}, 1500);
	}
	
	else
	{
		$.ajax({
				
				url:"dist/php/saveExportStock.php",
				type:"POST",
				data:{expPartNo:partNumber,expItemName:DecripName,expItemCode:DescCodeDL,ExpQty:exptvQty,expPo:CustPOExport,expPoRowId:PoSaveRowID,expPoType:PoSaveType,itemRowIdExp:RowIDGetItems2},
				beforeSend: function(){
						$("#saveExportStock").prop('disabled', true);
						$("#AllCustPo").attr("readonly", true);
					},
					success: function(doneExportStock)
					{
						
						 if(doneExportStock == 0)
						{
							alert("No Avlibile Stock to Export");
							$("#saveExportStock").prop('disabled', false);
							$(".AllCustPo").attr("readonly", false);
							$("#descQty").css("border-color","red");
							setTimeout(function(){
							   $("#descQty").css("border-color","#EBEBEB");    						
							   $("#descQty").focus();							
							}, 1500);																
						}
						 else if(doneExportStock == 1)
						{
							alert("Data Saved");
							setTimeout(function(){				
								$("#saveExportStock").prop('disabled', false);
								//$("#AllCustPo").prop("disabled", false);
								
								$(".finishBTN").show();
								$(".expItemsTable").html('');
								$(".expItemsTable").load("dist/php/showAllExptItemsTbl.php",
								{expCustPOIDWH:PoSaveRowID});
								$(".AllCustPo").attr("readonly", true);
								$(".expItemsTable").show();
								if(PoSaveType == 'Doors')
								{
									$("#AllPartNum").load("dist/php/allPartNumDListExpHW.php",
									{ItemRowIdStock:RowIDGetItems2});
									$("#showAllItems").load("dist/php/allItemsDListExpHW.php",
									{ItemRowIdStock:RowIDGetItems2});
								}
								else if(PoSaveType == 'Stock')
								{
									$(".expItemsTable").html('');
									$("#AllPartNum").html('');
									
									$("#showAllItems").html('');
									
									$("#AllPartNum").load("dist/php/allPartNumDListExp.php",
									{PoIdGetStk:PoSaveRowID});
									$("#showAllItems").load("dist/php/allItemsDListExpStk.php",
									{PoIdGetStk:PoSaveRowID});
									$(".expItemsTable").load("dist/php/showAllOldExptItemsTbl.php",
									{expCustPOIDWH:PoSaveRowID});
									$(".expItemsTable").show();
								}
								else
								{
									alert("Unexpected Error!!");
								}
							
																
							}, 500);
							$(".sndForm").val("");
							//$(".itesmT").hide();
																					
						}
						else
						{
							alert(doneExportStock);
							$("#saveExportStock").prop('disabled', false);
							$(".AllCustPo").attr("readonly", false);
						}
					}
			
			});
	}
	
		
		return false;
		});	
	
		$(".finishAndPrint").click(function(){
			
		var CustPOExport2 = $("#allValidPO").val();	
			var custdata2 = {};
$("#AllCustPo option").each(function(i,el) {  
   custdata2[$(el).data("value")] = $(el).val();
});
console.log(CustPOExport2, $("#AllCustPo option").val());
			
		var PoRID2 = $('#AllCustPo [value="' + CustPOExport2 + '"]').data('value');
		var PoSaveRowID2 = PoRID2.split(',')[0];
		$(".finishAndPrint").prop('disabled', true);	
			
							var newAutoDocPrint = window.open("dist/php/printExportStock.php?&PoRID="+PoSaveRowID2+"&PoNum="+CustPOExport2,"_balnk");							
							newAutoDocPrint.focus();
							setTimeout(function(){
								$(".finishAndPrint").prop('disabled', false);
								$("#AllCustPo").attr("readonly", false);
								$(".expItemsTable").html('');
								$(".finishBTN").hide();
								$(".expItemsTable").hide();
								$("#7_2").click();
							}, 500);	
						
		
		return false;
		});
	
		
});