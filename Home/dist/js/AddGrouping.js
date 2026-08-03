// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#Group").click(function(){
		
		$(".formToAdd").html('');
		$(".formToAdd").load("dist/html/addNewGroup.html");
		
		return false;
		});
		
	$("#SubGroup").click(function(){
		
		$(".formToAdd").html('');
		$(".formToAdd").load("dist/html/addNewSubGroup.html");
		
		return false;
		});
	$("#sSubGroup").click(function(){
		
		$(".formToAdd").html('');
		$(".formToAdd").load("dist/html/addNewSSGroup.html");
		
		return false;
		});		
		
	
});// docment.ready function **//
