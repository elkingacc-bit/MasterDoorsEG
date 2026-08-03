$(document).ready(function(){




  $.ajax({
  url:"dist/php/salesQuartar1.php",
  type:"POST",
  success: function(q1){
$(function(){
 'use strict'
 var ticksStyle = {fontColor: '#495057',fontStyle: 'bold'}
 var mode = 'index'
 var intersect = true
 var $salesChart = $('#stackedBarChart')
 // eslint-disable-next-line no-unused-vars
 
    

 var salesChart = new Chart($salesChart,{
  type: 'bar',
  data: {
   labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Total'],
   datasets:[
    {
 


     backgroundColor: '#007bff',
     borderColor: '#007bff',
     data: [q1, 2000, 3000, 2500, 8500]
    },
    {
     backgroundColor: '#ced4da',
     borderColor: '#ced4da',
     data: [700, 1700, 2700, 2000, 1800]
    }
   ]
  },
  options:{
   maintainAspectRatio: false,
   tooltips: {
    mode: mode,
    intersect: intersect
   },
   hover:{
    mode: mode,
    intersect: intersect
   },
   legend: {
    display: false
   },
   scales: {
    yAxes: [{
     // display: false,
     gridLines: {
      display: true,
      lineWidth: '4px',
      color: 'rgba(0, 0, 0, .2)',
      zeroLineColor: 'transparent'
     },
     ticks: $.extend({
      beginAtZero: true,
      // Include a dollar sign in the ticks
      callback: function (value) {
       if (value >= 1000) {
        value /= 1000
        value += 'k'
       }
       return '$' + value
      }
     }, ticksStyle)
    }],
    xAxes: [{
     display: true,
     gridLines: {
      display: false
     },
     ticks: ticksStyle
    }]
   }
  }
 })
})
  }
 });
  return false;
});
