// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#AsKitNameBtn").click(function(){
		
		//var Nameref = $(this).val();
		
		$(".dataToEdit").html('');
		$(".dataToEdit").load("dist/php/allAsKitNameForEdit.php");
		
		return false;
		});
		
	$("#AsKitItemsBtn").click(function(){
		
		//var SGref = $(this).val();
		$(".dataToEdit").html('');
		$(".dataToEdit").load("dist/php/allAsKitItemsForEdit.php");
		
		return false;
		});
		
	
});// docment.ready function **//
