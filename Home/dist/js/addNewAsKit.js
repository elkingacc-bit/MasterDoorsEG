// JavaScript Document
$(document).ready(function(){

"use strict";
	
$("#saveAddNewAsKitBTN").click(function(){
	
	var AsKitName = $("#AsKitName").val();
	
	if(AsKitName == "")
	{
		alert("Please Type Assemply Kit Name");
			$('#AsKitName').css("border-color","red");
			setTimeout(function(){
				$('#AsKitName').css("border-color","#C3C3C3");
				$("#AsKitName").focus();
			 }, 1500);
	}
	else
	{
		$.ajax({
			url:"dist/php/saveNewAsKitName.php",
			type:"POST",
			data:{AsKName:AsKitName},
			beforeSend: function(){
			$("#saveAddNewAsKitBTN").prop("disabled", true);
			},
			success: function(doneAddNewAsKit){
			if(doneAddNewAsKit == 0)
					{
						alert("The Assembly Kit Name Is Already existing in Database.!");
						$("#saveAddNewAsKitBTN").prop('disabled', false);
					}
					else if(doneAddNewAsKit == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveAddNewAsKitBTN").prop('disabled', false);
							$("#AsKit").click();
      					}, 1500);
						
					}
					else if(doneAddNewAsKit == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveAddNewAsKitBTN").prop('disabled', false);
						alert(doneAddNewAsKit);
					}	
				
			}
			
			});
	}
	
	return false;
	});		
	
});// docment.ready function **//Type New Organize Name Is allready Exist.
