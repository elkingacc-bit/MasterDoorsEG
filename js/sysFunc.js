// JavaScript Document
$(document).ready(function() {
	"use strict";
	var ref = "failPage.html";
		
		$.ajax({
				url:"js/checkSystem.php",
			success: function(system)
			{
				//alert(system);
				if(system == 0)
				{
					window.location.href= ref; 
				}
				else
				{
					$.ajax({
					url:"Home/plugins/system/lib/start/system.php",
					success: function(checkSystemFiles)
					{
						
						if(checkSystemFiles == 0)
						{
							window.location.href= ref; 
						}
				}
			
			});
				}
				
				
			}
			});
		
		
		
		
	});