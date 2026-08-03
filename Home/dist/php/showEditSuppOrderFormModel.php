<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$orderID = $_POST['ORIDEdit'];

$sqlGetOrderData="SELECT `SuppCode`,`date`, `deliveryDate`, `OrderNumber` FROM `supplierorder` 
WHERE `SOId` = $orderID";
$queryGetOrderData=mysqli_query($link,$sqlGetOrderData)or die("ERROR :01-AU_AU_S");
$resGetOrderData= mysqli_fetch_assoc($queryGetOrderData);

$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $resGetOrderData[SuppCode]";
$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :02-AU_AU_S");
$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);

?>
<div class="modal-header">

        <h5 class="modal-title">Edit Supplier Order No:&nbsp; <span style="color:blue;">
        <b><?php echo $resGetOrderData['OrderNumber']; ?></b>
        </h5>
</div>
  <div class="modal-body "> 
  <div class="table-responsive">   
	<table class="table">
    	<thead>
        	<th>Supplier Name</th>
            <th>Start</th>
            <th>Delivery</th>
        </thead>
        <tbody>
        	<td>
            	<input type="text" list="allSupplierList" class="form-control allSupplier" id="allSupplier" 
                placeholder="Supplier Name" autocomplete="off" 
                value="<?php echo $resGetSupplier['suppliername'] ?>">
                <datalist id="allSupplierList">
                </datalist>
            </td>
            <td>
                <input type="date"  class="form-control" id="StartDate" 
                autocomplete="off" value="<?php echo $resGetOrderData['date'] ?>">
            </td>
            <td>
               <input type="date" id="delvDate" class="form-control"
                value="<?php echo $resGetOrderData['deliveryDate'] ?>">
               
            </td>
            <tr>
            	<td colspan="3" align="center">
                	<button class="btn btn-success btn-sm" id="saveEditSuppOrderDataBTN">Save</button>
                </td>
            </tr>
        </tbody>
    
    </table>
 </div>     
  </div>
 <input type="text" value="<?php echo $orderID?>" style="display:none" id="SuppPORID"/>
  
 <script type="text/javascript">
 $(document).ready(function() {
	 
$("#allSupplierList").load("dist/php/allManufForOrderDList.php");
	 
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 


	$('#saveEditSuppOrderDataBTN').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
	
		var suppOrderRID = $("#SuppPORID").val();
		var Start = $("#StartDate").val();
		var Deliviry = $("#delvDate").val();
		
	var Suppdata = {};
$("#allSupplierList option").each(function(i,el) {  
   Suppdata[$(el).data("value")] = $(el).val();
});
console.log(Suppdata, $("#allSupplierList option").val());

	var SuppName = $('.allSupplier').val();
	var suppChosenValideate = $('#allSupplierList [value="' + SuppName + '"]');
	var SuppCode = $('#allSupplierList [value="' + SuppName + '"]').data('value');	
	
	
	if(SuppName == "")
      {
       alert('missing field');
			$('#allSupplier').css("border-color","red");
			setTimeout(function(){
           		$('#allSupplier').css("border-color","#EBEBEB");
				$('#allSupplier').focus();				
				}, 1500);
      }
	  else if(suppChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer name form the list');
			$("#allSupplier").css("border-color","red");
		  setTimeout(function(){
		   $("#allSupplier").css("border-color","#EBEBEB");    						
		   $("#allSupplier").val('');	
		   $("#allSupplier").focus();							
		  }, 1500);
		}
	else if( Start == "")
	  {
			alert('missing field');
				$('#StartDate').css("border-color","red");
				setTimeout(function(){
					$('#StartDate').css("border-color","#EBEBEB");
					$('#StartDate').focus();				
					}, 1500);
	  }
	else if( Deliviry == "")
	  {
			alert('missing field');
				$('#delvDate').css("border-color","red");
				setTimeout(function(){
					$('#delvDate').css("border-color","#EBEBEB");
					$('#delvDate').focus();				
					}, 1500);
	  }
	else
	{
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/SaveEditSuppOrderBData.php',
                type:'POST',
                data:{SuppORID:suppOrderRID, Suppname:SuppName,SupplierCode:SuppCode,StartDate:Start, EndDate:Deliviry},
                
				success: function(DoneEditSuppOrderBData)
				{
					if(DoneEditSuppOrderBData == 1)
					{
						alert("Data Saved");
						
						$('.ShowData').html('');
						
						$(".myModal").modal('toggle');
						setTimeout(function(){
							$('.custOrderDiv').html('');
							$('.custOrderDiv').load("dist/php/suppOrderRpt.php");
						}, 500);
					}
					else
					{
						alert(DoneEditSuppOrderBData);
					}
				}         
        	}); 
	}
  });
});
 
 </script>