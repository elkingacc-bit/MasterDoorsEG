$(document).ready(function(){
 var projectNum = $('#projectsId').val();
 var projectName = $('#projectsName').val();
 //
 $("#projectPurchases").click(function(){
  $('.projectLink').removeClass('active');
  $(this).addClass('active');
  $('.titelProject').html('Withdraw Purchases For Project ' + projectName);
  $('.formProject').load('dist/html/Acc/withdrawProjectPurchases.html');
 });
 //
 $("#otherExpenses").click(function(){
  $('.projectLink').removeClass('active');
  $(this).addClass('active');
  $('.titelProject').html('Withdraw Other Expenses To Project ' + projectName);
  $('.formProject').load('dist/html/Acc/withdrawOtherExpenses.html');
 });
 return false;
});