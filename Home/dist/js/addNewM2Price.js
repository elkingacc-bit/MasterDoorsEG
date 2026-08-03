// JavaScript Document
$(document).ready(function(){

"use strict";


$("#priceType").change(function(){
	
	var selectedType = $(this).val();
	if(selectedType != "")
	{
		$.ajax({
				
				url:"dist/php/getM2Price.php",
				type:"POST",
				data:{sType:selectedType},
				success: function(showM2Price){
					
					var oldPrice = showM2Price.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
				
					$(".M2PriceVal").val(oldPrice);
				}
			
			});
	}
});
	
	$("#saveAddNewM2PriceBTN").click(function(){
		
		var m2Price = $("#M2PriceVal").val();
		var priceType = $("#priceType").val();
		
		if(priceType == "")
		{
			 alert('missing field');
			$('#priceType').css("border-color","red");
			setTimeout(function(){
           		$('#priceType').css("border-color","#EBEBEB");
				$('#priceType').focus();				
				}, 1500);
		}
		
		else if(m2Price == "")
		{
			 alert('missing field');
			$('#M2PriceVal').css("border-color","red");
			setTimeout(function(){
           		$('#M2PriceVal').css("border-color","#EBEBEB");
				$('#M2PriceVal').focus();				
				}, 1500);
		}
		else
		{
			$.ajax({
				
					url:"dist/php/saveAddNewM2Price.php",
					type:"POST",
					data:{m2PriceAmount:m2Price, pType:priceType},
					beforeSend: function(){
					$("#saveAddNewM2PriceBTN").prop("disabled",true);		
						
					},
					success: function(doneSaveM2Price){
						
						if(doneSaveM2Price == 1)
						{
							alert("data Saved");
							$("#M2Price").click();
						}
						else
						{
							alert(doneSaveM2Price);
						}
						
					}
				
				
				});
		}
		
		return false;
		});	
		
	
});// docment.ready function **//
