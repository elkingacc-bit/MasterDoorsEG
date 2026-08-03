$(document).ready(function(){
 const formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'EGP',
 });
//Sales Chart
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
   var labelSales1="Vat";
   var labelSales2="Not Vat";
   var bgc1="#22aa99";
   var bgc2="#2b5797";
   $(".totalQ").html('');
   $(".totalQ").html(JsonData.totalQuarters);
   if(JsonData.array == 1){
    var salesLable=['Jan'];
    var dataSales1=[JsonData.quartarTax];
    var dataSales2=[JsonData.QuartarLocal];  
   } 
   if(JsonData.array == 2){
    var salesLable=['Jan','Feb'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2];  
   } 
   if(JsonData.array == 3){
   	var salesLable=['Jan','Feb','Mar'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3];  
   } 
   if(JsonData.array == 4){
   	var salesLable=['Jan','Feb','Mar','Apr'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4];  
   } 
   if(JsonData.array == 5){
   	var salesLable=['Jan','Feb','Mar','Apr','May'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5];  
   } 
   if(JsonData.array == 6){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6];
   } 
   //
   if(JsonData.array == 7){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6,,JsonData.quartarTax7];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7];
   } 
   if(JsonData.array == 8){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6,JsonData.quartarTax7,JsonData.quartarTax8];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8];
   } 
    if(JsonData.array == 9){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6,JsonData.quartarTax7,JsonData.quartarTax8,JsonData.quartarTax9];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9];
   } 
   if(JsonData.array == 10){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6,JsonData.quartarTax7,JsonData.quartarTax8,JsonData.quartarTax9,JsonData.quartarTax10];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9,JsonData.QuartarLocal10];
   } 
   if(JsonData.array == 11){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6,JsonData.quartarTax7,JsonData.quartarTax8,JsonData.quartarTax9,JsonData.quartarTax10,JsonData.quartarTax11];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9,JsonData.QuartarLocal10,JsonData.QuartarLocal11];
   } 
   if(JsonData.array == 12){
   	var salesLable=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
    var dataSales1=[JsonData.quartarTax,JsonData.quartarTax2,JsonData.quartarTax3,JsonData.quartarTax4,JsonData.quartarTax5,JsonData.quartarTax6,JsonData.quartarTax7,JsonData.quartarTax8,JsonData.quartarTax9,JsonData.quartarTax10,JsonData.quartarTax11,JsonData.quartarTax12];
    var dataSales2=[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9,JsonData.QuartarLocal10,JsonData.QuartarLocal11,JsonData.QuartarLocal12];
   } 
   var chart = new Chart(ctx,{
    type: 'bar',
    data:{
     labels:salesLable,
     datasets:[
      {label:labelSales1,data:dataSales1 ,backgroundColor: bgc1},
      {label:labelSales2,data:dataSales2 ,backgroundColor: bgc2},
     ]
    },
    options:salesOptins
   });
  }//success
 });//Ajex Service Report

  /*


 //Sales Chart
 $.ajax({
  url:"dist/php/salesQuartar1.php",
  type:"POST",
  dataType: "json",
  cache: false,
  success: function(JsonData){
   $(".totalQ").html('');
   $(".totalQ").html(JsonData.totalQuarters);
   if(JsonData.array == 1){
    var chart = new Chart(ctx,{
    type: 'bar',
    data:{
     labels:['1'],
     datasets: [
      {
       label: 'Vat',
       data: [JsonData.quartarTax],
       backgroundColor: '#22aa99'
      },
      {
       label: 'Not Vat',
       data: [JsonData.QuartarLocal],
       backgroundColor: '#2b5797'
      },
     ]
    },
    options:{
     responsive: false,
     legend:{position: 'bottom'},
     scales:{
      xAxes:[{stacked: true,}],
      yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
     },
     tooltips: {
      callbacks: {
       label: function (tooltipItem) {
        return (new Intl.NumberFormat('en-US',{style: 'currency',currency: 'EGP',})).format(tooltipItem.value);
       }
      }
     }
    }
   });
  }
  if(JsonData.array == 2){
   var chart = new Chart(ctx,{
   type: 'bar',
   data:{
    labels:['1','2'],
    datasets: [{
     label: 'Vat',
     data: [JsonData.quartarTax, JsonData.quartarTax2],
     backgroundColor: '#CACFD2'
    },
    { 
label: 'Not Vat',
data: [JsonData.QuartarLocal,JsonData.QuartarLocal2],
backgroundColor: '#85C1E9'
},]
},
options: {
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
if(JsonData.array == 3){
var chart = new Chart(ctx, {
type: 'bar',
data: {
labels:['1','2','3'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data: [JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true}],
yAxes:[{stacked: true,ticks: {callback: function(value, index, values) {return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
if(JsonData.array == 4){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
//5
if(JsonData.array == 5){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
//6
if(JsonData.array == 6){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
 xAxes:[{stacked: true }],
 yAxes:[{
  stacked: true,
  ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}
 }],
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}

}
});
}
//7
if(JsonData.array == 7){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6','7'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6, JsonData.quartarTax7],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
//8
if(JsonData.array == 8){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6','7','8'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6, JsonData.quartarTax7, JsonData.quartarTax8],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
//9
if(JsonData.array == 9){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6','7','8','9'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6, JsonData.quartarTax7, JsonData.quartarTax8, JsonData.quartarTax9],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
//10
if(JsonData.array == 10){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6','7','8','9','10'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6, JsonData.quartarTax7, JsonData.quartarTax8, JsonData.quartarTax9, JsonData.quartarTax10],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9,JsonData.QuartarLocal10],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}
//11
if(JsonData.array == 11){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6','7','8','9','10','11'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6, JsonData.quartarTax7, JsonData.quartarTax8, JsonData.quartarTax9, JsonData.quartarTax10, JsonData.quartarTax11],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9,JsonData.QuartarLocal10,JsonData.QuartarLocal11],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}

//12
if(JsonData.array == 12){
var chart = new Chart(ctx, {
type: 'bar',
data:{
labels:['1','2','3','4','5','6','7','8','9','10','11','12'],
datasets: [{
label: 'Vat',
data: [JsonData.quartarTax, JsonData.quartarTax2, JsonData.quartarTax3, JsonData.quartarTax4, JsonData.quartarTax5, JsonData.quartarTax6, JsonData.quartarTax7, JsonData.quartarTax8, JsonData.quartarTax9, JsonData.quartarTax10, JsonData.quartarTax11, JsonData.quartarTax12],
backgroundColor: '#22aa99'
},
{
label: 'Not Vat',
data:[JsonData.QuartarLocal,JsonData.QuartarLocal2,JsonData.QuartarLocal3,JsonData.QuartarLocal4,JsonData.QuartarLocal5,JsonData.QuartarLocal6,JsonData.QuartarLocal7,JsonData.QuartarLocal8,JsonData.QuartarLocal9,JsonData.QuartarLocal10,JsonData.QuartarLocal11,JsonData.QuartarLocal12],
backgroundColor: '#2b5797'
},]
},
options:{
responsive: false,
legend:{position: 'bottom'},
scales:{
xAxes:[{stacked: true }],
yAxes:[{stacked: true,ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
},
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
}
});
}





}//success
});//Ajex Sales Report





//Stock Cart
 $.ajax({
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
    data: {
     labels: xValues,
     datasets: [{backgroundColor: barColors,data: yValues}]
    },
    options:{
    
     plugins: {
     },
     title:{
      display: true,
      text: 'Start Year As '+formatter.format(start),
     }
    }
   });
  }//success
 })//Ajex Stock Cart





//Purchise Chart
 $.ajax({
  url:"dist/php/purchesingDetils.php",
  type:"POST",
  dataType: "json",
  cache: false,
  success: function(JsonPurData){
   $(".totalP").html('');
   $(".totalP").html(JsonPurData.totalPurches);
   if(JsonPurData.array == 1){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels: ['1'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
//2
   if(JsonPurData.array == 2){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
//3
   if(JsonPurData.array == 3){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3'],
      datasets: [{
      label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
      }
     }
    });
   }

   //4
   if(JsonPurData.array == 4){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true,}]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //5
   if(JsonPurData.array == 5){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true,}]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //6
   if(JsonPurData.array == 6){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
              yAxes:[{stacked: true, ticks:{callback: function(value, index, values){return value / 1e6 + 'M';}}}]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
//7
   if(JsonPurData.array == 7){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6', '7'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //8
   if(JsonPurData.array == 8){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6', '7', '8'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //9
   if(JsonPurData.array == 9){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6', '7', '8', '9'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //10
   if(JsonPurData.array == 10){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9, JsonPurData.monthBuy10],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //11
   if(JsonPurData.array == 11){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9, JsonPurData.monthBuy10, JsonPurData.monthBuy11],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
   //12
   if(JsonPurData.array == 12){
    var chart = new Chart(ptx,{
     type: 'bar',
     data:{
      labels:['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11','12'],
      datasets: [{
       label: 'purchesed Ber Month',
       data: [JsonPurData.monthBuy, JsonPurData.monthBuy2, JsonPurData.monthBuy3, JsonPurData.monthBuy4, JsonPurData.monthBuy5, JsonPurData.monthBuy6, JsonPurData.monthBuy7, JsonPurData.monthBuy8, JsonPurData.monthBuy9, JsonPurData.monthBuy10, JsonPurData.monthBuy11, JsonPurData.monthBuy12],
       backgroundColor: '#22aa99'
      },]
     },
     options:{
      responsive: false,
      legend:{position: 'bottom'},
      scales:{
       xAxes:[{stacked: true,}],
       yAxes:[{stacked: true, }]
      },
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
     }
    });
   }
  }//success
 });//Ajex Purches Report


 //Service Chart
 $.ajax({
  url:"dist/php/shipingDitels.php",
  type:"POST",
  dataType: "json",
  cache: false,
  success: function(JsonservisData){
   $(".totalS").html('');
   $(".totalB").html('');
   $(".totalR").html('');
   $(".totalS").html(formatter.format(JsonservisData.salesService));
   $(".totalB").html(formatter.format(JsonservisData.buyService));   
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
tooltips: {
    callbacks: {
        label: function (tooltipItem) {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EGP',
            })).format(tooltipItem.value);
        }
    }
}
    }
   });
  }//success
 });//Ajex Service Report
*/
});//Doucment
