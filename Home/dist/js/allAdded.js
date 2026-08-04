// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#newItem").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/AddItems.html");
		$('.m-0').html('New Item');
		
		return false;
		});		
		
	$("#newmanufactuer").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/AddManufactuer.html");
		$('.m-0').html('New Manufactuers');
		
		return false;
		});		
		
	$("#newSupplier").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/AddSupplier.html");
		$('.m-0').html('New Suppliers');
		
		return false;
		});		
		
	$("#newStaff").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/AddStaff.html");
		$('.m-0').html('New Staff');
		
		return false;
		});		
		
	$("#grouping").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/OrganizeGroup.html");
		$('.m-0').html('Grouping Organize');
		
		return false;
		});	
		
	$("#AsKit").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/addNewAsKit.html");
		$('.m-0').html('Assembly Kit');
		
		return false;
		});	
		
	$("#newCust").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/addNewCust.html");
		$('.m-0').html('Customers');
		
		return false;
		});
		
	$("#M2Price").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/addNewM2Price.html");
		$('.m-0').html('Doors M<sup>2</sup> Price');
		
		return false;
		});
		
	$("#SFactor").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/addNewSFactor.html");
		$('.m-0').html('Add Sales Factor & Over Cost');
		
		return false;
		});		
		
	$("#addPartner").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/Acc/addNewPartner.html");
		$('.m-0').html('Add New Partner');
		
		return false;
		});	
		
	
});// docment.ready function **//
