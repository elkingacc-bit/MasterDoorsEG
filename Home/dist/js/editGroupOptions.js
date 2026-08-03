// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#editGroupBtn").click(function(){
		
		var Gref = $(this).val();
		
		$(".formToEdit").html('');
		$(".formToEdit").load("dist/php/editGroup.php", {Ref:Gref});
		
		return false;
		});
		
	$("#editSubGroupBtn").click(function(){
		
		var SGref = $(this).val();
		$(".formToEdit").html('');
		$(".formToEdit").load("dist/php/editGroup.php", {Ref:SGref});
		
		return false;
		});
	$("#editSSubGroupBtn").click(function(){
		
		var SSGref = $(this).val();
		$(".formToEdit").html('');
		$(".formToEdit").load("dist/php/editGroup.php", {Ref:SSGref});
		
		return false;
		});	
		
	$("#reOrganize").click(function(){
		
		
		$(".formToEdit").html('');
		$(".formToEdit").load("dist/php/reGroupingItems.php");
		
		return false;
		});			
		
	
});// docment.ready function **//
