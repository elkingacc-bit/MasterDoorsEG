$(document).ready(function(){
	"use strict";

	$('#AllItems').load('dist/php/AllItemsDL.php');
	
	
	$("#Descrip").blur(function(){
		
		var dataLoc = {};
$("#AllItems option").each(function(i,el) {  
   dataLoc[$(el).data("value")] = $(el).val();
});
console.log(dataLoc, $("#AllItems option").val());

var valueLoc = $('#Descrip').val();	
var itemLocation = $('#AllItems [value="' + valueLoc + '"]').data('value');

			if(itemLocation != "")
			{
				$.ajax({
						
						url:"dist/php/itemLoc.php",
						type:"POST",
						data:{locationVal:itemLocation},
						success: function(DoneGetlocation){
							
							DoneGetlocation=DoneGetlocation.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
							$("#localtion").val(DoneGetlocation);
							$("#localtion").css("border-color","green");
						setTimeout(function(){
						$('#localtion').css("border-color","#D9D9D9");	
						}, 3000);	
							
						}
					
					});
			}
		});

	$("#saveQTYBTN").click(function(){
	
	var data = {};
$("#AllItems option").each(function(i,el) {  
   data[$(el).data("value")] = $(el).val();
});
console.log(data, $("#AllItems option").val());

var value = $('#Descrip').val();	
var ItemCode = $('#AllItems [value="' + value + '"]').data('value');		
	
		var itemQTY = $("#ItemsQTY").val();
		var locate = $("#localtion").val();
		locate=locate.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		
		if(ItemCode == undefined || ItemCode == "")
		{
			alert("Please Choose Valid description from list");
						$("#Descrip").css("border-color","red");
						setTimeout(function(){
						$('#Descrip').css("border-color","#D9D9D9");	
						}, 1500);	
		}
		else if(itemQTY == "")
		{
			alert("Please add description Quantity");
						$("#ItemsQTY").css("border-color","red");
						setTimeout(function(){
						$('#ItemsQTY').css("border-color","#D9D9D9");	
						}, 1500);	
		}
		
		else
		{
		//alert(ItemCode);	
			$.ajax({
				
					url:"dist/php/saveAddItemQTY.php",
					type:"POST",
					data:{itemCode:ItemCode,QTY:itemQTY,location:locate},
					beforeSend: function(){
					$("#saveQTYBTN").prop("disabled",true);	
						
					},
					success: function(doneSavedItemQTY){
						
						if(doneSavedItemQTY == 1)
						{
							alert("Successfully added Description Quantity");
							$("#2_2").click();
						}
						else
						{
							alert(doneSavedItemQTY);
							$("#saveQTYBTN").prop("disabled",false);	
						}
						
					}
				});
		}
		
		return false;
		});
});