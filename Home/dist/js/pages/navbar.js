$(document).ready(function(){
 $("#addProducts").click(function(){
  $(".data_display").load('dist/html/uploadExcel.html');
 });

$(".sideBar").load("dist/php/navigationMinu.php");


 $("#test").click(function(){
  $(".m-0").html('test');
  $(".data_display").load('dist/php/test.php');
 });


 $("#q1").click(function(){
  $(".m-0").html('Quarter 1-2022');
  $(".data_display").load('dist/php/salesQ1.php');
 });
 $("#q2").click(function(){
  $(".m-0").html('Quarter 2-2022');
  $(".data_display").load('dist/php/salesQ2.php');
 });
 $("#q3").click(function(){
  $(".m-0").html('Quarter 3-2022');
  $(".data_display").load('dist/php/salesQ3.php');
 });
 $("#q4").click(function(){
  $(".m-0").html('Quarter 4-2022');
  $(".data_display").load('dist/php/salesQ4.php');
 });
 $("#allReb").click(function(){
  $(".m-0").html('Year 2022');
  $(".data_display").load('dist/php/allSales.php');
 });

 $("#customers").click(function(){
  $(".data_display").load('dist/php/customers.php');
 });
 $("#stock").click(function(){
  $(".data_display").load('dist/php/stock.php');
 });
 $("#salesTarget").click(function(){
  $(".data_display").load('dist/php/salesTarget.php');
 });
 $("#seller").click(function(){
  $(".data_display").load('dist/php/seller.php');
 });
});