$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$('#AllCustPo').load("dist/php/allCustPO.php");
$("#allSupplierList").load("dist/php/allManufForOrderDList.php");
	
$("#addNewSuppOrderBtn").click(function(){
	
	var CustPO = $('#ChoseCustPO').val();
	var Suppdata = {};
$("#allSupplierList option").each(function(i,el) {  
   Suppdata[$(el).data("value")] = $(el).val();
});
console.log(Suppdata, $("#allSupplierList option").val());

	var SuppName = $('.allSupplier').val();
	var suppChosenValideate = $('#allSupplierList [value="' + SuppName + '"]');
	var PoChosenValideate = $('#AllCustPo [value="' + CustPO + '"]');					
	var SuppCode = $('#allSupplierList [value="' + SuppName + '"]').data('value');
	var DDate = $("#delvDate").val();
	var OrderNote = $("#OrderNote").val();
	OrderNote=OrderNote.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	
	 if(SuppName == "")
      {
       alert('missing field');
			$('#allSupplier').css("border-color","red");
			setTimeout(function(){
           		$('#allSupplier').css("border-color","#EBEBEB");
				$('#allSupplier').focus();				
				}, 1500);
      }
	  else if(suppChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer name form the list');
			$("#allSupplier").css("border-color","red");
		  setTimeout(function(){
		   $("#allSupplier").css("border-color","#EBEBEB");    						
		   $("#allSupplier").val('');	
		   $("#allSupplier").focus();							
		  }, 1500);
		}
		
	else if(PoChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer PO form the list');
			$("#ChoseCustPO").css("border-color","red");
		  setTimeout(function(){
		   $("#ChoseCustPO").css("border-color","#EBEBEB");    						
		   $("#ChoseCustPO").val('');	
		   $("#ChoseCustPO").focus();							
		  }, 1500);
		}
		
	  else if( CustPO == "")
	  {
		alert('missing field');
			$('#ChoseCustPO').css("border-color","red");
			setTimeout(function(){
           		$('#ChoseCustPO').css("border-color","#EBEBEB");
				$('#ChoseCustPO').focus();				
				}, 1500);
	  }
	  else if( DDate == "")
	  {
		alert('missing field');
			$('#delvDate').css("border-color","red");
			setTimeout(function(){
           		$('#delvDate').css("border-color","#EBEBEB");
				$('#delvDate').focus();				
				}, 1500);
	  }
	else
		{
			$.ajax({
					
					url:"dist/php/saveNewSuppOrder.php",
					type:"POST",
					data:{customerPO:CustPO ,supplierName:SuppName, SupplierCode:SuppCode, DeliveryD:DDate,SuppOrderNote:OrderNote},
					
					beforeSend: function(){
						$("#addNewSuppOrderBtn").prop("disabled",true);	
					},
					
				success: function(doneAddNewSuppO){
				   if(doneAddNewSuppO == 0)
					{
						alert("Supplier Order is Already Exist in Database ...!!");
						$('#ChoseCustPO').css("border-color","red");
						setTimeout(function(){				
						$('#ChoseCustPO').css("border-color","#EBEBEB");
						$('#ChoseCustPO').focus();			
      					}, 1500);
						$("#addNewSuppOrderBtn").prop('disabled', false);
					}
				   
				   else if(doneAddNewSuppO == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#addNewSuppOrderBtn").prop('disabled', false);
      					}, 1500);
						$("#5_1").click();
					}
					else if(doneAddNewSuppO == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "../";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#addNewSuppOrderBtn").prop('disabled', false);
						alert(doneAddNewSuppO);
					}
			}
				
				});
		}
	
	});

});// JavaScript Document