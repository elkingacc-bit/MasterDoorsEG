// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
var oldDoorJobRID = $("#jRowId").val();
$(".addedAutoDoor").load("dist/php/allAddedAutoDoors.php", {jobIdAutoD:oldDoorJobRID});

$(".SHType").keyup(function(){

var KeyWord2 = $(this).val();

	if(KeyWord2 != "")
	{
		$.ajax({
			
				url:"dist/php/loadOldAddedAutoType.php",
				type:"POST",
				data:{keyWordSearch2:KeyWord2},
				async: true,
				cache: false,
				success: function(showOldAddedTypes2)
				{
					$("#searchType2").html(showOldAddedTypes2);
				}
				
			});
	}
	else
	{
		$("#searchType2").val('');
		$(".DSpecs").val('');
		$(".MSpecs").val('');
		$(".itemPrice").val('');
	}

});	

$(".SHType").change(function(){
	
	var selectedKeyWord2 = $(this).val();
	
	
	if(selectedKeyWord2 != '')
	{
		var keyWordChosenValideate = $('#searchType2 [value="' + selectedKeyWord2 + '"]');
	
		if(keyWordChosenValideate.length > 0 )
		{
		 var ConfrimloadOldType2 = confirm("Do you want fulfil door data for door Type: "+selectedKeyWord2+"?");
			if(ConfrimloadOldType2 === true)
			{
				var TypeDataFill2 = {};
			$("#searchType2 option").each(function(i,el) {  
  			 TypeDataFill2[$(el).data("value")] = $(el).val();
			});
			console.log(TypeDataFill2, $("#searchType2 option").val());
			var DorTypeVal2 = $("#SHType").val();
			var doorTypeRowId2 = $('#searchType2 [value="' + DorTypeVal2 + '"]').data('value');
				$.ajax({
				
					url:"dist/php/fulfilItemDataAuto.php",
					type:"POST",
					data:{DorTypRID2:doorTypeRowId2},
					dataType: "json",
					cache: false,
					beforeSend: function(){
						
						$("#AddShutterBtn").prop("disabled", true);
						
					},
					success: function(doneFulfilAutoData){
						
						$("#AddShutterBtn").prop("disabled", false);
						
					$('.itemQty').css("border-color","red");
					setTimeout(function(){
						$('.itemQty').css("border-color","#EBEBEB");
						$(".itemQty").focus();				
						}, 3500);	
						 
					$(".SHType").val(doneFulfilAutoData.putItemType2);
					$(".DSpecs").val(doneFulfilAutoData.putDoorSpecs);
					$(".MSpecs").val(doneFulfilAutoData.putMotorSpecs);
					$(".itemPrice").val(doneFulfilAutoData.putDoorPrice);
					}
		
		});

			}
		}
	
	}
	return false;
	});


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
		
		
		$("#AddShutterBtn").click(function(){
			
			var itemJobRowId = $("#jRowId").val();
			var itemCustCode = $("#CustCode").val();
			var itemCustName = $("#CustName").val();
			var SHType = $(".SHType").val();
			SHType = SHType.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var DoorSpecs = $(".DSpecs").val();
			DoorSpecs = DoorSpecs.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var MotorSpecs = $(".MSpecs").val();
			MotorSpecs = MotorSpecs.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var SHPrice = $(".itemPrice").val();
			SHPrice = parseFloat(SHPrice).toFixed(1);
			var Quantity = $(".itemQty").val();
			var itemTotalPrice = $(".Total").val();
			
			if(SHType == "" || SHType == null )
			{
			alert('missing field');
			$('.SHType').css("border-color","red");
			setTimeout(function(){
           		$('.SHType').css("border-color","#EBEBEB");
				$(".SHType").focus();				
				}, 1500);
								
			}
			else if(DoorSpecs == "" || DoorSpecs == null )
			{
			alert('missing field');
			$('.DSpecs').css("border-color","red");
			setTimeout(function(){
           		$('.DSpecs').css("border-color","#EBEBEB");
				$(".DSpecs").focus();				
				}, 1500);
								
			}
			else if(MotorSpecs == "" || MotorSpecs == null )
			{
			alert('missing field');
			$('.MSpecs').css("border-color","red");
			setTimeout(function(){
           		$('.MSpecs').css("border-color","#EBEBEB");
				$(".MSpecs").focus();				
				}, 1500);
								
			}
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
						
					url:"dist/php/saveAutoDoorsOfferData.php",
					type:"POST",
					data:{shRowId:itemJobRowId, shCustCode:itemCustCode, shCustname:itemCustName,shType:SHType,shDSpecs:DoorSpecs, shMSpecs:MotorSpecs, shPrice:SHPrice, shQTY:Quantity, shTPrice:itemTotalPrice},
					beforeSend: function(){
						$("#AddShutterBtn").prop('disabled', true);
						
					},
					success: function(doneAddShutter)
					{
						if(doneAddShutter == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#AddShutterBtn").prop('disabled', false);
							$('#SHType').css("border-color","red");
							setTimeout(function(){
								$('#SHType').css("border-color","#EBEBEB");
								$('#SHType').focus();				
							}, 1500);
						}
						else if(doneAddShutter == 1)
						{
							alert("Data Saved");
								$(".fristForm").val('');
							setTimeout(function(){				
								$("#AddShutterBtn").prop('disabled', false);
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
							$(".addedAutoDoor").html('');
							$(".addedAutoDoor").load("dist/php/allAddedAutoDoors.php", {jobIdAutoD:itemJobRowId});
						}
						else
						{
							alert(doneAddShutter);
							$("#AddShutterBtn").prop('disabled', false);
						}
					}
					
					
					});
			}
			
			return false;
			});
			
			
			
	$("#EditDoorOfferBtn").click(function(){
			
			var itemJobRowId2 = $("#jRowId").val();
			var doorRowId = $("#rowIdDoorForEdit").val();
			var itemCustCode2 = $("#CustCode").val();
			var itemCustName2 = $("#CustName").val();
			var SHType2 = $(".SHType").val();
			SHType2 = SHType2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var DoorSpecs2 = $(".DSpecs").val();
			DoorSpecs2 = DoorSpecs2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var MotorSpecs2 = $(".MSpecs").val();
			MotorSpecs2 = MotorSpecs2.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
			var SHPrice2 = $(".itemPrice").val();
			SHPrice2 = parseFloat(SHPrice2).toFixed(1);
			var Quantity2 = $(".itemQty").val();
			var itemTotalPrice2 = $(".Total").val();
			
			if(SHType2 == "" || SHType2 == null )
			{
			alert('missing field');
			$('.SHType').css("border-color","red");
			setTimeout(function(){
           		$('.SHType').css("border-color","#EBEBEB");
				$(".SHType").focus();				
				}, 1500);
								
			}
			else if(DoorSpecs2 == "" || DoorSpecs2 == null )
			{
			alert('missing field');
			$('.DSpecs').css("border-color","red");
			setTimeout(function(){
           		$('.DSpecs').css("border-color","#EBEBEB");
				$(".DSpecs").focus();				
				}, 1500);
								
			}
			else if(MotorSpecs2 == "" || MotorSpecs2 == null )
			{
			alert('missing field');
			$('.MSpecs').css("border-color","red");
			setTimeout(function(){
           		$('.MSpecs').css("border-color","#EBEBEB");
				$(".MSpecs").focus();				
				}, 1500);
								
			}
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
						
					url:"dist/php/saveEditAutoDoorsOfferData.php",
					type:"POST",
					data:{shRowId2:itemJobRowId2, shCustCode2:itemCustCode2, shCustname2:itemCustName2,shType2:SHType2,shDSpecs2:DoorSpecs2, shMSpecs2:MotorSpecs2, shPrice2:SHPrice2, shQTY2:Quantity2, shTPrice2:itemTotalPrice2, doorRID:doorRowId},
					beforeSend: function(){
						$("#EditDoorOfferBtn").prop('disabled', true);
						
					},
					success: function(doneAddShutter2)
					{
						if(doneAddShutter2 == 0)
						{
							alert("Item Type Is Already existing in this Offer.!");
							$("#EditDoorOfferBtn").prop('disabled', false);
							$('#SHType').css("border-color","red");
							setTimeout(function(){
								$('#SHType').css("border-color","#EBEBEB");
								$('#SHType').focus();				
							}, 1500);
						}
						else if(doneAddShutter2 == 1)
						{
							alert("Data Saved");
								$(".fristForm").val('');
								$(".MSpecs").val('');
								$(".DSpecs").val('');
							setTimeout(function(){				
								$("#EditDoorOfferBtn").prop('disabled', false);
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
							$(".addedAutoDoor").html('');
							$(".addedAutoDoor").show();
							$(".addedAutoDoor").load("dist/php/allAddedAutoDoors.php", {jobIdAutoD:itemJobRowId2});
							$(".AddDoorOfferTR").show();
							$("#AddShutterBtn").show();
							$(".EditDoorOfferTR").hide();
						}
						else
						{
							alert(doneAddShutter2);
							$("#EditDoorOfferBtn").prop('disabled', false);
						}
					}
					
					
					});
			}
			
			return false;
			});		
		
$(".backBTN2").click(function(){
	
	$(".fristForm").val('');
	$(".MSpecs").val('');
	$(".DSpecs").val('');
	$(".addedAutoDoor").show();
	$(".backBTN2").hide();
	$(".backBTN").show();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	return false; 
	});	
});