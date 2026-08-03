// JavaScript Document
$(document).ready(function(){

"use strict";
	
	
$("#saveCreateNewGroupBTN").click(function(){
	
	var ItemRowId = $("#ItemRowId").val();
	var gName = $("#Group").val();
	var sGName = $("#SubGroup").val();
	var sSGName = $("#SSGroup").val();
	var GChosenValideate = $('#allGroupName [value="' + gName + '"]');
	var SGChosenValideate = $('#allSubGroupName [value="' + sGName + '"]');
	var SSGChosenValideate = $('#allSSGroupName [value="' + sSGName + '"]');
	
	 if(GChosenValideate.length <= 0)
		{
			alert('Please Choose Valid Name form the list');
			$("#Group").css("border-color","red");
			setTimeout(function(){
		       $("#Group").css("border-color","#EBEBEB");    						
			   $("#Group").val('');	
			   $("#Group").focus();							
			}, 1500);
		}
		else if(SGChosenValideate.length <= 0)
		{
			alert('Please Choose Valid Name form the list');
			$("#SubGroup").css("border-color","red");
			setTimeout(function(){
		       $("#SubGroup").css("border-color","#EBEBEB");    						
			   $("#SubGroup").val('');	
			   $("#SubGroup").focus();							
			}, 1500);
		}
		else if(SSGChosenValideate.length <= 0)
		{
			alert('Please Choose Valid Name form the list');
			$("#SSGroup").css("border-color","red");
			setTimeout(function(){
		       $("#SSGroup").css("border-color","#EBEBEB");    						
			   $("#SSGroup").val('');	
			   $("#SSGroup").focus();							
			}, 1500);
		}
		
		else
		{
		  $.ajax({
				
			url:"dist/php/saveCreateGrouping.php",
			type:"POST",
			data:{IRID:ItemRowId,Group:gName,SGroup:sGName,SSGroup:sSGName},
			beforeSend: function(){
				$("#saveCreateNewGroupBTN").prop('disabled', true);	
			},
			success: function(doneCearteGrouping){
				if(doneCearteGrouping == 0)
					{
						alert("Grouping is already created before in Database.!");
						$("#saveCreateNewGroupBTN").prop('disabled', false);
					}
					else if(doneCearteGrouping == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveCreateNewGroupBTN").prop('disabled', false);
							$(".allStock").load("dist/php/allStockForGrouping.php");
      					}, 1500);
						$('.ShowData').html('');
						$(".myModal").modal('toggle');
						
					}
					
					else if(doneCearteGrouping == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveCreateNewGroupBTN").prop('disabled', false);
						alert(doneCearteGrouping);
					}
			}
				
				});
		}
		
			
	return false;
	});	


});// docment.ready function **//Type New Organize Name Is allready Exist.
