// JavaScript Document
$(document).ready(function() {
	"use strict";
		
		$.ajax({
				url:"Home/dist/php/HID.php",
				type:"POST",
			success: function(system)
			{
				$(".HID").html(system);
			}
		});
		
		
	});