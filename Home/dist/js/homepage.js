$(document).ready(function() {
	"use strict";
	
	
 $('.chart').load("dist/php/AllWeekPlan.php");
 	
$('#AddNewPlan').click(function () {
       	
	$('.ShowHWData').html('');
    $('.ShowHWData').load("dist/php/installWeekPlan.php");
	$(".myModal").modal('toggle');
				
	return false; 
	});

});

// JavaScript Document