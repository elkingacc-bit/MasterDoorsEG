$(document).ready(function(){
	"use strict";
	
	$("#invoice").click(function(){
		$('.data_display').html('');
		$('.data_display').load('dist/html/addInvoice.html');
		$('.m-0').html('Export Stock Invoice');
	return false;	
		});	
		
		$("#duty").click(function(){
		$('.data_display').html('');
		$('.data_display').load('dist/html/addDuty.html');
		$('.m-0').html('Export Stock Duty');
	return false;	
		});	
	
});	