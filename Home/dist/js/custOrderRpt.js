$(document).ready(function(){
	"use strict";
	

$("#CustomerRpt").click(function(){
	
	$('.custOrderDiv').html('');
	$('.custOrderDiv').load("dist/php/custOrderRpt.php");
	$('.m-0').html('');
	$('.m-0').html('All Customer Po Report');
	return false;
	});
	
	$("#SupplierRpt").click(function(){
	
	$('.custOrderDiv').html('');
	$('.custOrderDiv').load("dist/php/suppOrderRpt.php");
	$('.m-0').html('');
	$('.m-0').html('All Supplier Order Status Report');
	return false;
	});


});// JavaScript Document