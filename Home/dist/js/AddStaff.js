// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#AllPosiList").load("dist/php/allPosiDDList.php");
	
	$("#addNewStaffButton").click(function(){
		
		var staffName = $("#staffName").val();
		staffName = staffName.replace(/^\s+|\s+$|\s+(?=\s)/g, "");//.replace(/[^A-Z0-9]+/ig, " ");
		
		var Position = $("#choosePosi").val();
		var DVal = $("#dayVal").val();
	
	 if(Position == "" || Position == null )
		{
			alert('missing field');
			$('#choosePosi').css("border-color","red");
			setTimeout(function(){
           		$('#choosePosi').css("border-color","#EBEBEB");
				$("#choosePosi").focus();				
				}, 1500);
								
			
		}
	else if(staffName == "" || staffName == null )
		{
			alert('missing field');
			$('#staffName').css("border-color","red");
			setTimeout(function(){
           		$('#staffName').css("border-color","#EBEBEB");
				$('#staffName').focus();				
				}, 1500);
		}
		
	else if(DVal == "" || DVal == null )
		{
			alert('missing field');
			$('#dayVal').css("border-color","red");
			setTimeout(function(){
           		$('#dayVal').css("border-color","#EBEBEB");
				$('#dayVal').focus();				
				}, 1500);
		}	
		
	else
		{
			
		$.ajax({
			url:"dist/php/addStaff.php",
			type:"POST",
			data: {StfName:staffName, StfPosition:Position, dayAmount:DVal},
			beforeSend: function(){
				
				$("#addNewStaffButton").prop('disabled', true);
				},
				
			success: function(AddNewStaff){
				if(AddNewStaff == 0)
					{
						alert("Staff Name Is Already existing in Database.!");
						$("#addNewStaffButton").prop('disabled', false);
						$('#staffName').css("border-color","red");
						setTimeout(function(){
							$('#staffName').css("border-color","#EBEBEB");
							$('#staffName').focus();				
							}, 1500);
					}
					else if(AddNewStaff == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#addNewStaffButton").prop('disabled', false);
      					}, 1500);
						$("#newStaff").click();
					}
					
					else if(AddNewStaff == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#addNewStaffButton").prop('disabled', false);
						alert(AddNewStaff);
					}
			}
		});
	}
		return false;
		});

	});// docment.ready function **//
