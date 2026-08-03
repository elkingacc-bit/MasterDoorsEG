// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#GroupBTN").click(function(){
		
		$(".allStock").html("");
		$(".allStock").load("dist/php/allStockForGrouping.php");
		$('.m-0').html('');
		$('.m-0').html('Organize Stock | Grouping');	

	return false;
	});
	
	$("#AsKitBTN").click(function(){
		
		$(".allStock").html("");
		$(".allStock").load("dist/php/allAsKit.php");
		$('.m-0').html('');
	 	$('.m-0').html('Organize Stock | Assembly Kits');	

	return false;
	});
});// docment.ready function **//Type New Organize Name Is allready Exist.
