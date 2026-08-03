$(document).ready(function(){
	"use strict";
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
	
	$("#addItemForm").submit(function(){
		
		var partNo = $("#partNo").val();
		partNo = partNo.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		var itemName = $("#ItemName").val();
		itemName = itemName.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		var itemDescrip = $("#ItemDesc").val();
		itemDescrip = itemDescrip.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	
	if(itemName == "" || null )
		{
			alert('missing field');
			$('#ItemName').css("border-color","red");
			setTimeout(function(){
           		$('#ItemName').css("border-color","#EBEBEB");
				$('#ItemName').focus();				
				}, 1500);
								
			
		}
		
	else
		{
			
		$.ajax({
			url:"dist/php/addItems.php",
			type:"POST",
			data: new FormData(this),
			contentType: false,
        	cache: false,
   			processData:false,
			beforeSend: function(){
				
				$("#saveItemBtn").prop('disabled', true);
				},
				
			success: function(AddNewItem){
				if(AddNewItem == 0)
					{
						alert("Item Name Is Already existing in Database.!");
						$("#saveItemBtn").prop('disabled', false);
						$('#ItemName').css("border-color","red");
						setTimeout(function(){
							$('#ItemName').css("border-color","#EBEBEB");
							$('#ItemName').focus();				
							}, 1500);
					}
					else if(AddNewItem == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveItemBtn").prop('disabled', false);
      					}, 1500);
						$("#newItem").click();
					}
					else if(AddNewItem == 2)
					{
						alert("Error it is look like not allowed image estension!!");
						$('#ItemPhoto').css("background-color","red");
						$("#saveItemBtn").prop('disabled', false);
						setTimeout(function(){
							$('#ItemPhoto').css("background-color","#EBEBEB");
											
						}, 1500);
					}
					else if(AddNewItem == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveItemBtn").prop('disabled', false);
						alert(AddNewItem);
					}
			}
		});
	}
		return false;
		});

	});// docment.ready function **//
