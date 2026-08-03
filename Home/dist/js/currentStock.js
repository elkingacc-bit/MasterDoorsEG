// JavaScript Document
$(document).ready(function(){

"use strict";
	
	$.ajax({
			
			url:"dist/php/checkUpdateStockLookUp.php",
				success: function(checkLookUp){
					if(checkLookUp == 1)
					{
						$('.cStock').html("");
						$(".cStock").load("dist/php/currentStockTable.php");
					}
					else if(checkLookUp == 0)
					{
						
						$.ajax({
								url:"dist/php/updateWHLookUp.php",
								beforeSend: function(){
								$('.cStock').html("");
								$('.cStock').html("<center><img src='dist/img/loadingColor.gif' alt='loading'><br><h1>Please Wait System Updating Stock </h1></center>");
								},
								success: function(ShowAllST){
									if(ShowAllST == 1)
									{
										$('.cStock').html("");
										$(".cStock").load("dist/php/currentStockTable.php");
									}
									else
									{
										alert(ShowAllST);
									}
								}
						});
					}
				}
		
		});
		
});// docment.ready function **//
