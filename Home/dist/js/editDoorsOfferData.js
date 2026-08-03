// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
var oldItemJobRID = $("#jRowId").val();

$("#AllPartNum").load("dist/php/allPartNumDListAK.php");
$("#showAllItems").load("dist/php/allItemsDListAK.php");
$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:oldItemJobRID});
 
$.ajax({
				
				url:"dist/php/getM2Price.php",
				type:"POST",
				data:{sType:'Doors'},
				success: function(showM2PriceOffer){
					
					var mater2Price = showM2PriceOffer.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
				
					$(".mSqPrice").val(mater2Price);
				}
			
			});
	
$(".itemType").keyup(function(){

var KeyWord = $(this).val();

	if(KeyWord != "")
	{
		$.ajax({
			
				url:"dist/php/loadOldAddedItemType.php",
				type:"POST",
				data:{keyWordSearch:KeyWord},
				async: true,
				cache: false,
				success: function(showOldAddedTypes)
				{
					$("#searchType").html(showOldAddedTypes);
				}
				
			});
	}
	else
	{
		$("#searchType").val('');
		$(".itemName").val('');
		$(".itemHight").val('');
		$(".itemWidth").val('');
		$(".itemDepth").val('');
		$(".itemMSq").val('');
		//$(".mSqPrice").val('');
		$(".MPrice").val('');
		$(".FRMin").val('');
		$(".Remarks").val('');
		$(".Overlap").val('');
	}

});	

$(".itemType").change(function(){
	
	var selectedKeyWord = $(this).val();
	
	
	if(selectedKeyWord != '')
	{
		var keyWordChosenValideate1 = $('#searchType [value="' + selectedKeyWord + '"]');
	
		if(keyWordChosenValideate1.length > 0)
		{
		 var ConfrimloadOldType = confirm("Do you want fulfil door data for door Type: "+selectedKeyWord+"?");
			if(ConfrimloadOldType === true)
			{
				var TypeDataFill = {};
			$("#searchType option").each(function(i,el) {  
  			 TypeDataFill[$(el).data("value")] = $(el).val();
			});
			console.log(TypeDataFill, $("#searchType option").val());
			var DorTypeVal = $("#itemType").val();
			var doorTypeRowId = $('#searchType [value="' + DorTypeVal + '"]').data('value');
				$.ajax({
				
					url:"dist/php/fulfilItemDataDoor.php",
					type:"POST",
					data:{DorTypRID:doorTypeRowId},
					dataType: "json",
					cache: false,
					beforeSend: function(){
						
						$("#EditItemOfferBtn").prop("disabled", true);
						
					},
					success: function(doneFulfilDoorData){
						
						$("#EditItemOfferBtn").prop("disabled", false);
						
					$('.itemQty').css("border-color","red");
					setTimeout(function(){
						$('.itemQty').css("border-color","#EBEBEB");
						$(".itemQty").focus();				
						}, 3500);	
						 
					$(".itemType").val(doneFulfilDoorData.putItemType);
					$(".itemName").val(doneFulfilDoorData.putItemName);
					$(".itemHight").val(doneFulfilDoorData.putItemHight);
					$(".itemWidth").val(doneFulfilDoorData.putItemWidth);
					$(".itemDepth").val(doneFulfilDoorData.putItemDepth);
					$(".itemMSq").val(doneFulfilDoorData.putItemMsqu);
					//$(".mSqPrice").val(doneFulfilDoorData.putItemMsquPrice);
					$(".MPrice").val(doneFulfilDoorData.putItemPrice);
					$(".FRMin").val(doneFulfilDoorData.putItemFRMin);
					$(".Remarks").val(doneFulfilDoorData.putItemRemk);
					$(".Overlap").val(doneFulfilDoorData.putItemOverlap);
					
					}
		
		});

			}
		}
	
	}
	return false;
	});
	
	
$(".MPrice").keyup(function(){
	
	var EditItemPrice = $(this).val();
	var EditItemQTY = $(".itemQty").val();
	
	if(EditItemQTY != "")
	{
		var TotalPriceEdited = Math.round(EditItemQTY * EditItemPrice);
		$(".Total").val(TotalPriceEdited).css("font-weight","bold");
	}	
	
	});	
	


var value = $('.required-entry').filter(function () {
    return this.value === '';
});

	$(".itemWidth").keyup(function(){
		
		var width = $(this).val();
		
			if(width != '' || width != 0)
			{
				var hight = $(".itemHight").val();
				if(hight != '' || hight != 0)
				{
					$(".itemMSq").val('');
					//$(".mSqPrice").val('');
					$(".MPrice").val('');
					width = parseFloat(width).toFixed(2);
					hight = parseFloat(hight).toFixed(2);
					
					var mSquaer = parseFloat(width * hight).toFixed(2);
					
					$(".itemMSq").val(mSquaer).css("font-weight","bold","color","blue");
					if (value.length > 0) 
					{
   			
					var shipping = $(".shipping").val();	
					var m2Price = $(".mSqPrice").val();
					var itemMSquaer = $(".itemMSq").val();
					var install1 = $(".Installation").val();
					var margin = $(".margin").val();
					//alert(shipping);
					
					if(itemMSquaer != '' || itemMSquaer != 0)
						{
							var QTYForTotal = $(".itemQty").val();
							m2Price = parseFloat(m2Price).toFixed(2);
							itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
							
							var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
							
							
							var otherPrice = (Number(shipping) + Number(install1));
							var newNetPrice = (Number(netPrice) + Number(otherPrice));
							margin = parseFloat(margin / 100).toFixed(2);
							var overhead = parseFloat(newNetPrice * margin);
							var salesPrice = Math.round(overhead + newNetPrice);
							var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
							$(".MPrice").val(salesPrice).css("font-weight","bold");
							$(".Total").val(totalQtyPrice).css("font-weight","bold");
						
						}	
						
				  }
				    else
				    {
					  $(".MPrice").val('');
				  }

				}
			}
		});
		
	$(".itemHight").keyup(function(){
		
		var  hight2= $(this).val();
		
			if(hight2 != '' || hight2 != 0)
			{
				var  width2= $(".itemWidth").val();
				if(width2 != '' || width2 != 0)
				{
					$(".itemMSq").val('');
					width2 = parseFloat(width2).toFixed(2);
					hight2 = parseFloat(hight2).toFixed(2);
					
					var mSquaer2 = parseFloat(width2 * hight2).toFixed(2);
					
					$(".itemMSq").val(mSquaer2).css("font-weight","bold","color","blue");
					if (value.length > 0) 
					{
   			
					var shipping = $(".shipping").val();	
					var m2Price = $(".mSqPrice").val();
					var itemMSquaer = $(".itemMSq").val();
					var install1 = $(".Installation").val();
					var margin = $(".margin").val();
					//alert(shipping);
					
					if(itemMSquaer != '' || itemMSquaer != 0)
						{
							var QTYForTotal = $(".itemQty").val();
							m2Price = parseFloat(m2Price).toFixed(2);
							itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
							
							var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
							
							
							var otherPrice = (Number(shipping) + Number(install1));
							var newNetPrice = (Number(netPrice) + Number(otherPrice));
							margin = parseFloat(margin / 100).toFixed(2);
							var overhead = parseFloat(newNetPrice * margin);
							var salesPrice = Math.round(overhead + newNetPrice);
							var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
							$(".MPrice").val(salesPrice).css("font-weight","bold");
							$(".Total").val(totalQtyPrice).css("font-weight","bold");
						
						}	
						
				  }
				  else
				  {
					  $(".MPrice").val('');
				  }

				}
			}
		});
		
	$(".shipping").keyup(function(){
		
		if (value.length > 0) 
		{
   			
		var shipping = $(this).val();	
		var m2Price = $(".mSqPrice").val();
		var itemMSquaer = $(".itemMSq").val();
		var install1 = $(".Installation").val();
		var margin = $(".margin").val();
		//alert(shipping);
		
		if(itemMSquaer != '' || itemMSquaer != 0)
			{
				var QTYForTotal = $(".itemQty").val();
				m2Price = parseFloat(m2Price).toFixed(2);
				itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
				
				var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
				
				
				var otherPrice = (Number(shipping) + Number(install1));
				var newNetPrice = (Number(netPrice) + Number(otherPrice));
				margin = parseFloat(margin / 100).toFixed(2);
				var overhead = parseFloat(newNetPrice * margin);
				var salesPrice = Math.round(overhead + newNetPrice);
				var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
				$(".MPrice").val(salesPrice).css("font-weight","bold");
				$(".Total").val(totalQtyPrice).css("font-weight","bold");
			
			}	
			
		} 
		/*else
		{
			
		}
		
		$(".itemQty").val('');
		$('.itemQty').css("border-color","red");
			setTimeout(function(){
           		$('.itemQty').css("border-color","#EBEBEB");
							
				}, 500);*/
		});	
		
	$(".Installation").keyup(function(){
		
		if (value.length > 0) 
		{
   			
		var install1 = $(this).val();	
		var m2Price = $(".mSqPrice").val();
		var itemMSquaer = $(".itemMSq").val();
		var shipping = $(".shipping").val();
		var margin = $(".margin").val();
		if(itemMSquaer != '' || itemMSquaer != 0)
			{
				var QTYForTotal = $(".itemQty").val();
				m2Price = parseFloat(m2Price).toFixed(2);
				itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
				
				var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
				
				
				var otherPrice = (Number(shipping) + Number(install1));
				var newNetPrice = (Number(netPrice) + Number(otherPrice));
				margin = parseFloat(margin / 100).toFixed(2);
				var overhead = parseFloat(newNetPrice * margin);
				var salesPrice = Math.round(overhead + newNetPrice);
				var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
				$(".MPrice").val(salesPrice).css("font-weight","bold");
				$(".Total").val(totalQtyPrice).css("font-weight","bold");
			
			}	
			
		} 
	
		});	
		
	$(".margin").keyup(function(){
		
		if (value.length > 0) 
		{
   			
		var margin = $(this).val();	
		var m2Price = $(".mSqPrice").val();
		var itemMSquaer = $(".itemMSq").val();
		var shipping = $(".shipping").val();
		var install1 = $(".Installation").val();
		if(itemMSquaer != '' || itemMSquaer != 0)
			{
				var QTYForTotal = $(".itemQty").val();
				m2Price = parseFloat(m2Price).toFixed(2);
				itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
				
				var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
				
				
				var otherPrice = (Number(shipping) + Number(install1));
				var newNetPrice = (Number(netPrice) + Number(otherPrice));
				margin = parseFloat(margin / 100).toFixed(2);
				var overhead = parseFloat(newNetPrice * margin);
				var salesPrice = Math.round(overhead + newNetPrice);
				var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
				$(".MPrice").val(salesPrice).css("font-weight","bold");
				$(".Total").val(totalQtyPrice).css("font-weight","bold");
			
			}	
			
		} 

		});	
		
	$(".mSqPrice").keyup(function(){
		
		if (value.length > 0) 
		{
   			
		var m2Price = $(this).val();	
		var margin = $(".margin").val();
		var itemMSquaer = $(".itemMSq").val();
		var shipping = $(".shipping").val();
		var install1 = $(".Installation").val();
		if(itemMSquaer != '' || itemMSquaer != 0)
			{
				var QTYForTotal = $(".itemQty").val();
				m2Price = parseFloat(m2Price).toFixed(2);
				itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
				
				var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
				
				
				var otherPrice = (Number(shipping) + Number(install1));
				var newNetPrice = (Number(netPrice) + Number(otherPrice));
				margin = parseFloat(margin / 100).toFixed(2);
				var overhead = parseFloat(newNetPrice * margin);
				var salesPrice = Math.round(overhead + newNetPrice);
				var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
				$(".MPrice").val(salesPrice).css("font-weight","bold");
				$(".Total").val(totalQtyPrice).css("font-weight","bold");
			
			}	
			
		} 

		});				
		
	$(".mSqPrice").keyup(function(){
		
		var m2Price = $(this).val();
		var itemMSquaer = $(".itemMSq").val();
		var shipping = $(".shipping").val();
		var install1 = $(".Installation").val();
		var margin = $(".margin").val();
		
			if(itemMSquaer != '' || itemMSquaer != 0)
			{
				var QTYForTotal = $(".itemQty").val();
				m2Price = parseFloat(m2Price).toFixed(2);
				itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
				
				var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
				
				
				var otherPrice = (Number(shipping) + Number(install1));
				var newNetPrice = (Number(netPrice) + Number(otherPrice));
				margin = parseFloat(margin / 100).toFixed(2);
				var overhead = parseFloat(newNetPrice * margin);
				var salesPrice = Math.round(overhead + newNetPrice);
				var totalQtyPrice = Math.round(QTYForTotal * salesPrice);
				$(".MPrice").val(salesPrice).css("font-weight","bold");
				$(".Total").val(totalQtyPrice).css("font-weight","bold");
			
			}
		});
		
	$(".itemQty").keyup(function(){
		
		var itemQTY = $(this).val();
		
		var netItemPrice = $(".MPrice").val();
		var itemMSquaer = $(".itemMSq").val();
		var m2Price = $(".mSqPrice").val();
		
		var shipping2 = $(".shipping").val();
		var install2 = $(".Installation").val();
		var margin2 = $(".margin").val();
		
		if(netItemPrice != '' || netItemPrice != 0 && itemMSquaer != '' || itemMSquaer != 0)
			{
				m2Price = parseFloat(m2Price).toFixed(2);
				itemMSquaer = parseFloat(itemMSquaer).toFixed(2);
				var netPrice = parseFloat(m2Price * itemMSquaer).toFixed(1);
				
				//netItemPrice = parseFloat(netItemPrice).toFixed(2);
				
				var otherPrice = (Number(shipping2) + Number(install2));
				var newNetPrice = (Number(netPrice) + Number(otherPrice));
				margin2 = parseFloat(margin2 / 100).toFixed(2);
				var overhead = Math.round(newNetPrice * margin2);
				var salesPrice = parseFloat(overhead + newNetPrice);
				
				var totalPrice = Math.round(itemQTY * salesPrice);
				
				$(".Total").val(totalPrice).css("font-weight","bold");
				$(".MPrice").val(salesPrice).css("font-weight","bold");
				
				
			}
		
		});
		
		
	$(".descQty").keyup(function(){
		
		var hardwaareQTY = $(this).val();
		
		var netPriceWH = $(".descPrice").val();
			if(netPriceWH != '' )
			{
				hardwaareQTY = Number(hardwaareQTY);
				netPriceWH = Number(netPriceWH);
				netPriceWH = Math.round(netPriceWH);
				
				var totalPriceHW = parseFloat(hardwaareQTY * netPriceWH).toFixed(1);
				
				$(".totalPrice").val(totalPriceHW).css("font-weight","bold");
			}
		
		});	
		
	$(".descPrice").keyup(function(){
		
		var netPriceWH1 = $(this).val();
		
		var hardwaareQTY1 = $(".descQty").val();
			if(hardwaareQTY1 != '' || hardwaareQTY1 != 0)
			{
				hardwaareQTY1 = Number(hardwaareQTY1);
				netPriceWH1 = Number(netPriceWH1);
				netPriceWH1 = Math.round(netPriceWH1);
				
				var totalPriceHW1 = parseFloat(hardwaareQTY1 * netPriceWH1).toFixed(1);
				
				$(".totalPrice").val(totalPriceHW1).css("font-weight","bold");
			}
		
		});						
		
		$("#EditItemOfferBtn").click(function(){
			
			var PremissionAdd = $(".UserPermiss").val();
			var itemJobRowId = $("#jRowId").val();
			var itemCustCode = $("#CustCode").val();
			var itemCustName = $("#CustName").val();
			var itemType = $(".itemType").val();
			itemType = itemType.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var itemName = $(".itemName").val();
			itemName = itemName.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var itemHights = $(".itemHight").val();
			itemHights = parseFloat(itemHights).toFixed(1);
			var itemWidth = $(".itemWidth").val();
			itemWidth = parseFloat(itemWidth).toFixed(1);
			var itemM2 = $(".itemMSq").val();
			itemM2 = parseFloat(itemM2).toFixed(1);
			var itemM2Price = $(".mSqPrice").val();
			itemM2Price = parseFloat(itemM2Price).toFixed(1);
			var itemQuantity = $(".itemQty").val();
			var itemTotalPrice = $(".Total").val();
			var itemDepth = $(".itemDepth").val();
			var itemFRMin = $(".FRMin").val();
			var itemRemarks = $(".Remarks").val();
			itemRemarks = itemRemarks.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var itemOverlap = $(".Overlap").val();
			itemOverlap = itemOverlap.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var itemMargin = $(".margin").val();
			var itemShip = $(".shipping").val();
			var itemInstall = $(".Installation").val();
			var Handling = $(".Handl").val();
			Handling = Handling.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var doorNumber = $(".DoorNum").val();
			doorNumber = doorNumber.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var doorRal = $(".ral").val();
			doorRal = doorRal.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			
			if(itemType == "" || itemType == null )
			{
			alert('missing field');
			$('.itemType').css("border-color","red");
			setTimeout(function(){
           		$('.itemType').css("border-color","#EBEBEB");
				$(".itemType").focus();				
				}, 1500);
								
			}
			else if(itemName == "" || itemName == null )
			{
			alert('missing field');
			$('.itemName').css("border-color","red");
			setTimeout(function(){
           		$('.itemName').css("border-color","#EBEBEB");
				$(".itemName").focus();				
				}, 1500);
								
			}
			else if(itemHights == "" || itemHights == null )
			{
			alert('missing field');
			$('.itemHight').css("border-color","red");
			setTimeout(function(){
           		$('.itemHight').css("border-color","#EBEBEB");
				$(".itemHight").focus();				
				}, 1500);
								
			}
			else if(itemWidth == "" || itemWidth == null )
			{
			alert('missing field');
			$('.itemWidth').css("border-color","red");
			setTimeout(function(){
           		$('.itemWidth').css("border-color","#EBEBEB");
				$(".itemWidth").focus();				
				}, 1500);
								
			}
			else if(itemDepth == "" || itemDepth == null )
			{
			alert('missing field');
			$('.itemDepth').css("border-color","red");
			setTimeout(function(){
           		$('.itemDepth').css("border-color","#EBEBEB");
				$(".itemDepth").focus();				
				}, 1500);
								
			}
			else if(itemM2Price == "" || itemM2Price == null )
			{
			alert('missing field');
			$('.mSqPrice').css("border-color","red");
			setTimeout(function(){
           		$('.mSqPrice').css("border-color","#EBEBEB");
				$(".mSqPrice").focus();				
				}, 1500);
								
			}
			else if(itemQuantity == "" || itemQuantity == null )
			{
			alert('missing field');
			$('.itemQty').css("border-color","red");
			setTimeout(function(){
           		$('.itemQty').css("border-color","#EBEBEB");
				$(".itemQty").focus();				
				}, 1500);
								
			}
			
			/*else if(Handling == "" || Handling == null )
			{
			alert('missing field');
			$('.Handl').css("border-color","red");
			setTimeout(function(){
           		$('.Handl').css("border-color","#EBEBEB");
				$(".Handl").focus();				
				}, 1500);
								
			}
			else if(doorNumber == "" || doorNumber == null )
			{
			alert('missing field');
			$('.DoorNum').css("border-color","red");
			setTimeout(function(){
           		$('.DoorNum').css("border-color","#EBEBEB");
				$(".DoorNum").focus();				
				}, 1500);
								
			}*/
			else if(itemFRMin == "" || itemFRMin == null )
			{
			alert('missing field');
			$('.FRMin').css("border-color","red");
			setTimeout(function(){
           		$('.FRMin').css("border-color","#EBEBEB");
				$(".FRMin").focus();				
				}, 1500);
								
			}
			else if(itemRemarks == "" || itemRemarks == null )
			{
			alert('missing field');
			$('.Remarks').css("border-color","red");
			setTimeout(function(){
           		$('.Remarks').css("border-color","#EBEBEB");
				$(".Remarks").focus();				
				}, 1500);
								
			}
			/*else if(doorRal == "" || doorRal == null )
			{
			alert('missing field');
			$('.ral').css("border-color","red");
			setTimeout(function(){
           		$('.ral').css("border-color","#EBEBEB");
				$(".ral").focus();				
				}, 1500);
								
			}
			else if(itemOverlap == "" || itemOverlap == null )
			{
			alert('missing field');
			$('.Overlap').css("border-color","red");
			setTimeout(function(){
           		$('.Overlap').css("border-color","#EBEBEB");
				$(".Overlap").focus();				
				}, 1500);
								
			}
			*/	
			else
			{
				if(PremissionAdd == "Admin"  || PremissionAdd == "Manager" )
				{
					if( itemMargin == 0 || itemMargin == "")
					{
					alert('missing field');
					$('.margin').css("border-color","red");
					setTimeout(function(){
						$('.margin').css("border-color","#EBEBEB");
						$(".margin").focus();				
						}, 1500);
					}
					else if(itemShip == 0 || itemShip == "")
					{
					alert('missing field');
					$('.shipping').css("border-color","red");
					setTimeout(function(){
						$('.shipping').css("border-color","#EBEBEB");
						$(".shipping").focus();				
						}, 1500);
					}
					else if(itemInstall == 0 || itemInstall == "")
					{
					alert('missing field');
					$('.Installation').css("border-color","red");
					setTimeout(function(){
						$('.Installation').css("border-color","#EBEBEB");
						$(".Installation").focus();				
						}, 1500);
					}
					
					else
					{
						//alert(itemMargin);
						$.ajax({
						
					url:"dist/php/saveDoorsOfferData.php",
					type:"POST",
					data:{tRowId:itemJobRowId, jCustCode:itemCustCode, jCustname:itemCustName,jItemName:itemName,jItemH:itemHights, jItemW:itemWidth, jItem2:itemM2, jM2Price:itemM2Price, jItemQty:itemQuantity, jItemTotalPrice:itemTotalPrice,jItemDepth:itemDepth,jItemFRMin:itemFRMin,jItemRemk:itemRemarks,jItemOverlap:itemOverlap,jItemType:itemType,jMargin:itemMargin,jInstall:itemInstall,jShipping:itemShip,handlDir:Handling,doorNo:doorNumber,jItemRal:doorRal},
					beforeSend: function(){
						$("#EditItemOfferBtn").prop('disabled', true);
						
					},
					success: function(doneAddItem)
					{
						if(doneAddItem == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#EditItemOfferBtn").prop('disabled', false);
							$('#itemType').css("border-color","red");
							setTimeout(function(){
								$('#itemType').css("border-color","#EBEBEB");
								$('#itemType').focus();				
							}, 1500);
						}
						else if(doneAddItem == 1)
						{
							alert("Data Saved");
							$("#EditItemOfferBtn").hide();
							$(".EditItemOfferTR").hide();
							$(".oldAddItems").hide();
							setTimeout(function(){				
								$("#EditItemOfferBtn").prop('disabled', false);
							}, 1500);
							$(".fristForm").prop("readonly", true);
							$(".selectedHW").show();
							$(".AssignAsKit").show();
							$(".AssignHWRef").show();
							
							setTimeout(function(){				
								$("#addItemHWBtn").prop('disabled', false);
								$(".addMoreItemToOfferTR").show();
								$("#addMoreItemToOfferBtn").show();
							}, 1500);
							
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:itemJobRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							
							$.ajax({
				
								url:"dist/php/getItemRef.php",
								type:"POST",
								data:{refJobRId:itemJobRowId, refiItemName:itemName},
								dataType: "json",
								cache: false,
								success: function(showItemIDandREF){
									
									$(".itemRef").html(showItemIDandREF.itemRef);
									$(".itemNewRowId").val(showItemIDandREF.itemRowId);
	
								}
							});
							//$(".itemRef").load("dist/php/getItemRef.php", {refJobRId:itemJobRowId, refiItemName:itemName});
						}
						else
						{
							alert(doneAddItem);
							$("#EditItemOfferBtn").prop('disabled', false);
						}
					}
					
					
					});
					}
				}
				else
				{
				//alert(itemMargin);
				$.ajax({
						
					url:"dist/php/saveDoorsOfferData.php",
					type:"POST",
					data:{tRowId:itemJobRowId, jCustCode:itemCustCode, jCustname:itemCustName,jItemName:itemName,jItemH:itemHights, jItemW:itemWidth, jItem2:itemM2, jM2Price:itemM2Price, jItemQty:itemQuantity, jItemTotalPrice:itemTotalPrice,jItemDepth:itemDepth,jItemFRMin:itemFRMin,jItemRemk:itemRemarks,jItemOverlap:itemOverlap,jItemType:itemType,jMargin:itemMargin,jInstall:itemInstall,jShipping:itemShip,handlDir:Handling,doorNo:doorNumber,jItemRal:doorRal},
					beforeSend: function(){
						$("#EditItemOfferBtn").prop('disabled', true);
						
					},
					success: function(doneAddItem)
					{
						if(doneAddItem == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#EditItemOfferBtn").prop('disabled', false);
							$('#itemType').css("border-color","red");
							setTimeout(function(){
								$('#itemType').css("border-color","#EBEBEB");
								$('#itemType').focus();				
							}, 1500);
						}
						else if(doneAddItem == 1)
						{ 
							alert("Data Saved");
							$("#EditItemOfferBtn").hide();
							$(".EditItemOfferTR").hide();
							$(".oldAddItems").hide();
							setTimeout(function(){				
								$("#EditItemOfferBtn").prop('disabled', false);
								$("#addItemHWBtn").prop('disabled', false);
								$(".addMoreItemToOfferTR").show();
								$("#addMoreItemToOfferBtn").show();
							}, 1500);
							
							$(".fristForm").prop("readonly", true);
							$(".selectedHW").show();
							$(".AssignAsKit").show();
							$(".AssignHWRef").show();
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:itemJobRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							
							$.ajax({
				
								url:"dist/php/getItemRef.php",
								type:"POST",
								data:{refJobRId:itemJobRowId, refiItemName:itemName},
								dataType: "json",
								cache: false,
								success: function(showItemIDandREF){
									
									$(".itemRef").html(showItemIDandREF.itemRef);
									$(".itemNewRowId").val(showItemIDandREF.itemRowId);
	
								}
							});
							//$(".itemRef").load("dist/php/getItemRef.php", {refJobRId:itemJobRowId, refiItemName:itemName});
						}
						else
						{
							alert(doneAddItem);
							$("#EditItemOfferBtn").prop('disabled', false);
						}
					}
					
					
					});
				}
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
		   $(".descPrice").val('');	
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
				 $(".descPrice").val(showPNData.ItemPrice);
				 $(".descPrice").prop("readonly", true);
				
				 
				 
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
		   $(".descPrice").val('');
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
			$(".descPrice").val(showDescripData.ItemPrice);
			$(".descPrice").prop("readonly", true);
			}
			
			
			});
		
	}
}
else if(selectedDescrip == "")
{
	 $("#partNo").val("");
}
});//Description
		
	
	$("#addItemHWBtn").click(function(){
	
	var PremissionAdd3 = $(".UserPermiss").val();	
	var requItemName = $(".itemName").val();	
	var requItemType = $(".itemType").val();	
	var requItemRID = $(".itemNewRowId").val();	
		var data = {};
			$("#showAllItems option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#showAllItems option").val());
	var DecripCode = $("#ItemName").val();
	var Description = $('#showAllItems [value="' + DecripCode + '"]').data('value');
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
	else if(RequQTY == "" || RequQTY == 0)
	{
		alert('Please add qty 0 value not accepted');
		$("#descQty").css("border-color","red");
		setTimeout(function(){
		   $("#descQty").css("border-color","#EBEBEB");    						
		   $("#descQty").focus();							
		}, 1500);
	}
	else
	{
		if(PremissionAdd3 == "Admin"  || PremissionAdd3 == "Manager" )
		{
			if(requItemPrice == "" || requItemPrice == 0)
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
				
				url:"dist/php/saveAddHWToItem.php",
				type:"POST",
				data:{HWname:DecripCode,HWCode:Description,HWPartNo:partNumber,HWQTY:RequQTY,HWPrice:requItemPrice,HWItemTPrice:requTotalPrice,HWJobId:requJobRowId,HWCustCode:requCustCode,HWCustName:requCustName,hwItemName:requItemName,hwItemtype:requItemType,HWItemRowId:requItemRID},
				beforeSend: function(){
						$("#addItemHWBtn").prop('disabled', true);
						
					},
					success: function(doneAddHWToItem)
					{
						if(doneAddHWToItem == 0)
						{
							alert("Hardware selected Is Already existing in this Item.!");
							$("#addItemHWBtn").prop('disabled', false);
							$("#partNo").css("border-color","red");
							$('#itemName').css("border-color","red");
							setTimeout(function(){
								$('#itemName').css("border-color","#EBEBEB");
								 $("#partNo").css("border-color","#EBEBEB");    
								$('#itemName').focus();				
							}, 1500);
						}
						else if(doneAddHWToItem == 1)
						{
							alert("Data Saved");
							$("#EditItemOfferBtn").hide();
							setTimeout(function(){				
								$("#addItemHWBtn").prop('disabled', false);
								$(".addMoreItemToOfferTR").show();
								$("#addMoreItemToOfferBtn").show();
							}, 1500);
							$(".sndForm").val("");
							$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:requJobRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
							
							$(".HWadded").html("");
							$(".HWadded").show("");
							$(".HWadded").load("dist/php/showAllAddHWtoItem.php",{tableJobId:requJobRowId,tableItemName:requItemName,tableItemType:requItemType, tableItemRowId:requItemRID});
							
						}
						else
						{
							alert(doneAddHWToItem);
							$("#addItemHWBtn").prop('disabled', false);
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
				data:{HWname:DecripCode,HWCode:Description,HWPartNo:partNumber,HWQTY:RequQTY,HWPrice:requItemPrice,HWItemTPrice:requTotalPrice,HWJobId:requJobRowId,HWCustCode:requCustCode,HWCustName:requCustName,hwItemName:requItemName,hwItemtype:requItemType,HWItemRowId:requItemRID},
				beforeSend: function(){
						$("#addItemHWBtn").prop('disabled', true);
						
					},
					success: function(doneAddHWToItem)
					{
						if(doneAddHWToItem == 0)
						{
							alert("Hardware selected Is Already existing in this Item.!");
							$("#addItemHWBtn").prop('disabled', false);
							$("#partNo").css("border-color","red");
							$('#itemName').css("border-color","red");
							setTimeout(function(){
								$('#itemName').css("border-color","#EBEBEB");
								 $("#partNo").css("border-color","#EBEBEB");    
								$('#itemName').focus();				
							}, 1500);
						}
						else if(doneAddHWToItem == 1)
						{
							alert("Data Saved");
							$("#EditItemOfferBtn").hide();
							setTimeout(function(){				
								$("#addItemHWBtn").prop('disabled', false);
								$(".addMoreItemToOfferTR").show();
								$("#addMoreItemToOfferBtn").show();
							}, 1500);
							$(".sndForm").val("");
							$(".TotalOffer").html('');
							
							$.ajax({
									url:"dist/php/loadTotalOffer.php",
									type:"POST",
									data:{TotalJobRID:requJobRowId},
									success: function(showOfferTotal){
										$(".TotalOffer").html(showOfferTotal);
									}
								});
							
							$(".HWadded").html("");
							$(".HWadded").show("");
							$(".HWadded").load("dist/php/showAllAddHWtoItem.php",{tableJobId:requJobRowId,tableItemName:requItemName,tableItemType:requItemType, tableItemRowId:requItemRID});
							
						}
						else
						{
							alert(doneAddHWToItem);
							$("#addItemHWBtn").prop('disabled', false);
						}
					}
			
			});

		}
	}
	
	
		
		return false;
		});
		
	$("#addMoreItemToOfferBtn").click(function(){
		var oldItemJobAddNew = $("#jRowId").val();
		
		$(".fristForm").val("");
		$(".sndForm").val("");
		$(".selectedHW").hide("");
		$(".HWadded").hide("");
		$("#addMoreItemToOfferBtn").hide("");
		$(".addMoreItemToOfferTR").hide("");
		$("#EditItemOfferBtn").show();
		$(".EditItemOfferTR").show();
		//$(".addHWTableCalss").show();
		$(".fristForm").prop("readonly", false);
		$(".itemMSq").prop("readonly", true);
		$(".MPrice").prop("readonly", true);
		$(".Total").prop("readonly", true);
		$(".oldAddItems").show();
		$(".oldAddItems").html('');
		$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:oldItemJobAddNew});
		$(".TotalOffer").html('');
		$(".Installation").val(0);
		$(".shipping").val(0);
		$(".margin").val(0);
		$(".Total").val(0);
							
			$.ajax({
					url:"dist/php/loadTotalOffer.php",
					type:"POST",
					data:{TotalJobRID:oldItemJobAddNew},
					success: function(showOfferTotal){
						$(".TotalOffer").html(showOfferTotal);
					}
				});
		
		return false;
		});	
		
	$("#EditItemInOfferTR").click(function(){
		
		var PremissionAdd2 = $(".UserPermiss").val();
		
			var itemJobRowId2 = $("#jRowId").val();
			var itemRowIdEdit = $("#rowIdItemForEdit").val();
			var itemCustCode2 = $("#CustCode").val();
			var itemCustName2 = $("#CustName").val();
			var itemType2 = $(".itemType").val();
			itemType2 = itemType2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var itemName2 = $(".itemName").val();
			itemName2 = itemName2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var itemHights2 = $(".itemHight").val();
			itemHights2 = parseFloat(itemHights2).toFixed(1);
			var itemWidth2 = $(".itemWidth").val();
			itemWidth2 = parseFloat(itemWidth2).toFixed(1);
			var itemM2_2 = $(".itemMSq").val();
			itemM2_2 = parseFloat(itemM2_2).toFixed(1);
			var itemM2Price2 = $(".mSqPrice").val();
			itemM2Price2 = parseFloat(itemM2Price2).toFixed(1);
			var itemQuantity2 = $(".itemQty").val();
			var itemTotalPrice2 = $(".Total").val();
			var itemDepth2 = $(".itemDepth").val();
			var itemFRMin2 = $(".FRMin").val();
			var itemRemarks2 = $(".Remarks").val();
			var itemOverlap2 = $(".Overlap").val();
			var itemMargin2 = $(".margin").val();
			var itemShip2 = $(".shipping").val();
			var itemInstall2 = $(".Installation").val();
			var Handling2 = $(".Handl").val();
			var doorNumber2 = $(".DoorNum").val();
			var doorRal2 = $(".ral").val();
			doorRal2 = doorRal2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			
			if(itemType2 == "" || itemType2 == null )
			{
			alert('missing field');
			$('.itemType').css("border-color","red");
			setTimeout(function(){
           		$('.itemType').css("border-color","#EBEBEB");
				$(".itemType").focus();				
				}, 1500);
								
			}
			else if(itemName2 == "" || itemName2 == null )
			{
			alert('missing field');
			$('.itemName').css("border-color","red");
			setTimeout(function(){
           		$('.itemName').css("border-color","#EBEBEB");
				$(".itemName").focus();				
				}, 1500);
								
			}
			else if(itemHights2 == "" || itemHights2 == null )
			{
			alert('missing field');
			$('.itemHight').css("border-color","red");
			setTimeout(function(){
           		$('.itemHight').css("border-color","#EBEBEB");
				$(".itemHight").focus();				
				}, 1500);
								
			}
			else if(itemWidth2 == "" || itemWidth2 == null )
			{
			alert('missing field');
			$('.itemWidth').css("border-color","red");
			setTimeout(function(){
           		$('.itemWidth').css("border-color","#EBEBEB");
				$(".itemWidth").focus();				
				}, 1500);
								
			}
			else if(itemDepth2 == "" || itemDepth2 == null )
			{
			alert('missing field');
			$('.itemDepth').css("border-color","red");
			setTimeout(function(){
           		$('.itemDepth').css("border-color","#EBEBEB");
				$(".itemDepth").focus();				
				}, 1500);
								
			}
			else if(itemM2Price2 == "" || itemM2Price2 == null )
			{
			alert('missing field');
			$('.mSqPrice').css("border-color","red");
			setTimeout(function(){
           		$('.mSqPrice').css("border-color","#EBEBEB");
				$(".mSqPrice").focus();				
				}, 1500);
								
			}
			else if(itemQuantity2 == "" || itemQuantity2 == null )
			{
			alert('missing field');
			$('.itemQty').css("border-color","red");
			setTimeout(function(){
           		$('.itemQty').css("border-color","#EBEBEB");
				$(".itemQty").focus();				
				}, 1500);
								
			}
			/*else if(Handling2 == "" || Handling2 == null )
			{
			alert('missing field');
			$('.Handl').css("border-color","red");
			setTimeout(function(){
           		$('.Handl').css("border-color","#EBEBEB");
				$(".Handl").focus();				
				}, 1500);
								
			}
			else if(doorNumber2 == "" || doorNumber2 == null )
			{
			alert('missing field');
			$('.DoorNum').css("border-color","red");
			setTimeout(function(){
           		$('.DoorNum').css("border-color","#EBEBEB");
				$(".DoorNum").focus();				
				}, 1500);
								
			}*/
			else if(itemFRMin2 == "" || itemFRMin2 == null )
			{
			alert('missing field');
			$('.FRMin').css("border-color","red");
			setTimeout(function(){
           		$('.FRMin').css("border-color","#EBEBEB");
				$(".FRMin").focus();				
				}, 1500);
								
			}
			else if(itemRemarks2 == "" || itemRemarks2 == null )
			{
			alert('missing field');
			$('.Remarks').css("border-color","red");
			setTimeout(function(){
           		$('.Remarks').css("border-color","#EBEBEB");
				$(".Remarks").focus();				
				}, 1500);
								
			}
			/*else if(doorRal2 == "" || doorRal2 == null )
			{
			alert('missing field');
			$('.ral').css("border-color","red");
			setTimeout(function(){
           		$('.ral').css("border-color","#EBEBEB");
				$(".ral").focus();				
				}, 1500);
								
			}
			else if(itemOverlap2 == "" || itemOverlap2 == null )
			{
			alert('missing field');
			$('.Overlap').css("border-color","red");
			setTimeout(function(){
           		$('.Overlap').css("border-color","#EBEBEB");
				$(".Overlap").focus();				
				}, 1500);
								
			}*/
			else
			{
				
				if(PremissionAdd2 == "Admin"  || PremissionAdd2 == "Manager" )
				{
					if( itemMargin2 == 0 || itemMargin2 == "")
					{
					alert('missing field');
					$('.margin').css("border-color","red");
					setTimeout(function(){
						$('.margin').css("border-color","#EBEBEB");
						$(".margin").focus();				
						}, 1500);
					}
					else if(itemShip2 == 0 || itemShip2 == "")
					{
					alert('missing field');
					$('.shipping').css("border-color","red");
					setTimeout(function(){
						$('.shipping').css("border-color","#EBEBEB");
						$(".shipping").focus();				
						}, 1500);
					}
					else if(itemInstall2 == 0 || itemInstall2 == "")
					{
					alert('missing field');
					$('.Installation').css("border-color","red");
					setTimeout(function(){
						$('.Installation').css("border-color","#EBEBEB");
						$(".Installation").focus();				
						}, 1500);
					}
					
					else
					{
						$.ajax({
						
					url:"dist/php/saveEditDoorsOfferData.php",
					type:"POST",
					data:{ItemTableId:itemRowIdEdit,tRowId:itemJobRowId2, jCustCode:itemCustCode2, jCustname:itemCustName2,jItemName:itemName2,jItemH:itemHights2, jItemW:itemWidth2, jItem2:itemM2_2, jM2Price:itemM2Price2, jItemQty:itemQuantity2, jItemTotalPrice:itemTotalPrice2,jItemDepth:itemDepth2,jItemFRMin:itemFRMin2,jItemRemk:itemRemarks2,jItemOverlap:itemOverlap2,jItemType:itemType2,jMargin:itemMargin2,jInstall:itemInstall2,jShipping:itemShip2,handlDir:Handling2,doorNo:doorNumber2,jItemRal:doorRal2},
					beforeSend: function(){
						$("#EditItemInOfferTR").prop('disabled', true);
						
					},
					success: function(doneEditItem)
					{
						if(doneEditItem == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#EditItemInOfferTR").prop('disabled', false);
							$('#itemType').css("border-color","red");
							setTimeout(function(){
								$('#itemType').css("border-color","#EBEBEB");
								$('#itemType').focus();				
							}, 1500);
						}
						else if(doneEditItem == 1)
						{
							alert("Data Saved");
							$(".fristForm").val("");
							$("#EditItemOfferBtn").hide();
							$(".EditItemOfferTR").hide();
							$("#EditItemInOfferTR").hide();
							$(".EditItemInOfferTR").hide();
							$(".backBTN2").hide();
							
							$(".backBTN").show();
							$("#EditItemOfferBtn").show();
							$(".EditItemOfferTR").show();
							$(".oldAddItems").show();
							$(".oldAddItems").html('');
							setTimeout(function(){				
								$("#EditItemInOfferTR").prop('disabled', false);
							}, 1500);
							//$(".fristForm").val('');
							//$(".selectedHW").show();
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:itemJobRowId2},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							$.ajax({
				
								url:"dist/php/getItemRef.php",
								type:"POST",
								data:{refJobRId:itemJobRowId2, refiItemName:itemName2},
								dataType: "json",
								cache: false,
								success: function(showItemIDandREF){
									
									$(".itemRef").html(showItemIDandREF.itemRef);
									$(".itemNewRowId").val(showItemIDandREF.itemRowId);
	
								}
							});
							
							$(".oldAddItems").html('');
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:itemJobRowId2});
							$(".Installation").val(0);
							$(".shipping").val(0);
							$(".margin").val(0);
							$(".Total").val(0);
							
						}
						else
						{
							alert(doneEditItem);
							$("#EditItemInOfferTR").prop('disabled', false);
						}
					}
					
					
					});
					}
				}
				else
				{
					$.ajax({
						
					url:"dist/php/saveEditDoorsOfferData.php",
					type:"POST",
					data:{ItemTableId:itemRowIdEdit,tRowId:itemJobRowId2, jCustCode:itemCustCode2, jCustname:itemCustName2,jItemName:itemName2,jItemH:itemHights2, jItemW:itemWidth2, jItem2:itemM2_2, jM2Price:itemM2Price2, jItemQty:itemQuantity2, jItemTotalPrice:itemTotalPrice2,jItemDepth:itemDepth2,jItemFRMin:itemFRMin2,jItemRemk:itemRemarks2,jItemOverlap:itemOverlap2,jItemType:itemType2,jMargin:itemMargin2,jInstall:itemInstall2,jShipping:itemShip2,handlDir:Handling2,doorNo:doorNumber2,jItemRal:doorRal2},
					beforeSend: function(){
						$("#EditItemInOfferTR").prop('disabled', true);
						
					},
					success: function(doneEditItem)
					{
						if(doneEditItem == 0)
						{
							alert("Item Name Is Already existing in this Offer.!");
							$("#EditItemInOfferTR").prop('disabled', false);
							$('#itemName').css("border-color","red");
							setTimeout(function(){
								$('#itemName').css("border-color","#EBEBEB");
								$('#itemName').focus();				
							}, 1500);
						}
						else if(doneEditItem == 1)
						{
							alert("Data Saved");
							$(".fristForm").val("");
							$("#EditItemOfferBtn").hide();
							$(".EditItemOfferTR").hide();
							$("#EditItemInOfferTR").hide();
							$(".EditItemInOfferTR").hide();
							$(".backBTN2").hide();
							
							$(".backBTN").show();
							$("#EditItemOfferBtn").show();
							$(".EditItemOfferTR").show();
							$(".oldAddItems").show();
							$(".oldAddItems").html('');
							setTimeout(function(){				
								$("#EditItemInOfferTR").prop('disabled', false);
							}, 1500);
							//$(".fristForm").val('');
							//$(".selectedHW").show();
							$(".TotalOffer").html('');
							$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:itemJobRowId2},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
							$.ajax({
				
								url:"dist/php/getItemRef.php",
								type:"POST",
								data:{refJobRId:itemJobRowId2, refiItemName:itemName2},
								dataType: "json",
								cache: false,
								success: function(showItemIDandREF){
									
									$(".itemRef").html(showItemIDandREF.itemRef);
									$(".itemNewRowId").val(showItemIDandREF.itemRowId);
	
								}
							});
							
							$(".oldAddItems").html('');
							$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:itemJobRowId2});
							$(".Installation").val(0);
							$(".shipping").val(0);
							$(".margin").val(0);
							$(".Total").val(0);
							
						}
						else
						{
							alert(doneEditItem);
							$("#EditItemInOfferTR").prop('disabled', false);
						}
					}
					
					
					});
				}
			}
		
		return false;
		});
	
	
	$(".backBTN").click(function(){
	
	var Premission2 = $(".UserPermiss").val();
	if(Premission2 == "Admin" || Premission2 == "Manager")
		{
			$('#3_2').click();
		}
		else
		{
			$('#8_2').click();
		}
	//$('.allNewOffers').html('');
	//$('.allNewOffers').load("dist/php/allOffersForEditSales.php");
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	return false; 
	});	
	
	$(".backBTN2").click(function(){
		
		 
	$(".fristForm").val('');
	$(".Installation").val(0);
	$(".shipping").val(0);
	$(".margin").val(0);
	$(".Total").val(0);
	$(".oldAddItems").show();
	$(".backBTN2").hide();
	$(".backBTN").show();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	$(".oldAddItems").html('');
	$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:oldItemJobRID});
	return false; 
	});	
	
	 
	$("#AssignHWRef").click(function(){
		
		$(".selectedHW").hide();
		$(".addHWRefCalss").show();
		$(".addHWRefCalss").html('');
		$(".addHWRefCalss").load("dist/php/getAllAddHWGroup.php",{assignHWGroupJobId:oldItemJobRID});
		return false;
		});
		
	$("#AssignAsKit").click(function(){
		
		$(".selectedHW").hide();
		$(".addHWRefCalss").show();
		$(".addHWRefCalss").html('');
		$(".addHWRefCalss").load("dist/php/getAllAsKitName.php",{assignHWGroupJobId:oldItemJobRID});
		return false;
		});	
			
});