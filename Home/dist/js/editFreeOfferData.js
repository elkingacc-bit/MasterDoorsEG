// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
var oldDoorJobRID = $("#jRowId").val();
$(".addedMaintDoor").load("dist/php/allAddedTypes.php", {jobIdAutoD:oldDoorJobRID});


$(".itemQty").keyup(function(){
	
		var itemQTY = $(this).val();
		
		var netItemPrice = $(".itemPrice").val();
		if(netItemPrice != '' || netItemPrice != 0)
			{
				netItemPrice = parseFloat(netItemPrice).toFixed(2);
				
				var totalPrice = parseFloat(itemQTY * netItemPrice).toFixed(1);
				
				$(".Total").val(totalPrice).css("font-weight","bold");
			}
		
		});
		
		$(".itemPrice").keyup(function(){
		
		var netPriceWH = $(this).val();
		
		var hardwaareQTY = $(".itemQty").val();
			if(hardwaareQTY != '' || hardwaareQTY != 0)
			{
				netPriceWH = parseFloat(netPriceWH).toFixed(1);
				
				var totalPriceHW = parseFloat(hardwaareQTY * netPriceWH).toFixed(1);
				
				$(".Total").val(totalPriceHW).css("font-weight","bold");
			}
		
		});	
		
		
		$("#AddMaintBtn").click(function(){
			
			var itemJobRowId = $("#jRowId").val();
			var itemCustCode = $("#CustCode").val();
			var itemCustName = $("#CustName").val();
			var TypeText = $(".SHType").val();
			TypeText = TypeText.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			//var typeHeight = $(".heights").val();
			//var typeWedth = $(".widths").val();
			//var typeDepth = $(".depths").val();
			var SHPrice = $(".itemPrice").val();
			SHPrice = parseFloat(SHPrice).toFixed(1);
			var Quantity = $(".itemQty").val();
			var itemTotalPrice = $(".Total").val();
			
			if(TypeText == "" || TypeText == null )
			{
			alert('missing field');
			$('.SHType').css("border-color","red");
			setTimeout(function(){
           		$('.SHType').css("border-color","#EBEBEB");
				$(".SHType").focus();				
				}, 1500);
								
			}
			/*else if(typeHeight == "" || typeHeight == null )
			{
			alert('missing field');
			$('.heights').css("border-color","red");
			setTimeout(function(){
           		$('.heights').css("border-color","#EBEBEB");
				$(".heights").focus();				
				}, 1500);
								
			}
			else if(typeWedth == "" || typeWedth == null )
			{
			alert('missing field');
			$('.widths').css("border-color","red");
			setTimeout(function(){
           		$('.widths').css("border-color","#EBEBEB");
				$(".widths").focus();				
				}, 1500);
								
			}
			else if(typeDepth == "" || typeDepth == null )
			{
			alert('missing field');
			$('.depths').css("border-color","red");
			setTimeout(function(){
           		$('.depths').css("border-color","#EBEBEB");
				$(".depths").focus();				
				}, 1500);
								
			}*/
			else if(SHPrice == "" || SHPrice == null )
			{
			alert('missing field');
			$('.itemPrice').css("border-color","red");
			setTimeout(function(){
           		$('.itemPrice').css("border-color","#EBEBEB");
				$(".itemPrice").focus();				
				}, 1500);
								
			}
			else if(Quantity == "" || Quantity == null )
			{
			alert('missing field');
			$('.itemQty').css("border-color","red");
			setTimeout(function(){
           		$('.itemQty').css("border-color","#EBEBEB");
				$(".itemQty").focus();				
				}, 1500);
								
			}
			else
			{
				$.ajax({
						
					url:"dist/php/saveFreeOfferData.php",
					type:"POST",
					data:{shRowId:itemJobRowId, shCustCode:itemCustCode, shCustname:itemCustName,shType:TypeText,/*THeight:typeHeight, TWedth:typeWedth,TDepth:typeDepth,*/ shPrice:SHPrice, shQTY:Quantity, shTPrice:itemTotalPrice},
					beforeSend: function(){
						$("#AddMaintBtn").prop('disabled', true);
						
					},
					success: function(doneAddFreeOffer)
					{
						if(doneAddFreeOffer == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#AddMaintBtn").prop('disabled', false);
							$('#SHType').css("border-color","red");
							setTimeout(function(){
								$('#SHType').css("border-color","#EBEBEB");
								$('#SHType').focus();				
							}, 1500);
						}
						else if(doneAddFreeOffer == 1)
						{
							alert("Data Saved");
								$(".fristForm").val('');
							setTimeout(function(){				
								$("#AddMaintBtn").prop('disabled', false);
							}, 500);
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:itemJobRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							$(".addedMaintDoor").html('');
							$(".addedMaintDoor").load("dist/php/allAddedTypes.php", {jobIdAutoD:itemJobRowId});
						}
						else
						{
							alert(doneAddFreeOffer);
							$("#AddMaintBtn").prop('disabled', false);
						}
					}
					
					
					});
			}
			
			return false;
			});
			
			
			
	$("#EditMaintBtn").click(function(){
			
			var itemRowId2 = $("#rowIdDoorForEdit").val();
			var itemJobRowId2 = $("#jRowId").val();
			var itemCustCode2 = $("#CustCode").val();
			var itemCustName2 = $("#CustName").val();
			var TypeText2 = $(".SHType").val();
			TypeText2 = TypeText2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			//var typeHeight2 = $(".heights").val();
			//var typeWedth2 = $(".widths").val();
			//var typeDepth2 = $(".depths").val();
			var SHPrice2 = $(".itemPrice").val();
			SHPrice2 = parseFloat(SHPrice2).toFixed(1);
			var Quantity2 = $(".itemQty").val();
			var itemTotalPrice2 = $(".Total").val();
			
			if(TypeText2 == "" || TypeText2 == null )
			{
			alert('missing field');
			$('.SHType').css("border-color","red");
			setTimeout(function(){
           		$('.SHType').css("border-color","#EBEBEB");
				$(".SHType").focus();				
				}, 1500);
								
			}
			/*else if(typeHeight2 == "" || typeHeight2 == null )
			{
			alert('missing field');
			$('.heights').css("border-color","red");
			setTimeout(function(){
           		$('.heights').css("border-color","#EBEBEB");
				$(".heights").focus();				
				}, 1500);
								
			}
			else if(typeWedth2 == "" || typeWedth2 == null )
			{
			alert('missing field');
			$('.widths').css("border-color","red");
			setTimeout(function(){
           		$('.widths').css("border-color","#EBEBEB");
				$(".widths").focus();				
				}, 1500);
								
			}
			else if(typeDepth2 == "" || typeDepth2 == null )
			{
			alert('missing field');
			$('.depths').css("border-color","red");
			setTimeout(function(){
           		$('.depths').css("border-color","#EBEBEB");
				$(".depths").focus();				
				}, 1500);
								
			}*/
			else if(SHPrice2 == "" || SHPrice2 == null )
			{
			alert('missing field');
			$('.itemPrice').css("border-color","red");
			setTimeout(function(){
           		$('.itemPrice').css("border-color","#EBEBEB");
				$(".itemPrice").focus();				
				}, 1500);
								
			}
			else if(Quantity2 == "" || Quantity2 == null )
			{
			alert('missing field');
			$('.itemQty').css("border-color","red");
			setTimeout(function(){
           		$('.itemQty').css("border-color","#EBEBEB");
				$(".itemQty").focus();				
				}, 1500);
								
			}
			else
			{
				$.ajax({
						
					url:"dist/php/saveEditFreeOfferData.php",
					type:"POST",
					data:{TypeRID:itemRowId2,shRowId:itemJobRowId2, shCustCode:itemCustCode2, shCustname:itemCustName2,shType:TypeText2,/*THeight:typeHeight2, TWedth:typeWedth2,TDepth:typeDepth2,*/ shPrice:SHPrice2, shQTY:Quantity2, shTPrice:itemTotalPrice2},
					beforeSend: function(){
						$("#EditMaintBtn").prop('disabled', true);
						
					},
					success: function(doneAddFreeOffer2)
					{
						if(doneAddFreeOffer2 == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#EditMaintBtn").prop('disabled', false);
							$('#SHType').css("border-color","red");
							setTimeout(function(){
								$('#SHType').css("border-color","#EBEBEB");
								$('#SHType').focus();				
							}, 1500);
						}
						else if(doneAddFreeOffer2 == 1)
						{
							alert("Data Saved");
								$(".fristForm").val('');
								
							setTimeout(function(){				
								$("#EditMaintBtn").prop('disabled', false);
							}, 500);
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:itemJobRowId2},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							$(".addedMaintDoor").html('');
							$(".addedMaintDoor").show();
							$(".addedMaintDoor").load("dist/php/allAddedTypes.php", {jobIdAutoD:itemJobRowId2});
							$(".AddDoorOfferTR").show();
							$("#AddMaintBtn").show();
							$(".EditDoorOfferTR").hide();
						}
						else
						{
							alert(doneAddFreeOffer2);
							$("#EditMaintBtn").prop('disabled', false);
						}
					}
					
					
					});
			}
			
			return false;
			});		
		
$(".backBTN2").click(function(){
	
	$(".fristForm").val('');
	
	$(".addedMaintDoor").show();
	$(".backBTN2").hide();
	$(".backBTN").show();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	return false; 
	});	
});