// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$("#eidtItem").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editItems.html");
		$('.m-0').html('Edit Item');
		
		return false;
		});		
		
	$("#eidtManufactuer").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editManufactuer.html");
		$('.m-0').html('Edit Manufactuers');
		
		return false;
		});		
		
	$("#eidtSupplier").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editSupplier.html");
		$('.m-0').html('Edit Suppliers');
		
		return false;
		});		
		
	$("#editStaff").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editStaff.html");
		$('.m-0').html('Edit Staff');
		
		return false;
		});		
	
	$("#editGrouping").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editGrouping.html");
		$('.m-0').html('Edit Grouping');
		
		return false;
		});	
		
	$("#editAsKit").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editAsKit.html");
		$('.m-0').html('Edit Assembly Kit');
		
		return false;
		});	
			
	$("#editCust").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editCustomer.html");
		$('.m-0').html('Edit Customers');
		
		return false;
		});	
		
	$("#editEditSF").click(function(){
		
		$(".addDiv").html('');
		$(".addDiv").load("dist/html/editSFactor.html");
		$('.m-0').html('Edit Sales Factor & Over Cost');
		
		return false;
		});		
		
	$("#editAccountant").click(function(){
		
		$(".data_display").html('');
		$(".data_display").load("dist/html/Acc/accountantEditing.html");
		$('.m-0').html('Edit Accountant');
		
		return false;
		});	
	
});// docment.ready function **//
