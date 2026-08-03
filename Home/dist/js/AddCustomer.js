$(document).ready(function(){
	"use strict";
	
	$("#CustActivityList").load("dist/php/CheckAllCustActivity.php");
	
$('#saveNewCustomer').click(function(){
      var CustomerName=$("#newCustomerName").val();
      CustomerName=CustomerName.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
      var customerArea=$("#customerArea").val();
      var Address=$("#customerAddress").val();
	  var activtCust= $("#customeractivity").val();
	  activtCust=activtCust.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	  
      if(CustomerName == "" )
      {
       alert('missing field');
			$('#newCustomerName').css("border-color","red");
			setTimeout(function(){
           		$('#newCustomerName').css("border-color","#EBEBEB");
				$('#newCustomerName').focus();				
				}, 1500);
      }
	  else if( customerArea == "")
	  {
		alert('missing field');
			$('#customerArea').css("border-color","red");
			setTimeout(function(){
           		$('#customerArea').css("border-color","#EBEBEB");
				$('#customerArea').focus();				
				}, 1500);
	  }
	  else if( activtCust == "")
	  {
		alert('missing field');
			$('#customeractiv').css("border-color","red");
			setTimeout(function(){
           		$('#customeractiv').css("border-color","#EBEBEB");
				$('#customeractiv').focus();				
				}, 1500);
	  }
      else
      {
        $.ajax({
            url:"dist/php/saveNewCustomer.php",
            type:"POST",
            data:{name:CustomerName, Area:customerArea,address:Address,activityCust:activtCust},
			beforeSend: function(){
			$("#saveNewCustomer").prop('disabled', true);	
			},
            success: function(doneNewCust){
              if(doneNewCust == 0)
              {
                alert("The Customer Name is allready Existing!");
                $('#newCustomerName').css("border-color","red");
                setTimeout(function(){
                      $('#newCustomerName').css("border-color","black");
                $('#newCustomerName').val('');
                  }, 1500);
				  $("#saveNewCustomer").prop('disabled', false);	
              }
              else if(doneNewCust == 1)
              {
                alert("successfully added new Customer "+CustomerName);
                $('#newCust').click();
				$('#8_4').click();
              }
              else
              {
                alert(doneNewCust);
				$("#saveNewCustomer").prop('disabled', false);	
              }
            }
          });
      }
    return false;
    });
    });
