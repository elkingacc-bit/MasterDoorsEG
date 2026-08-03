$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$('#AllCustPo').load("dist/php/allCustPOExpt.php");

				$.ajax({
						url:"dist/php/checkWHDocPending.php",
						type:"POST",
						success: function(doneCheckPenddingDoc)
						{
							if(doneCheckPenddingDoc != 1)
							{
								alert("Please print the pending Document before export new Stock");
								$("#allValidPO").prop("disabled", true);
								$(".expItemsTable").html('');
								$(".expItemsTable").load("dist/php/showAllExptItemsTbl.php");
								$(".finishBTN").show();
								var PONumSuccess = doneCheckPenddingDoc.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
								$('.allValidPO').val(PONumSuccess);
								//alert(PONumSuccess);
								
							} 
							else
							{
								
								$(".allValidPO").show();
								$(".allValidPO").prop("disabled", false);
							}
						}
					});


$(".allValidPO").change(function(){
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
			$(".allValidPO").css("border-color","red");
		  setTimeout(function(){
		   $(".allValidPO").css("border-color","#EBEBEB");    						
		   $(".allValidPO").val('');	
		   $(".allValidPO").focus();							
		  }, 1500);
		}
		else
		{
			
								$(".allValidPO").prop("disabled", true);
								if(PoGetItemsType == "Doors")
								{
									$(".expItemsTable").html('');
									$(".expItemsTable").load("dist/php/allPOStockExp.php",
									{PoNumGet:PoGetItemsVal, PoIdGet:PoGetItemsID});
							
								}
								
								else if(PoGetItemsType == "Stock")
								{
									$(".insertItems").show();
									$("#AllPartNum").load("dist/php/allPartNumDListExp.php",
									{PoIdGetStk:PoGetItemsID});
									$("#showAllItems").load("dist/php/allItemsDListExpStk.php",
									{PoIdGetStk:PoGetItemsID});
									$(".expItemsTable").load("dist/php/showAllOldExptItemsTbl.php",
									{expCustPOIDWH:PoGetItemsID});
									$(".expItemsTable").show();
								}

		}
	}


		return false;
  });
  
  
  $(".finishAndPrint").click(function(){
			
		var CustPOExport2 = $(".allValidPO").val();	
		
			var dataPoForPrint = {};
			$("#AllCustPo option").each(function(i,el) {  
  			 dataPoForPrint[$(el).data("value")] = $(el).val();
			});
		console.log(dataPoForPrint, $("#AllCustPo option").val());
	
		var PoRIDForPrint = $('#AllCustPo [value="' + CustPOExport2 + '"]').data('value');
		
		
		//alert(CustPOExport2);
		var PoGetItemsID2 = PoRIDForPrint.split(',')[0];
		//var PoGetItemsType2 = PoRID2.split(',')[1];
		
		$(".finishAndPrint").prop('disabled', true);	
			
							var newAutoDocPrint = window.open("dist/php/printExportStock.php?&PoRID="+PoGetItemsID2+"&PoNum="+CustPOExport2,"_balnk");							
							newAutoDocPrint.focus();
							setTimeout(function(){
								$(".finishAndPrint").prop('disabled', false);
								$(".allValidPO").attr("disabled", false);
								$(".expItemsTable").html('');
								$(".finishBTN").hide();
								$(".expItemsTable").hide();
								$("#7_2").click();
							}, 500);	
						
		
		return false;
		});
  

});