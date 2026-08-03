$(document).ready(function(){
	"use strict";
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
	
	//$("#AllManufList").load("dist/php/allManufDDList.php");
	
	 $("#suppType").change(function(){
		 
		 var selectedType = $(this).val();
		 
		 if(selectedType == 1)
		 {
			 $("#CuntryList").val("Egypt");
			 $("#CuntryList").prop('disabled', true);
		 }
		 else
		 {
			$("#CuntryList").prop('disabled', false); 
			$("#CuntryList").val("");
		 }
		 
		 });
	
	$("#addNewSuppButton").click(function(){
		
		/*var data = {};
			$("#AllManufList option").each(function(i,el) {  
  			 data[$(el).data("value")] = $(el).val();
			});
		console.log(data, $("#AllManufList option").val());
*/

		var ManuValue = $("#chooseManuf").val();
		var SuppType = $("#suppType").val();
		//var ManuCode = $('#AllManufList [value="' + ManuValue + '"]').data('value');
		var SuppName = $("#suppName").val();
		SuppName = SuppName.replace(/^\s+|\s+$|\s+(?=\s)/g, "").replace(/[^A-Z0-9]+/ig, " ");
		var SuppCountry = $("#CuntryList").val();
		//var ManufChosenValideate = $('#AllManufList [value="' + ManuValue + '"]');	
	 /*if(ManufChosenValideate.length <= 0)
			{
				alert('Please Choose Valid Manufactuer name form the list');
				$("#chooseManuf").css("border-color","red");
				setTimeout(function(){
			       $("#chooseManuf").css("border-color","#EBEBEB");    						
				   $("#chooseManuf").val('');	
				   $("#chooseManuf").focus();							
				}, 1500);
			}	
	else*/ if(SuppType == "" || null )
		{
			alert('missing field');
			$('#suppType').css("border-color","red");
			setTimeout(function(){
           		$('#suppType').css("border-color","#EBEBEB");
				$("#suppType").focus();				
				}, 1500);
								
			
		}
		
		else if(SuppName == "" || null )
		{
			alert('missing field');
			$('#suppName').css("border-color","red");
			setTimeout(function(){
           		$('#suppName').css("border-color","#EBEBEB");
				$("#suppName").focus();				
				}, 1500);
								
			
		}
		
	else if(SuppCountry == "" || null )
		{
			alert('missing field');
			$('#CuntryList').css("border-color","red");
			setTimeout(function(){
           		$('#CuntryList').css("border-color","#EBEBEB");
				$('#CuntryList').focus();				
				}, 1500);
								
			
		}
		
	else
		{
			
		$.ajax({
			url:"dist/php/addSupplier.php",
			type:"POST",
			data: {manufctuerCode:ManuValue, Supplier:SuppName, Country:SuppCountry,spplierType:SuppType},
			beforeSend: function(){
				
				$("#addNewSuppButton").prop('disabled', true);
				},
				
			success: function(AddNewSupplier){
				if(AddNewSupplier == 0)
					{
						alert("Supplier Name Is Already existing in Database.!");
						$("#addNewSuppButton").prop('disabled', false);
						$('#suppName').css("border-color","red");
						setTimeout(function(){
							$('#suppName').css("border-color","#EBEBEB");
							$("#suppName").focus();				
							}, 1500);
					}
					else if(AddNewSupplier == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#addNewSuppButton").prop('disabled', false);
      					}, 1500);
						$("#newSupplier").click();
					}
					else if(AddNewSupplier == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#addNewSuppButton").prop('disabled', false);
						alert(AddNewSupplier);
					}
			}
		});
	}
		return false;
		});

	});// docment.ready function **//
