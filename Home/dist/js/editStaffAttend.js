$(document).ready(function(){
	"use strict";
	
	
	$("#selectedAttDate").change(function(){
		
		var DateSelected = $(this).val();
		
		if(DateSelected != "")
		{ 
			 $.ajax({
				 	url:"dist/php/showAllAttendtoday.php",
					type:"POST",
					data:{DateVal:DateSelected},
					beforeSend: function(){
					$('.editAttend').html('');	
					},
					success: function(ShowAllDateAttend){
						
					$('.editAttend').html(ShowAllDateAttend);		
							
					}
				 
				 
				 }); 
		}
		
		return false; 
		});
	


});// JavaScript Document