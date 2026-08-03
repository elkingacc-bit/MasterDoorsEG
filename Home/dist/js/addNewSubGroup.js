// JavaScript Document
$(document).ready(function(){

"use strict";
	
$("#saveAddNewSubGroupBTN").click(function(){
	
	var SubgroupName = $("#subGroupName").val();
	SubgroupName = SubgroupName.replace(/^\s+|\s+$|\s+(?=\s)/g, "").replace(/[^A-Z0-9]+/ig, " ");

	if(SubgroupName == "")
	{
		alert("Please Type Sub Group Name");
			$('#subGroupName').css("border-color","red");
			setTimeout(function(){
				$('#subGroupName').css("border-color","#C3C3C3");
				$("#subGroupName").focus();
			 }, 1500);
	}
	else
	{
		$.ajax({
			url:"dist/php/saveNewSubGroupName.php",
			type:"POST",
			data:{SGName:SubgroupName},
			beforeSend: function(){
			$("#saveAddNewSubGroupBTN").prop("disabled", true);
			},
			success: function(doneAddNewSubGroup){
			if(doneAddNewSubGroup == 0)
					{
						alert("The Sub Group Name Is Already existing in Database.!");
						$("#saveAddNewSubGroupBTN").prop('disabled', false);
					}
					else if(doneAddNewSubGroup == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveAddNewSubGroupBTN").prop('disabled', false);
							$("#Group").click();
      					}, 1500);
						
					}
					else if(doneAddNewSubGroup == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveAddNewSubGroupBTN").prop('disabled', false);
						alert(doneAddNewSubGroup);
					}	
				
			}
			
			});
	}
	
	return false;
	});		
	
});// docment.ready function **//Type New Organize Name Is allready Exist.
