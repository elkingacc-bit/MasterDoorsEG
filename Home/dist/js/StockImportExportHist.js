// JavaScript Document
$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$("#stkImport").click(function(){
	
	var btnRef = $(this).val();
			
		$.ajax({
				
			url:"dist/php/allImportHist.php",
			type:"POST",
			data:{bRef:btnRef},
			beforeSend: function()
			{
				$(".showBtn").prop("disabled", true);
			},
			success: function(showhistoryData)
			{
				$(".showBtn").prop("disabled", false);
				$(".allStockHistIE").html(''); 
				$(".allStockHistIE").html(showhistoryData); 
			}
			
			
			});
		
	return false;

	});
	
$("#stkExport").click(function(){
	
	var btnRef2 = $(this).val();
			
		$.ajax({
			
			url:"dist/php/allExportHist.php",
			type:"POST",
			data:{bRef2:btnRef2},
			beforeSend: function()
			{
				$(".showBtn").prop("disabled", true);
			},
			success: function(showhistoryData2)
			{
				$(".showBtn").prop("disabled", false);
				$(".allStockHistIE").html(''); 
				$(".allStockHistIE").html(showhistoryData2); 
			}
			
			
			});
		
	return false;

	});
		
});