// JavaScript Document
$(document).ready(function(){

"use strict";

$(".Groupinput").change(function(){
	
	var groupSelected = $(this).val();
	if(groupSelected != "")
	 {
	var dataGSelect = {};
			$(".allGroupName option").each(function(i,el) {  
  			 dataGSelect[$(el).data("value")] = $(el).val();
			});
		console.log(dataGSelect, $(".allGroupName option").val()); 
	 var GCodeSelecte = $('.allGroupName [value="' + groupSelected + '"]').data('value');
	 
	 $(".AsKitNames").val("");
	 $(".filterResult").html("");
	 
	 $(".Groupinput").css("border-color","#0275d8");
	 $('.SubGroupInput').css("border-color","#EBEBEB");
	 $('.SSGroupInput').css("border-color","#EBEBEB");
	 $('.AsKitNames').css("border-color","#EBEBEB");
	 
	 $(".filterResult").load("dist/php/showFilterResult.php",{ref:1,changedCode:GCodeSelecte});
	 
	 
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


$(".SubGroupInput").change(function(){
	
	
	var subGroupSelected = $(this).val();
	if(subGroupSelected != "")
	 {
	var dataSubGSelect = {};
			$(".allSubGroupName option").each(function(i,el) {  
  			 dataSubGSelect[$(el).data("value")] = $(el).val();
			});
		console.log(dataSubGSelect, $(".allSubGroupName option").val()); 
	 var SubGCodeSelecte = $('.allSubGroupName [value="' + subGroupSelected + '"]').data('value');
	 
	 
	 $(".AsKitNames").val("");
	 $(".filterResult").html("");
	 
	 $(".SubGroupInput").css("border-color","#0275d8");
	 $('.Groupinput').css("border-color","#EBEBEB");
	 $('.SSGroupInput').css("border-color","#EBEBEB");
	 $('.AsKitNames').css("border-color","#EBEBEB");
	 
	 $(".filterResult").load("dist/php/showFilterResult.php",{ref:2,changedCode:SubGCodeSelecte}); 
		  $.ajax({
			 	
				url:"dist/php/getSubSGroupRe.php",
				type:"POST",
				data:{gSelected:SubGCodeSelecte},
				success: function(showAllSubSubGroupRelated){
					
					 $(".SSGroupInput").val("");
		 			  $(".allSSGroupName").html("");
					 $(".allSSGroupName").html(showAllSubSubGroupRelated);
					
				}
			 
			 });
	 }
	
});	


$(".SSGroupInput").change(function(){
	
	
	var subSGroupSelected = $(this).val();
	if(subSGroupSelected != "")
	 {
	var dataSubSGSelect = {};
			$(".allSSGroupName option").each(function(i,el) {  
  			 dataSubSGSelect[$(el).data("value")] = $(el).val();
			});
		console.log(dataSubSGSelect, $(".allSSGroupName option").val()); 
	 var SubSGCodeSelecte = $('.allSSGroupName [value="' + subSGroupSelected + '"]').data('value');
	 
	 
	 $(".AsKitNames").val("");
	 $(".filterResult").html("");
	 
	 $(".SSGroupInput").css("border-color","#0275d8");
	 $('.Groupinput').css("border-color","#EBEBEB");
	 $('.SubGroupInput').css("border-color","#EBEBEB");
	 $('.AsKitNames').css("border-color","#EBEBEB");
	 
	 $(".filterResult").load("dist/php/showFilterResult.php",{ref:3,changedCode:SubSGCodeSelecte}); 
	 }
	
});	


$(".AsKitNames").change(function(){
	
	
	var AsKitVal = $(this).val();
	if(AsKitVal != "")
	 {
	var dataAsKitSelect = {};
			$(".AllAsKitList option").each(function(i,el) {  
  			 dataAsKitSelect[$(el).data("value")] = $(el).val();
			});
		console.log(dataAsKitSelect, $(".AllAsKitList option").val()); 
	 var asKitIdSelecte = $('.AllAsKitList [value="' + AsKitVal + '"]').data('value');
	 
	  $(".Groupinput").val("");
	  $(".SubGroupInput").val("");
	  $(".SSGroupInput").val("");
	  
	  $(".AsKitNames").css("border-color","#0275d8");
	  $('.Groupinput').css("border-color","#EBEBEB");
	  $('.SubGroupInput').css("border-color","#EBEBEB");
	  $('.SSGroupInput').css("border-color","#EBEBEB");
	  
	  $(".filterResult").html("");
	  $(".filterResult").load("dist/php/showFilterAsKit.php",{AsKitName:AsKitVal,AsKitRID:asKitIdSelecte}); 
	 }
	
});	


});// docment.ready function **//Type New Organize Name Is allready Exist.
