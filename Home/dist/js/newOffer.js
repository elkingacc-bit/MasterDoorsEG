$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$('#customerList').load("dist/php/allCustCode.php");
$('#SalesList').load("dist/php/getSalesCode.php");
	
$("#NewLocalPR").click(function(){
		
		$.ajax({
				url:"dist/php/getNewLocalPR.php",
				type:"POST",
				beforeSend: function(){
					$("#NewLocalPR").prop("disabled",true);		
				},
				success: function(addLocalPR){
					
					$("#jobRequRef").val(addLocalPR);	
				}
			
			});
	
	return false;
	});	

$("#addNewOfferBtn").click(function(){
	
	var data = {};
$("#customerList option").each(function(i,el) {  
   data[$(el).data("value")] = $(el).val();
});
console.log(data, $("#customerList option").val());

var value = $('#ChoseCustName').val();

var dataSales = {};
$("#SalesList option").each(function(i,el) {  
   dataSales[$(el).data("value")] = $(el).val();
});
console.log(dataSales, $("#SalesList option").val());

var valueSales = $('#SalesName').val();

var custChosenValideate = $('#customerList [value="' + value + '"]');		
var salesChosenValideate = $('#SalesList [value="' + valueSales + '"]');					
	
	var jCustm = $('#customerList [value="' + value + '"]').data('value');
	var jSalesName = $('#SalesList [value="' + valueSales + '"]').data('value');
	var jType = $("#jobtype").val();
	var jPjtName = $("#projName").val();
	//var jAttPers=$('#AttentionPers').val();
	var jReqRef = $("#jobRequRef").val();
	jReqRef=jReqRef.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	var DescribJob = $("#jobDescrip").val();
	var SalesCommCode = $("#SalesCommCode").val();
	
	DescribJob=DescribJob.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	
	 if(value == "" )
      {
       alert('missing field');
			$('#ChoseCustName').css("border-color","red");
			setTimeout(function(){
           		$('#ChoseCustName').css("border-color","#EBEBEB");
				$('#ChoseCustName').focus();				
				}, 1500);
      }
	  else if(custChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer name form the list');
			$("#ChoseCustName").css("border-color","red");
		  setTimeout(function(){
		   $("#ChoseCustName").css("border-color","#EBEBEB");    						
		   $("#ChoseCustName").val('');	
		   $("#ChoseCustName").focus();							
		  }, 1500);
		}
		
	  else if( jType == "")
	  {
		alert('missing field');
			$('#jobtype').css("border-color","red");
			setTimeout(function(){
           		$('#jobtype').css("border-color","#EBEBEB");
				$('#jobtype').focus();				
				}, 1500);
	  }
	  else if( jPjtName == "")
	  {
		alert('missing field');
			$('#projName').css("border-color","red");
			setTimeout(function(){
           		$('#projName').css("border-color","#EBEBEB");
				$('#projName').focus();				
				}, 1500);
	  }
	  else if( jReqRef == "")
	  {
		alert('missing field');
			$('#jobRequRef').css("border-color","red");
			setTimeout(function(){
           		$('#jobRequRef').css("border-color","#EBEBEB");
				$('#jobRequRef').focus();				
				}, 1500);
	  }
	  else if( valueSales == "")
	  {
		alert('missing field');
			$('#SalesName').css("border-color","red");
			setTimeout(function(){
           		$('#SalesName').css("border-color","#EBEBEB");
				$('#SalesName').focus();				
				}, 1500);
	  }
	   else if(salesChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Sales name form the list');
			$("#SalesName").css("border-color","red");
		  setTimeout(function(){
		   $("#SalesName").css("border-color","#EBEBEB");    						
		   $("#SalesName").val('');	
		   $("#SalesName").focus();							
		  }, 1500);
		}
		else
		{
			$.ajax({
					
					url:"dist/php/saveAddNewOffer.php",
					type:"POST",
					data:{custNameJ:jCustm ,SalesNJ:jSalesName, jobType:jType, jobName:jPjtName,salesComm:SalesCommCode,jRequRef:jReqRef, jobDesc:DescribJob},
					
					beforeSend: function(){
						$("#addNewOfferBtn").prop("disabled",true);	
					},
					
				success: function(doneAddNewOffer){
				   if(doneAddNewOffer == 0)
					{
						alert("PR is Already Exist in Database ...!!");
						$('#jobRequRef').css("border-color","red");
						setTimeout(function(){				
						$('#jobRequRef').css("border-color","#EBEBEB");
						$('#jobRequRef').focus();			
      					}, 1500);
						$("#addNewOfferBtn").prop('disabled', false);
					}
				   
				   else if(doneAddNewOffer == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#addNewOfferBtn").prop('disabled', false);
      					}, 1500);
						$("#3_1").click();
					}
					else if(doneAddNewOffer == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "../";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#addNewOfferBtn").prop('disabled', false);
						alert(doneAddNewOffer);
					}
			}
				
				});
		}
	
	});

});// JavaScript Document