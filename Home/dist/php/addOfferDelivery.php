<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $JobRowId = $_POST['ModelJobRIDDeliv'];
 
 $sqlGetAllNewJob="SELECT `customer` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetAttCurrent="SELECT `deliver`, `deliverydate` FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryGetAttCurrent=mysqli_query($link,$sqlGetAttCurrent)or die("ERROR :02-AU_AU_S");
	$resGetAttCurrent= mysqli_fetch_assoc($queryGetAttCurrent);
	if($resGetAttCurrent['deliver'] != "" )
	{
		$deliveryDate = $resGetAttCurrent['deliverydate'];
		$deliveryNote = strip_tags($resGetAttCurrent['deliver']);
	}
	else
	{
		$deliveryDate = "";
		$deliveryNote = "Standard INSTALLATION lead time IS (6) working weeks according to site situation \n delivery time for this doors is (8) working weeks after deposit release";
	}
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Add offer Delivery Trems for customer:&nbsp;<span style="color:blue;"><?php echo $resGetCustomer['customername'];?></span</h5>
        
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:100%" align="center">
   
      <thead class="bg-warning">
       	<th>End Date</th>
        <th>Note</th>
      </thead>
      <tbody>
		<td class='col-sm-1'>
        	<input type="date" class="form-control" id="DeliverDate" value="<?php echo $deliveryDate;?>" />
        </td>
        <td class='col-sm-5'>
        <textarea class="form-control" id="DeliveryNotes" style="height:30vh; font-size:14px"><?php echo $deliveryNote;?></textarea> 
        </td>
 <tr>
 	<td colspan="2" align="center">	
    	<button class="btn btn-sm btn-success" id="saveDelivInOfferBTN">Save</button>
    </td> 
  </tr>
  </tbody>
     
 </table>
 </div>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoadDeliv"/>
 <script type="text/javascript">
 $(document).ready(function() {

	$("#saveDelivInOfferBTN").click(function(){
		
		var DeliveryJobRID = $("#rowIdJobLoadDeliv").val();
		var DeliveryDateAdded = $("#DeliverDate").val();
		var DeliveryDateNote = $("#DeliveryNotes").val();
		//
		if(DeliveryDateAdded == "" || DeliveryDateAdded == null)
		{
			alert('missing field');
			$('#DeliverDate').css("border-color","red");
			setTimeout(function(){
           		$('#DeliverDate').css("border-color","#EBEBEB");
				$("#DeliverDate").focus();				
				}, 1500);
		}
		else if(DeliveryDateNote == "" || DeliveryDateNote == null)
		{
			alert('missing field');
			$('#DeliveryNotes').css("border-color","red");
			setTimeout(function(){
           		$('#DeliveryNotes').css("border-color","#EBEBEB");
				$("#DeliveryNotes").focus();				
				}, 1500);
		}
		else
		{
			
			$.ajax({
				
					url:"dist/php/saveOfferDelivery.php",
					type:"POST",
					data:{Delvdate:DeliveryDateAdded, DelvJobRowId:DeliveryJobRID, DelvNotes:DeliveryDateNote},
					beforeSend: function(){
						$("#saveDelivInOfferBTN").prop('disabled', true);	
					},
					success: function(doneAddDelivery){
						
						if(doneAddDelivery == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $("#Delivery").removeClass("btn-dark");
							 $("#Delivery").addClass("btn-info");
							 $(".policyTable").show();
							 $(".deliveryTH").show();
							 $(".deliveryData").show();
							 $(".deliveryData").html(DeliveryDateNote);
							 
						}
						else
						{
							alert(doneAddDelivery);
							$("#saveDelivInOfferBTN").prop('disabled', false);	
						}
						
					}
				});
		}
		
		return false;
		});
});
 

 </script>
 <?php
 }
 ?>