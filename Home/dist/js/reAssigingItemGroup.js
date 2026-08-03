// JavaScript Document
$(document).ready(function(){

"use strict";

$(document).on('change','.Groupinput',function(){
	
	var groupSelected = $(this).val();
	if(groupSelected != "")
	 {
	var dataGSelect = {};
			$(".allGroupName option").each(function(i,el) {  
  			 dataGSelect[$(el).data("value")] = $(el).val();
			});
		console.log(dataGSelect, $(".allGroupName option").val()); 
	 var GCodeSelecte = $('.allGroupName [value="' + groupSelected + '"]').data('value');
	 
	 
	 
	 
		 $.ajax({
			 	
				url:"dist/php/getSubGroupRe.php",
				type:"POST",
				data:{gSelected:GCodeSelecte},
				success: function(showAllSubGroupRelated){
					
					 $(".SubGroupInput").val("");
					 $(".SSGroupInput").val("");
		 			 $(".allSubGroupName").html("");
					 $(".allSubGroupName").html(showAllSubGroupRelated);
					
				}
			 
			 });
		
	 }
	
});	


$(document).on('change','.SubGroupInput',function(){
	
	
	var subGroupSelected = $(this).val();
	if(subGroupSelected != "")
	 {
	var dataSubGSelect = {};
			$(".allGroupName option").each(function(i,el) {  
  			 dataSubGSelect[$(el).data("value")] = $(el).val();
			});
		console.log(dataSubGSelect, $(".allGroupName option").val()); 
	 var SubGCodeSelecte = $('.allGroupName [value="' + subGroupSelected + '"]').data('value');
	 
	 
		 
		  $.ajax({
			 	
				url:"dist/php/getSubSGroupRe.php",
				type:"POST",
				data:{sGSelected:SubGCodeSelecte},
				success: function(showAllSubSubGroupRelated){
					
					 $(".SSGroupInput").val("");
		 			  $(".allSSGroupName").html("");
					 $(".allSSGroupName").html(showAllSubSubGroupRelated);
					
				}
			 
			 });
	 }
	
});	

	
$("#saveEditItemGroupBTN").click(function(){
	
	var ItemRowId = $("#ItemRowId").val();
	var gName = $(".Groupinput").val();
	var sGName = $(".SubGroupInput").val();
	var sSGName = $(".SSGroupInput").val();
	
	var dataG = {};
			$(".allGroupName option").each(function(i,el) {  
  			 dataG[$(el).data("value")] = $(el).val();
			});
		console.log(dataG, $(".allGroupName option").val()); 
	 var GCode = $('.allGroupName [value="' + gName + '"]').data('value');
	 
	 var dataSG = {};
			$(".allSubGroupName option").each(function(i,el) {  
  			 dataSG[$(el).data("value")] = $(el).val();
			});
		console.log(dataSG, $(".allSubGroupName option").val()); 
	 var SGCode = $('.allSubGroupName [value="' + sGName + '"]').data('value');
	 
	 var dataSSG = {};
			$(".allSSGroupName option").each(function(i,el) {  
  			 dataSSG[$(el).data("value")] = $(el).val();
			});
		console.log(dataSSG, $(".allSSGroupName option").val()); 
	 var SSGCode = $('.allSSGroupName [value="' + sSGName + '"]').data('value');
	 
	 var GChosenValideate = $('.allGroupName [value="' + gName + '"]');
	var SGChosenValideate = $('.allSubGroupName [value="' + sGName + '"]');
	var SSGChosenValideate = $('.allSSGroupName [value="' + sSGName + '"]');
	 
	 if(GChosenValideate.length <= 0)
		{
			alert('Please Choose Valid Group Name form the list');
			$(".Groupinput").css("border-color","red");
			setTimeout(function(){
		       $(".Groupinput").css("border-color","#EBEBEB");    						
			   $(".Groupinput").val('');	
			   $(".Groupinput").focus();							
			}, 1500);
		}
		else if(SGChosenValideate.length <= 0)
		{
			alert('Please Choose Valid S-Group Name form the list');
			$(".SubGroupInput").css("border-color","red");
			setTimeout(function(){
		       $(".SubGroupInput").css("border-color","#EBEBEB");    						
			   $(".SubGroupInput").val('');	
			   $(".SubGroupInput").focus();							
			}, 1500);
		}
		else if(SSGChosenValideate.length <= 0)
		{
			alert('Please Choose Valid S-S-Group Name form the list');
			$(".SSGroupInput").css("border-color","red");
			setTimeout(function(){
		       $(".SSGroupInput").css("border-color","#EBEBEB");    						
			   $(".SSGroupInput").val('');	
			   $(".SSGroupInput").focus();							
			}, 1500);
		}
		
		else
		{
		  $.ajax({
				
			url:"dist/php/saveEditItemGrouping.php",
			type:"POST",
			data:{IRID:ItemRowId,Group:gName,SGroup:sGName,SSGroup:sSGName, GroupCode:GCode, SGroupCode:SGCode, SSGroupCode:SSGCode},
			beforeSend: function(){
				$("#saveEditItemGroupBTN").prop('disabled', true);	
			},
			success: function(doneEditGrouping){
				 if(doneEditGrouping == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveEditItemGroupBTN").prop('disabled', false);
							
      					}, 1500);
						$('.ShowEditForm').html('');
						$(".myModal").modal('toggle');
						
					}
					
					else if(doneEditGrouping == 2)
					{
						
						$('.showToast').show();
						$('.ShowEditForm').html('');
						$(".myModal").modal('toggle');
						setTimeout(function(){
						$('.toast').toast('show');
						}, 500);
					}
					
					else if(doneEditGrouping == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveEditItemGroupBTN").prop('disabled', false);
						alert(doneEditGrouping);
					}
			}
				
				});
		}
		
			
	return false;
	});	


});// docment.ready function **//Type New Organize Name Is allready Exist.
