$(".buyAndSalesTax2").click(function(){
 $('.data_display').html('');
 $(".data_display").load('dist/php/buyAndSalesTaxDetiles.php');
});
$("#serviceRemaining").click(function(){
 $('.nav-link').removeClass("active");
 $(this).addClass('active ');
 $('.data_display').html('Hi');
 $(".data_display").load('dist/html/serviceInvoice.html');
 $('.m-0').html('');
 $('.m-0').html('Show Service Invoice');
});
$("#startBalance").click(function(){
 $('.nav-link').removeClass("active");
 $(this).addClass('active ');
 $('.data_display').html('progress Data........');
 $(".data_display").load('dist/php/startStockBalance.php');
 $('.m-0').html('');
});
$("#salesD").click(function(){
 $('.nav-link').removeClass("active");
 $(this).addClass('active ');
 $('.data_display').html('Hi');
 $(".data_display").load('dist/php/salesTrx.php');
 $('.m-0').html('');
});

const formatter = new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',});
//Sales none Vat Chart
$.ajax({
 url:"dist/php/salesQuartarNV.php",
 type:"POST",
 dataType: "json",
 cache: false,
 success: function(JsonDataNV){
  var salesNVOptins={
   responsive: false,
   legend:{position: 'bottom'},
   scales:{xAxes:[{stacked: true,}],yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]},
   tooltips:{callbacks:{label: function (tooltipItem){return (new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',})).format(tooltipItem.value);}}}
  };
  var labelSalesNV1="Salse";
  var labelSalesNV2="RTV";
  var labelSalesNV3="Cri";
  var labelSalesNV4="Service";
  var bgcNV1="#22aa99";
  var bgcNV2="red";
  var bgcNV3 = "#2b5797";
  var bgcNV4="green";
  $(".totalNV").html('');
  $(".totalNV").html(JsonDataNV.totalQuarters);
  if(JsonDataNV.month == 1){
   var salesNVLable=['Jan'];
   var dataSalesNV1=[JsonDataNV.mSales];
   var dataSalesNV2=[JsonDataNV.cridet];
   var dataSalesNV3=[JsonDataNV.RTV];  
   var dataSalesNV4=[JsonDataNV.service];
  } 
  if(JsonDataNV.month == 2){
   var salesNVLable=['Jan','Feb'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2];  
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2];
  } 
  if(JsonDataNV.month == 3){
   var salesNVLable=['Jan','Feb','Mar'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3];  
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3];
  } 
  if(JsonDataNV.month == 4){
   var salesNVLable=['Jan','Feb','Mar','Apr'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4];  
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4];
  } 
  if(JsonDataNV.month == 5){
   var salesNVLable=['Jan','Feb','Mar','Apr','May'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5];  
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5];
  } 
  if(JsonDataNV.month == 6){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6];
  } 
  if(JsonDataNV.month == 7){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun','Jul'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6,JsonDataNV.mSales7];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6,JsonDataNV.cridet7];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6,JsonDataNV.RTV7];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6,JsonDataNV.service7];
  } 
  if(JsonDataNV.month == 8){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6,JsonDataNV.mSales7,JsonDataNV.mSales8];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6,JsonDataNV.cridet7,JsonDataNV.cridet8];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6,JsonDataNV.RTV7,JsonDataNV.RTV8];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6,JsonDataNV.service7,JsonDataNV.service8];
  } 
  if(JsonDataNV.month == 9){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6,JsonDataNV.mSales7,JsonDataNV.mSales8,JsonDataNV.mSales9];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6,JsonDataNV.cridet7,JsonDataNV.cridet8,JsonDataNV.cridet9];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6,JsonDataNV.RTV7,JsonDataNV.RTV8,JsonDataNV.RTV9];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6,JsonDataNV.service7,JsonDataNV.service8,JsonDataNV.service9];
  } 
  if(JsonDataNV.month == 10){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6,JsonDataNV.mSales7,JsonDataNV.mSales8,JsonDataNV.mSales9,JsonDataNV.mSales10];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6,JsonDataNV.cridet7,JsonDataNV.cridet8,JsonDataNV.cridet9,JsonDataNV.cridet10];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6,JsonDataNV.RTV7,JsonDataNV.RTV8,JsonDataNV.RTV9,JsonDataNV.RTV10];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6,JsonDataNV.service7,JsonDataNV.service8,JsonDataNV.service9,JsonDataNV.service10];
  } 
  if(JsonDataNV.month == 11){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6,JsonDataNV.mSales7,JsonDataNV.mSales8,JsonDataNV.mSales9,JsonDataNV.mSales10,JsonDataNV.mSales11];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6,JsonDataNV.cridet7,JsonDataNV.cridet8,JsonDataNV.cridet9,JsonDataNV.cridet10,JsonDataNV.cridet11];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6,JsonDataNV.RTV7,JsonDataNV.RTV8,JsonDataNV.RTV9,JsonDataNV.RTV10,JsonDataNV.RTV11];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6,JsonDataNV.service7,JsonDataNV.service8,JsonDataNV.service9,JsonDataNV.service10,JsonDataNV.service11];
  } 
  if(JsonDataNV.month == 12){
   var salesNVLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
   var dataSalesNV1=[JsonDataNV.mSales,JsonDataNV.mSales2,JsonDataNV.mSales3,JsonDataNV.mSales4,JsonDataNV.mSales5,JsonDataNV.mSales6,JsonDataNV.mSales7,JsonDataNV.mSales8,JsonDataNV.mSales9,JsonDataNV.mSales10,JsonDataNV.mSales11,JsonDataNV.mSales12];
   var dataSalesNV2=[JsonDataNV.cridet,JsonDataNV.cridet2,JsonDataNV.cridet3,JsonDataNV.cridet4,JsonDataNV.cridet5,JsonDataNV.cridet6,JsonDataNV.cridet7,JsonDataNV.cridet8,JsonDataNV.cridet9,JsonDataNV.cridet10,JsonDataNV.cridet11,JsonDataNV.cridet12];
   var dataSalesNV3=[JsonDataNV.RTV,JsonDataNV.RTV2,JsonDataNV.RTV3,JsonDataNV.RTV4,JsonDataNV.RTV5,JsonDataNV.RTV6,JsonDataNV.RTV7,JsonDataNV.RTV8,JsonDataNV.RTV9,JsonDataNV.RTV10,JsonDataNV.RTV11,JsonDataNV.RTV12];
   var dataSalesNV4=[JsonDataNV.service,JsonDataNV.service2,JsonDataNV.service3,JsonDataNV.service4,JsonDataNV.service5,JsonDataNV.service6,JsonDataNV.service7,JsonDataNV.service8,JsonDataNV.service9,JsonDataNV.service10,JsonDataNV.service11,JsonDataNV.service12];
  } 
  var chart = new Chart(ctxNV,{
   type: 'bar',
   data:{
    labels:salesNVLable,
    datasets:[
     {label:labelSalesNV1,data:dataSalesNV1 ,backgroundColor: bgcNV1},
     {label:labelSalesNV2,data:dataSalesNV2 ,backgroundColor: bgcNV2},
     {label:labelSalesNV3,data:dataSalesNV3 ,backgroundColor: bgcNV3},
     {label:labelSalesNV4,data:dataSalesNV4 ,backgroundColor: bgcNV4},
    ]
   },
   options:salesNVOptins
  });
 }//success
});//Ajex
//Sales Vat Chart
$.ajax({
 url:"dist/php/salesQuartar1.php",
 type:"POST",
 dataType: "json",
 cache: false,
 success: function(JsonData){
  var salesOptins={
   responsive: false,
   legend:{position: 'bottom'},
   scales:{xAxes:[{stacked: true,}],yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]},
   tooltips:{callbacks:{label: function (tooltipItem){return (new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',})).format(tooltipItem.value);}}}
  };
  var labelSales1="Salse";
  var labelSales2="RTV";
  var labelSales3="Cri";
  var labelSales4="Service";
  var bgc1="#22aa99";
  var bgc2="red";
  var bgc3 = "#2b5797";
  var bgc4="green";
  $(".totalQ").html('');
  $(".totalQ").html(JsonData.totalQuarters);
  if(JsonData.month == 1){
   var salesLable=['Jan'];
   var dataSales1=[JsonData.mSales];
   var dataSales2=[JsonData.cridet];
   var dataSales3=[JsonData.RTV];  
   var dataSales4=[JsonData.service];
  } 
  if(JsonData.month == 2){
   var salesLable=['Jan','Feb'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2];
   var dataSales2=[JsonData.cridet,JsonData.cridet2];  
   var dataSales3=[JsonData.RTV,JsonData.RTV2];
   var dataSales4=[JsonData.service,JsonData.service2];
  } 
  if(JsonData.month == 3){
   var salesLable=['Jan','Feb','Mar'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3];  
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3];
  } 
  if(JsonData.month == 4){
   var salesLable=['Jan','Feb','Mar','Apr'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4];  
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4];
  } 
  if(JsonData.month == 5){
   var salesLable=['Jan','Feb','Mar','Apr','May'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5];  
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5];
  } 
  if(JsonData.month == 6){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6];
  } 
  if(JsonData.month == 7){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6,JsonData.mSales7];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6,JsonData.cridet7];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6,JsonData.RTV7];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6,JsonData.service7];
  } 
  if(JsonData.month == 8){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6,JsonData.mSales7,JsonData.mSales8];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6,JsonData.cridet7,JsonData.cridet8];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6,JsonData.RTV7,JsonData.RTV8];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6,JsonData.service7,JsonData.service8];
  } 
  if(JsonData.month == 9){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6,JsonData.mSales7,JsonData.mSales8,JsonData.mSales9];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6,JsonData.cridet7,JsonData.cridet8,JsonData.cridet9];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6,JsonData.RTV7,JsonData.RTV8,JsonData.RTV9];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6,JsonData.service7,JsonData.service8,JsonData.service9];
  } 
  if(JsonData.month == 10){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6,JsonData.mSales7,JsonData.mSales8,JsonData.mSales9,JsonData.mSales10];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6,JsonData.cridet7,JsonData.cridet8,JsonData.cridet9,JsonData.cridet10];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6,JsonData.RTV7,JsonData.RTV8,JsonData.RTV9,JsonData.RTV10];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6,JsonData.service7,JsonData.service8,JsonData.service9,JsonData.service10];
  } 
  if(JsonData.month == 11){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6,JsonData.mSales7,JsonData.mSales8,JsonData.mSales9,JsonData.mSales10,JsonData.mSales11];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6,JsonData.cridet7,JsonData.cridet8,JsonData.cridet9,JsonData.cridet10,JsonData.cridet11];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6,JsonData.RTV7,JsonData.RTV8,JsonData.RTV9,JsonData.RTV10,JsonData.RTV11];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6,JsonData.service7,JsonData.service8,JsonData.service9,JsonData.service10,JsonData.service11];
  } 
  if(JsonData.month == 12){
   var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
   var dataSales1=[JsonData.mSales,JsonData.mSales2,JsonData.mSales3,JsonData.mSales4,JsonData.mSales5,JsonData.mSales6,JsonData.mSales7,JsonData.mSales8,JsonData.mSales9,JsonData.mSales10,JsonData.mSales11,JsonData.mSales12];
   var dataSales2=[JsonData.cridet,JsonData.cridet2,JsonData.cridet3,JsonData.cridet4,JsonData.cridet5,JsonData.cridet6,JsonData.cridet7,JsonData.cridet8,JsonData.cridet9,JsonData.cridet10,JsonData.cridet11,JsonData.cridet12];
   var dataSales3=[JsonData.RTV,JsonData.RTV2,JsonData.RTV3,JsonData.RTV4,JsonData.RTV5,JsonData.RTV6,JsonData.RTV7,JsonData.RTV8,JsonData.RTV9,JsonData.RTV10,JsonData.RTV11,JsonData.RTV12];
   var dataSales4=[JsonData.service,JsonData.service2,JsonData.service3,JsonData.service4,JsonData.service5,JsonData.service6,JsonData.service7,JsonData.service8,JsonData.service9,JsonData.service10,JsonData.service11,JsonData.service12];
  } 
  var chart = new Chart(ctx,{
   type: 'bar',
   data:{
    labels:salesLable,
    datasets:[
     {label:labelSales1,data:dataSales1 ,backgroundColor: bgc1},
     {label:labelSales2,data:dataSales2 ,backgroundColor: bgc2},
     {label:labelSales3,data:dataSales3 ,backgroundColor: bgc3},
     {label:labelSales4,data:dataSales4 ,backgroundColor: bgc4},
    ]
   },
   options:salesOptins
  });
 }//success
});//Ajex 
//Service Chart
$.ajax({
 url:"dist/php/shipingDitels.php",
 type:"POST",
 dataType: "json",
 cache: false,
 success: function(JsonservisData){
  $(".totalR").html('');
  $(".totalR").html(formatter.format(JsonservisData.reminder));
  var chart = new Chart(servis,{
   type: 'bar',
   data:{
    labels: ['buy', 'sales','remaining'],
    datasets: [{
     data: [JsonservisData.buyService,JsonservisData.salesService,JsonservisData.reminder],
     backgroundColor: ['#00aba9','#22aa99','#2b5797']
    },]
   },
   options:{
    responsive: false,
    legend: false,
    scales:{
     xAxes:[{stacked: true,}],
     yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
    },
    tooltips:{callbacks:{label:function(tooltipItem){return (new Intl.NumberFormat('en-US', {style: 'currency',currency: 'EGP',})).format(tooltipItem.value);}}}
   }
  });
 }//success
});//Ajex
//Purchise Chart
$.ajax({
 url:"dist/php/purchesingDetils.php",
 type:"POST",
 dataType: "json",
 cache: false,
 success: function(JsonPurData){
  var purchesOptins={
   responsive: false,
   legend:{position: 'bottom'},
   scales:{xAxes:[{stacked: true,}],yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]},
   tooltips:{callbacks:{label: function (tooltipItem){return (new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',})).format(tooltipItem.value);}}}
  };
  var labelpurches="purchesed Ber Month";
  var bgc3="#22aa99";
  $(".totalP").html('');
  var totalPur=JsonPurData.totalPurches;
  $(".totalP").html(formatter.format(totalPur));
  if(JsonPurData.array == 1){
   var purchesLable=['Jan'];
   var datapurches=[JsonPurData.monthBuy];
  }
  if(JsonPurData.array == 2){
   var purchesLable=['Jan','Feb'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2];
  }
  if(JsonPurData.array == 3){
   var purchesLable=['Jan','Feb','Mar'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3];
  }
  if(JsonPurData.array == 4){
   var purchesLable=['Jan','Feb','Mar','Apr'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4];
  }
  if(JsonPurData.array == 5){
   var purchesLable=['Jan','Feb','Mar','Apr','May'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5];
  }
  if(JsonPurData.array == 6){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6];
  }
  if(JsonPurData.array == 7){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7];
  }
  if(JsonPurData.array == 8){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8];
  }
  if(JsonPurData.array == 9){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9];
  }
  if(JsonPurData.array == 10){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9, JsonPurData.monthBuy10];
  }
  if(JsonPurData.array == 11){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9, JsonPurData.monthBuy10, JsonPurData.monthBuy11];
  }
  if(JsonPurData.array == 12){
   var purchesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
   var datapurches=[JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9, JsonPurData.monthBuy10, JsonPurData.monthBuy11, JsonPurData.monthBuy12];
  }
  var chart = new Chart(ptx,{
   type: 'bar',
   data:{
    labels:purchesLable,
    datasets: [{
     label:labelpurches,
     data: datapurches,
     backgroundColor:bgc3
    },]
   },
   options:purchesOptins
  });
 }//success
});  
//Vat
$.ajax({//Purchise Chart
 url:"dist/php/buyAndSalesTax.php",
 type:"POST",
 dataType: "json",
 cache: false,
 success: function(JsonPSTData){
  var PSTOptins={
   responsive: false,
   legend:{position: 'bottom'},
   scales:{xAxes:[{stacked: true,}],yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]},
   tooltips:{callbacks:{label: function (tooltipItem){return (new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',})).format(tooltipItem.value);}}}
  };
  var labelPST="VAT Due Ber Month";
  var bgPTS="#22aa99";
  $(".buyAndSalesTax2").html('');
  var totalPST=JsonPSTData.totalPurches;
  $(".buyAndSalesTax2").html(formatter.format(totalPST));
  if(JsonPSTData.array == 1){
   var PSTLable=['Jan'];
   var dataPST=[JsonPSTData.monthBuy];
  }
  if(JsonPSTData.array == 2){
   var PSTLable=['Jan','Feb'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2];
  }
  if(JsonPSTData.array == 3){
   var PSTLable=['Jan','Feb','Mar'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3];
  }
  if(JsonPSTData.array == 4){
   var PSTLable=['Jan','Feb','Mar','Apr'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4];
  }
  if(JsonPSTData.array == 5){
   var PSTLable=['Jan','Feb','Mar','Apr','May'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5];
  }
  if(JsonPSTData.array == 6){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6];
  }
  if(JsonPSTData.array == 7){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun','Jul'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6, JsonPSTData.monthBuy7];
  }
  if(JsonPSTData.array == 8){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6, JsonPSTData.monthBuy7, JsonPSTData.monthBuy8];
  }
  if(JsonPSTData.array == 9){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6, JsonPSTData.monthBuy7, JsonPSTData.monthBuy8, JsonPSTData.monthBuy9];
  }
  if(JsonPSTData.array == 10){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6, JsonPSTData.monthBuy7, JsonPSTData.monthBuy8, JsonPSTData.monthBuy9, JsonPSTData.monthBuy10];
  }
  if(JsonPSTData.array == 11){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6, JsonPSTData.monthBuy7, JsonPSTData.monthBuy8, JsonPSTData.monthBuy9, JsonPSTData.monthBuy10, JsonPSTData.monthBuy11];
  }
  if(JsonPSTData.array == 12){
   var PSTLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
   var dataPST=[JsonPSTData.monthBuy, JsonPSTData.monthBuy2, JsonPSTData.monthBuy3, JsonPSTData.monthBuy4, JsonPSTData.monthBuy5, JsonPSTData.monthBuy6, JsonPSTData.monthBuy7, JsonPSTData.monthBuy8, JsonPSTData.monthBuy9, JsonPSTData.monthBuy10, JsonPSTData.monthBuy11, JsonPSTData.monthBuy12];
  }
  var chart = new Chart(buyAndSalesTax,{
   type: 'bar',
   data:{
    labels:PSTLable,
    datasets: [{
     label:labelPST,
     data: dataPST,
     backgroundColor:bgPTS
    },]
   },
   options:PSTOptins
  });
 }//success
});



 $.ajax({//Stock Cart
  url:"dist/php/stockChart.php",
  type:"POST",
  dataType: "json",
  cache: false,
  success: function(JsonStockData){
   var start = JsonStockData.startBalance;
   var use = JsonStockData.usePrice;
   var Avilable =JsonStockData.avilable;
   $(".totalUse").html(formatter.format(use));
   $(".totalAv").html(formatter.format(Avilable));   
   var options = { tooltips: {enabled: false},};
   var xValues = ["Use", "Avilable"];
   var yValues = [use, JsonStockData.avilable];
   var barColors = ["#00aba9","#2b5797",];
   var ctx = document.getElementById("stockChart").getContext('2d');
   var myChart =new Chart(ctx, {
    type: "pie",
    data:{labels: xValues,datasets: [{backgroundColor: barColors,data: yValues}]},
    options:{
     title:{display: true,text: 'Start Year As '+formatter.format(start),}}
    });
   }//success
  })//Ajex Stock Cart




//Cash Chart
$.ajax({
 url:"dist/php/cashStuts.php",
 type:"POST",
 //dataType: "json",
 cache: false,
 success: function(JsonCashData){
  var jcdOptins={
   responsive: false,
   legend:{position: 'bottom'},
   scales:{xAxes:[{stacked: true,}],yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value ;}}}]},
   tooltips:{callbacks:{label: function (tooltipItem){return (new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',})).format(tooltipItem.value);}}}
  };
  var labelcs="VAT Due Ber Month";
  var bgcs="#22aa99";
  const result = JsonCashData;
  var lenth = result.length;
  alert(lenth);
for(I = 1; I < lenth; I++)
{

    var labelcs=result+I;
   var csTLable=result+I;
   I++;
   var datacs=result+I; 
   
}
  /*if(JsonCashData.totalArrayCount == 1){
   var labelcs=[JsonCashData.Account1];
   var csTLable=[JsonCashData.Account1];
   var datacs=[JsonCashData.totalCollect1];
  }
  if(JsonCashData.totalArrayCount == 2){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2];
  }
  if(JsonCashData.totalArrayCount == 3){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3];
  }
  if(JsonCashData.totalArrayCount == 4){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4];
  }
  if(JsonCashData.totalArrayCount == 5){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4, JsonCashData.totalCollect5];
  }
  if(JsonCashData.totalArrayCount == 6){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4, JsonCashData.totalCollect5, JsonCashData.totalCollect6];
  }
 
  if(JsonCashData.totalArrayCount == 7){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4, JsonCashData.totalCollect5, JsonCashData.totalCollect6, JsonCashData.totalCollect7];
  }

   if(JsonCashData.totalArrayCount == 8){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7,JsonCashData.Account8];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7,JsonCashData.Account8];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4, JsonCashData.totalCollect5, JsonCashData.totalCollect6, JsonCashData.totalCollect7,JsonCashData.Account8];
  }

   if(JsonCashData.totalArrayCount == 9){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7,JsonCashData.Account8,JsonCashData.Account9];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7,JsonCashData.Account8,JsonCashData.Account9];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4, JsonCashData.totalCollect5, JsonCashData.totalCollect6, JsonCashData.totalCollect7,JsonCashData.Account8,JsonCashData.Account9];
  }
 
  if(JsonCashData.totalArrayCount == 10){
   var labelcs=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7,JsonCashData.Account8,JsonCashData.Account9,JsonCashData.Account10];
   var csTLable=[JsonCashData.Account1,JsonCashData.Account2,JsonCashData.Account3,JsonCashData.Account4,JsonCashData.Account5,JsonCashData.Account6,JsonCashData.Account7,JsonCashData.Account8,JsonCashData.Account9,JsonCashData.Account10];
   var datacs=[JsonCashData.totalCollect1, JsonCashData.totalCollect2, JsonCashData.totalCollect3, JsonCashData.totalCollect4, JsonCashData.totalCollect5, JsonCashData.totalCollect6, JsonCashData.totalCollect7,JsonCashData.Account8,JsonCashData.Account9,JsonCashData.Account10];
  }

*/
  var chart = new Chart(cashTotalChart,{
   type: 'bar',
   data:{
    labels:csTLable,
    datasets: [{
     label:labelcs,
     data: datacs,
     backgroundColor:bgcs
    },]
   },
   options:jcdOptins
  });
 }//success


  })//Ajex Stock Cart