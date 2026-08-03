$(document).ready(function(){
	"use strict";
	$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$('#AllCustPo').load("dist/php/allCustPOSite.php");

$("#allValidPO").change(function(){
	var CustPOAttend = $(this).val();
	var dateAttend = $("#AttendDate").val();
	var custdata = {};
$("#AllCustPo option").each(function(i,el) {  
   custdata[$(el).data("value")] = $(el).val();
});
console.log(custdata, $("#AllCustPo option").val());

	var PoChosenValideate = $('#AllCustPo [value="' + CustPOAttend + '"]');					
	var PoRID = $('#AllCustPo [value="' + CustPOAttend + '"]').data('value');
	if(PoChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer name / PO Number form the list');
			$("#allValidPO").css("border-color","red");
		  setTimeout(function(){
		   $("#allValidPO").css("border-color","#EBEBEB");    						
		   $("#allValidPO").val('');	
		   $("#allValidPO").focus();							
		  }, 1500);
		}
		else if(dateAttend == "")
		{
			alert('Please Choose Valid Date');
			$("#AttendDate").css("border-color","red");
		  setTimeout(function(){
		   $("#AttendDate").css("border-color","#EBEBEB");    						
		   $("#AttendDate").val('');	
		   $("#AttendDate").focus();							
		  }, 1500);
		}
		else
		{	
			$(".AddAttend").html('');
			$(".AddAttend").load("dist/php/allStaffForAttend.php",{PoNum:CustPOAttend, PoRowId:PoRID,attendDate:dateAttend});
		}
	});
	
	
var now = new Date();

var day = ("0" + now.getDate()).slice(-2);
var month = ("0" + (now.getMonth() + 1)).slice(-2);

var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

$('#AttendDate').val(today);	
	

});// JavaScript Document