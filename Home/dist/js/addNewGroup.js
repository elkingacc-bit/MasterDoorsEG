// JavaScript Document
$(document).ready(function(){

"use strict";
	
$("#saveAddNewGroupBTN").click(function(){
	
	var groupName = $("#groupName").val();
	groupName = groupName.replace(/^\s+|\s+$|\s+(?=\s)/g, "").replace(/[^A-Z0-9]+/ig, " ");
	
	if(groupName == "")
	{
		alert("Please Type Group Name");
			$('#groupName').css("border-color","red");
			setTimeout(function(){
				$('#groupName').css("border-color","#C3C3C3");
				$("#groupName").focus();
			 }, 1500);
	}
	else
	{
		$.ajax({
			url:"dist/php/saveNewGroupName.php",
			type:"POST",
			data:{GName:groupName},
			beforeSend: function(){
			$("#saveAddNewGroupBTN").prop("disabled", true);
			},
			success: function(doneAddNewGroup){
			if(doneAddNewGroup == 0)
					{
						alert("The Group Name Is Already existing in Database.!");
						$("#saveAddNewGroupBTN").prop('disabled', false);
					}
					else if(doneAddNewGroup == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveAddNewGroupBTN").prop('disabled', false);
							$("#Group").click();
      					}, 1500);
						
					}
					else if(doneAddNewGroup == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveAddNewGroupBTN").prop('disabled', false);
						alert(doneAddNewGroup);
					}	
				
			}
			
			});
	}
	
	return false;
	});		
	
});// docment.ready function **//Type New Organize Name Is allready Exist.
