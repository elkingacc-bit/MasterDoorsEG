// JavaScript Document
$(document).ready(function(){

"use strict";
	
$("#saveAddNewSSGroupBTN").click(function(){
	
	var SSgroupName = $("#SSgroupName").val();
	SSgroupName = SSgroupName.replace(/^\s+|\s+$|\s+(?=\s)/g, "").replace(/[^A-Z0-9]+/ig, " ");
	
	if(SSgroupName == "")
	{
		alert("Please Type Sub Sub Group Name");
			$('#SSgroupName').css("border-color","red");
			setTimeout(function(){
				$('#SSgroupName').css("border-color","#C3C3C3");
				$("#SSgroupName").focus();
			 }, 1500);
	}
	else
	{
		$.ajax({
			url:"dist/php/saveNewSSGroupName.php",
			type:"POST",
			data:{SSGName:SSgroupName},
			beforeSend: function(){
			$("#saveAddNewSSGroupBTN").prop("disabled", true);
			},
			success: function(doneAddNewSSGroup){
			if(doneAddNewSSGroup == 0)
					{
						alert("The Group Name Is Already existing in Database.!");
						$("#saveAddNewSSGroupBTN").prop('disabled', false);
					}
					else if(doneAddNewSSGroup == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveAddNewSSGroupBTN").prop('disabled', false);
							$("#Group").click();
      					}, 1500);
						
					}
					else if(doneAddNewSSGroup == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveAddNewSSGroupBTN").prop('disabled', false);
						alert(doneAddNewSSGroup);
					}	
				
			}
			
			});
	}
	
	return false;
	});		
	
});// docment.ready function **//Type New Organize Name Is allready Exist.
