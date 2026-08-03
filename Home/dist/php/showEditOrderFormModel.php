<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$orderID = $_POST['ORIDEdit'];

$sqlGetOrderData="SELECT `custCode`,`orderNotes`, `jobidref` FROM `customerpo` WHERE `poId` = $orderID";
$queryGetOrderData=mysqli_query($link,$sqlGetOrderData)or die("ERROR :01-AU_AU_S");
$resGetOrderData= mysqli_fetch_assoc($queryGetOrderData);

$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetOrderData[custCode]";
$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);

$sqlGetJobData="SELECT `projectName`,`salesman`,`vatstatus` FROM `job` 
WHERE `jobId` = $resGetOrderData[jobidref]";
$queryGetJobData=mysqli_query($link,$sqlGetJobData)or die("ERROR :03-AU_AU_S");
$resGetJobData= mysqli_fetch_assoc($queryGetJobData);

$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetJobData[salesman]";
$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :04-AU_AU_S");
$resGetSales= mysqli_fetch_assoc($queryGetSales);

	
?>
<div class="editOrderInfo"> 
<div class="modal-header">

        <h5 class="modal-title">Edit PO For Project Name:&nbsp; <span style="color:blue;">
        <b><?php echo $resGetJobData['projectName']; ?></b></span>
        &nbsp;
        <button class="btn btn-xs btn-danger editDataBTN" id="DeleteAll" value="<?php echo $orderID; ?>">Delete All</button>
        </h5>
</div>
  <div class="modal-body "> 
  <div class="table-responsive editData">   
	<table class="table">
    	<thead>
        	<th>Customer</th>
            <th>Project</th>
            <th>Sales</th>
            <th>TAX</th>
        </thead>
        <tbody>
        	<td>
            	<input type="text" list="customerList" class="form-control" id="ChoseCustName" 
                placeholder="Customer Name" autocomplete="off" 
                value="<?php echo $resGetCustomer['customername'] ?>">
                <datalist id="customerList">
                </datalist>
            </td>
            <td>
                <input type="text"  class="form-control" id="projName" placeholder="Project Name"
                autocomplete="off" value="<?php echo $resGetJobData['projectName'] ?>">
            </td>
            <td>
                <input type="text" list="SalesList" class="form-control" id="SalesName" 
                placeholder="Sales Name" autocomplete="off" 
                value="<?php echo $resGetSales['username'] ?>">
                <datalist id="SalesList">
                </datalist>
            </td>
             <td >
        	<select class="form-control" id="TAX">  
            
            <?php
				if($resGetJobData['vatstatus'] == "")
				{
					echo '
						<option value="">Choose</option>
						<option value="1">VAT 14%</option>
						<option value="2">Contracting 5%</option>
						<option value="0">Sub Contractor</option>
					';
				}
				else if($resGetJobData['vatstatus'] == 0)
				{
					echo '
						 <option value="0">Sub Contractor</option>
						 <option value="1">VAT</option>
						 <option value="2">Contracting 5%</option>
					';
				}
				else if($resGetJobData['vatstatus'] == 1)
				{
					echo '
						 <option value="1">VAT</option>
						 <option value="0">Sub Contractor</option>
						 <option value="2">Contracting 5%</option>
					';
				}
				else if($resGetJobData['vatstatus'] == 1)
				{
					echo '
						 <option value="2">Contracting 5%</option>
						 <option value="1">VAT</option>
						 <option value="0">Sub Contractor</option>
						 
					';
				}
			?>
            
            	
            	
            </select>
        </td>
            <tr>
            	<td colspan="4" align="center">
                	<button class="btn btn-success btn-sm" id="saveEditOrderDataBTN">Save</button>
                </td>
            </tr>
        </tbody>
    
    </table>
 </div>     
  </div>
  
  </div>
 <input type="text" value="<?php echo $orderID?>" style="display:none" id="CustPORID"/>
 <input type="text" value="<?php echo $resGetOrderData['jobidref']?>" style="display:none" id="JobRID"/>
  
 <script type="text/javascript">
 $(document).ready(function() {
	 
$('#customerList').load("dist/php/allCustCode.php");
$('#SalesList').load("dist/php/getSalesCode.php");	 
	 
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 


	$('#saveEditOrderDataBTN').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
	
		var jobRowID =  $("#JobRID").val();
		var OrderRID = $("#CustPORID").val();
		var TaxVal = $("#TAX").val();
		var Project = $("#projName").val();
		Project=Project.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
		
var data = {};
$("#customerList option").each(function(i,el) {  
   data[$(el).data("value")] = $(el).val();
});
console.log(data, $("#customerList option").val());

var value = $('#ChoseCustName').val();

var dataSales = {};
$("#SalesList option").each(function(i,el) {  
   dataSales[$(el).data("value")] = $(el).val();
});
console.log(dataSales, $("#SalesList option").val());

var valueSales = $('#SalesName').val();
 
var custChosenValideate = $('#customerList [value="' + value + '"]');		
var salesChosenValideate = $('#SalesList [value="' + valueSales + '"]');					
	
	var jCustm = $('#customerList [value="' + value + '"]').data('value');
	var jSalesName = $('#SalesList [value="' + valueSales + '"]').data('value');
	
	 if(value == "" )
      {
       alert('missing field');
			$('#ChoseCustName').css("border-color","red");
			setTimeout(function(){
           		$('#ChoseCustName').css("border-color","#EBEBEB");
				$('#ChoseCustName').focus();				
				}, 1500);
      }
	 else if(custChosenValideate.length <= 0)
	  {
			alert('Please Choose Valid Customer name form the list');
			$("#ChoseCustName").css("border-color","red");
		  setTimeout(function(){
		   $("#ChoseCustName").css("border-color","#EBEBEB");    						
		   $("#ChoseCustName").val('');	
		   $("#ChoseCustName").focus();							
		  }, 1500);
	  }
	else if( Project == "")
	  {
			alert('missing field');
				$('#projName').css("border-color","red");
				setTimeout(function(){
					$('#projName').css("border-color","#EBEBEB");
					$('#projName').focus();				
					}, 1500);
	  }
	else if( valueSales == "")
	  {
			alert('missing field');
				$('#SalesName').css("border-color","red");
				setTimeout(function(){
					$('#SalesName').css("border-color","#EBEBEB");
					$('#SalesName').focus();				
					}, 1500);
	  }
	else if(salesChosenValideate.length <= 0)
	  {
				alert('Please Choose Valid Sales name form the list');
				$("#SalesName").css("border-color","red");
			  setTimeout(function(){
			   $("#SalesName").css("border-color","#EBEBEB");    						
			   $("#SalesName").val('');	
			   $("#SalesName").focus();							
			  }, 1500);
	  }
	  else if( TaxVal == "")
	  {
			alert('missing field');
				$('#TAX').css("border-color","red");
				setTimeout(function(){
					$('#TAX').css("border-color","#EBEBEB");
					$('#TAX').focus();				
					}, 1500);
	  }
	else
	{ 
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/SaveEditCustOrderBData.php',
                type:'POST',
                data:{CustORID:OrderRID, JobRIDOrder:jobRowID, Custname:value,CustCode:jCustm,Salesname:valueSales, SalesCode:jSalesName, newProject:Project, Taxs:TaxVal},
                
				success: function(DoneEditCustOrderBData)
				{
					if(DoneEditCustOrderBData == 1)
					{
						alert("Data Saved");
						
						
						
						$(".modal-dialog").removeClass("modal-sm");
						$(".modal-dialog").addClass("modal-lg");
						$('.ShowData').html('');
						$(".myModal").modal('toggle');
						setTimeout(function(){
							$('.custOrderDiv').html('');
							$('.custOrderDiv').load("dist/php/custOrderRpt.php");
						}, 500);
					}
					
					else
					{
						alert(DoneEditCustOrderBData);
					}
				}         
        	}); 
	}
	
	return false;
  });
  
  
  
  $("#DeleteAll").click(function(){
		
		var deletedOrderRID = $(this).val();
		
		var confirmDelete = confirm("Please Confirm Delete All Order Data (Supply Order & Customer PO & Close Offer and all exported stock will be returned to stock) ??");
		
		if(confirmDelete === true)
		{
			 	
			$.ajax({
				 url:"dist/php/confrimDeletePass.php",
				 data:{DOrederRID:deletedOrderRID},
				 type:"POST",
				 beforeSend: function(){
				 },
				success: function(DoneConfirmed){
				
				
				$(".modal-dialog").removeClass("modal-lg");
				$(".modal-dialog").addClass("modal-sm");
				
                $('.editOrderInfo').html('');
				//$(".myModal").css("max-width","40%");
				
				setTimeout(function(){
					$('.editOrderInfo').html(DoneConfirmed);
				}, 500);
				 }
			});
		}
		
		return false;
		});

});
 
 </script>