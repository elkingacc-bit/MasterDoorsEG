$(document).ready(function() {
	"use strict";
		
//$('#allManufactuer').load("php/AllManufactuerData.php");
$("#ManufRegion").change(function(){
		 
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

	
	$('#addNewManufButton').click(function(){
				
				var manufType=$('#ManufType').val();
				var newManufc=$("#ManufName").val();
				newManufc=newManufc.replace(/^\s+|\s+$|\s+(?=\s)/g, "").replace(/[^A-Z0-9]+/ig, " ");
				var Cuntory = $("#CuntryList").val();
				var CuntoryChosenValideate = $('#allCountries [value="' + Cuntory + '"]');
				var manufRegion=$("#ManufRegion").val();
		
				if(manufRegion == "" || null)
				{
					alert('Please Chosse Manufacturer Region!');
					$("#ManufRegion").css("border-color","red");
					setTimeout(function(){
			       $("#ManufRegion").css("border-color","#EBEBEB");    						
				   $("#ManufRegion").focus();							
				}, 1500);
				}
				
				else if(manufType == "")
				{
					alert('Please choose Manufacturer Type');
					$("#ManufType").css("border-color","red");
					setTimeout(function(){
			       $("#ManufType").css("border-color","#EBEBEB");    						
				   $("#ManufType").focus();							
				}, 1500);
				}
				
				else if(newManufc == "" || null)
				{
					alert('Please type Manufacturer Name!');
					$("#ManufName").css("border-color","red");
					setTimeout(function(){
			       $("#ManufName").css("border-color","#EBEBEB");    						
				   $("#ManufName").focus();							
				}, 1500);
				}
				
				else if(CuntoryChosenValideate.length <= 0)
				{
				alert('Please Choose Valid Country Name From List');
				$("#CuntryList").css("border-color","red");
				setTimeout(function(){
			       $("#CuntryList").css("border-color","#EBEBEB");    						
				   $("#CuntryList").val('');	
				   $("#CuntryList").focus();							
				}, 1500);
				}
				
				else
				{
					$.ajax({
							url:"dist/php/addManufactuer.php",
							type:"POST",
						data:{RegionM:manufRegion,TypeM:manufType, ManfName:newManufc, selectCountry:Cuntory},
							beforeSend: function(){
				
							$("#addNewManufButton").prop('disabled', true);
							},
							success: function(doneNewManuf){
								if(doneNewManuf == 0)
								{
									alert("The Manufacturer name is allready Existing!");
									$("#addNewManufButton").prop('disabled', false);
									$('#newManufName').css("border-color","red");
									setTimeout(function(){
           							$('#newManufName').css("border-color","black");
									$('#newManufName').val('');
      							}, 3000);
								}
							 else if(doneNewManuf == 1)
								{
									alert("successfully added new Manufacturer Data.");
									$('#newmanufactuer').click();
									
								}
								else
								{
									alert(doneNewManuf);
									$("#addNewManufButton").prop('disabled', false);
								}
							}
							
						
						});
				}
			return false;
			});	
	
	
	
	});