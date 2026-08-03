// JavaScript Document
$(document).ready(function(){

"use strict";

$("#rptSelected").change(function(){
	
var selectDate =$("#rptDate").val(); 
var selectRpt = $("#rptSelected").val();

$(".LogReportResult").load("dist/php/logRptResult.php",{rptRef:selectRpt, rptDate:selectDate});
	});


});// docment.ready function **//Type New Organize Name Is allready Exist.
