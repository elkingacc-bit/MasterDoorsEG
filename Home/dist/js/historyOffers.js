$(document).ready(function(){
	"use strict";
	
	$(".rptRef").click(function(){
		
		var rptRef = $(this).val();
		
		if(rptRef == 3)
		{
			$('.allOffersHist').html("");
			$('.allOffersHist').load("dist/php/allWonOffersHistory.php",{RPTRef:rptRef});
			
			$('.m-0').html('');
			$('.m-0').html('All Won Offers History Report');
		}
		else if(rptRef == 4)
		{
			$('.allOffersHist').html("");
			$('.allOffersHist').load("dist/php/allLostOffersHistory.php",{RPTRef:rptRef});
			
			$('.m-0').html('');
			$('.m-0').html('All Lost Offers History Report');
		}
		else if(rptRef == 5)
		{
			$('.allOffersHist').html("");
			$('.allOffersHist').load("dist/php/allCloseOffersHistory.php",{RPTRef:rptRef});
			
			$('.m-0').html('');
			$('.m-0').html('All Closed Offers History Report');
		}
		
		else if(rptRef == 6)
		{
			$('.allOffersHist').html("");
			$('.allOffersHist').load("dist/php/allCloseOffersHistory.php",{RPTRef:rptRef});
			
			$('.m-0').html('');
			$('.m-0').html('All Demo Offers History Report');
		}
		
		return false;
	});


});// JavaScript Document