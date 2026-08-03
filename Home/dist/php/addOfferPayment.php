<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $JobRowId = $_POST['ModelJobRIDPay'];
 
 $sqlGetAllNewJob="SELECT `customer`,`vatstatus` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetAttCurrent="SELECT `downpayment`, `deliverypayment`, `finishpayment`, `paynote` FROM `offerpolicy` 
	WHERE `jobRowId` = $JobRowId";
	$queryGetAttCurrent=mysqli_query($link,$sqlGetAttCurrent)or die("ERROR :02-AU_AU_S");
if(mysqli_num_rows($queryGetAttCurrent) == 0)
{
		$downPay = "";
		$recivePay = "";
		$finishPay = "";
		$paymentNote = "%60 at time of order \n %30 upon delivery before installation Delivery payment must be paid within a week from delivery date.\n %10 AFTER INSTALLATION";
}
else
{
	$resGetAttCurrent= mysqli_fetch_assoc($queryGetAttCurrent);
	if($resGetAttCurrent['downpayment'] != '0')
	{
		$downPay = $resGetAttCurrent['downpayment'];
		$downPay = round(($downPay * 100));
		$recivePay = $resGetAttCurrent['deliverypayment'];
		$recivePay = round(($recivePay * 100));
		$finishPay = $resGetAttCurrent['finishpayment'];
		$finishPay = round(($finishPay * 100));
		$paymentNote = strip_tags($resGetAttCurrent['paynote']);
		
	}
	else
	{
		$downPay = "";
		$recivePay = ""; 
		$finishPay = "";
		$paymentNote = "%60 at time of order\n %30 upon delivery before installation Delivery payment must be paid within a week from delivery date.\n %10 AFTER INSTALLATION";
	}
}
?>
 <div class="modal-header">
        <h5 class="modal-title">Add offer Delivery Trems for customer:&nbsp;<span style="color:blue;"><?php echo $resGetCustomer['customername'];?></span></h5>
        
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:100%" align="center">
   
      <thead class="bg-warning">
       	<th>Down Payment</th>
        <th>Delivery</th>
        <th>Finlization</th>
        <th>TAX</th>
      </thead>
      <tbody>
		<td class='col-sm-1'>
        	<select class="form-control" id="downPay">  
            
            	<option value="<?php echo $downPay;?>"><?php echo $downPay;?>%</option>
            	<?php					for($down = 0; $down <= 100; $down+=5)

					{
						
						echo "<option value='$down'>$down%</option>";
					}
				
				?>
            </select>
        </td>
        
        <td class='col-sm-1'>
        	<select class="form-control" id="DeliverPay">  
            
            	<option value="<?php echo $recivePay;?>"><?php echo $recivePay;?>%</option>
            	<?php
					for($deliveryPay = 0; $deliveryPay <= 100; $deliveryPay+=5)
					{
						
						echo "<option value='$deliveryPay'>$deliveryPay%</option>";
					}
			
				?>
            </select>
        </td>
        
         <td class='col-sm-1'>
        	<select class="form-control" id="InstallPay">  
            
            	<option value="<?php echo $finishPay;?>"><?php echo $finishPay;?>%</option>
            	<?php
					for($installPay = 0; $installPay <= 100; $installPay+=5)
					{
						
						echo "<option value='$installPay'>$installPay%</option>";
					}
				
				?>
            </select>
        </td>
         <td class='col-sm-1'>
        	<select class="form-control" id="TAX">  
            
            <?php
				if($resGetAllNewJob['vatstatus'] == "")
				{
					echo '
						<option value="">Choose</option>
						<option value="1">VAT</option>
						<option value="0">No VAT</option>
					';
				}
				else if($resGetAllNewJob['vatstatus'] == 0)
				{
					echo '
						 <option value="0">No VAT</option>
						 <option value="1">VAT</option>
					';
				}
				else if($resGetAllNewJob['vatstatus'] == 1)
				{
					echo '
						 <option value="1">VAT</option>
						 <option value="0">No VAT</option>
					';
				}
			?>
            
            	
            	
            </select>
        </td>
       
       
 <tr>
 	 <td class='col-sm-6' colspan="4">
        <textarea class="form-control" id="PayNotes" style="height:30vh; font-size:14px"><?php echo $paymentNote;?></textarea> 
        </td>
 </tr>       
 <tr>
 	<td colspan="4" align="center">	
    	<button class="btn btn-sm btn-success" id="savePayInOfferBTN">Save</button>
    </td> 
  </tr>
  </tbody>
     
 </table>
 </div>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoadPay"/>
 <script type="text/javascript">
 $(document).ready(function() {

	$("#savePayInOfferBTN").click(function(){
		
		var payJobRID = $("#rowIdJobLoadPay").val();
		var payDownpayment = $("#downPay").val();
		var payDeliver = $("#DeliverPay").val();
		var payInstall = $("#InstallPay").val();
		var payNote = $("#PayNotes").val();
		var TAXStatus = $("#TAX").val();
		//
		
		var checkTotal = (parseInt(payDownpayment)+parseInt(payDeliver)+parseInt(payInstall));
		
		if(payDownpayment == "" || payDownpayment == null)
		{
			alert('missing field');
			$('#downPay').css("border-color","red");
			setTimeout(function(){
           		$('#downPay').css("border-color","#EBEBEB");
				$("#downPay").focus();				
				}, 1500);
		}
		else if(payDeliver == "" || payDeliver == null)
		{
			alert('missing field');
			$('#DeliverPay').css("border-color","red");
			setTimeout(function(){
           		$('#DeliverPay').css("border-color","#EBEBEB");
				$("#DeliverPay").focus();				
				}, 1500);
		}
		else if(payInstall == "" || payInstall == null)
		{
			alert('missing field');
			$('#InstallPay').css("border-color","red");
			setTimeout(function(){
           		$('#InstallPay').css("border-color","#EBEBEB");
				$("#InstallPay").focus();				
				}, 1500);
		}
		else if(TAXStatus == "" || TAXStatus == null)
		{
			alert('missing field');
			$('#TAX').css("border-color","red");
			setTimeout(function(){
           		$('#TAX').css("border-color","#EBEBEB");
				$("#TAX").focus();				
				}, 1500);
		}
		else if(payNote == "" || payNote == null)
		{
			alert('missing field');
			$('#PayNotes').css("border-color","red");
			setTimeout(function(){
           		$('#PayNotes').css("border-color","#EBEBEB");
				$("#PayNotes").focus();				
				}, 1500);
		}
		else if(checkTotal != 100 )
		{
			alert('Total Payment installments no equal 100% ');
			$('#downPay').css("border-color","red");
			$('#DeliverPay').css("border-color","red");
			$('#InstallPay').css("border-color","red");
			setTimeout(function(){
           		$('#downPay').css("border-color","#EBEBEB");
				$('#DeliverPay').css("border-color","#EBEBEB");
				$('#InstallPay').css("border-color","#EBEBEB");
						
				}, 1500);
		}
		else
		{
			
			$.ajax({
				
					url:"dist/php/saveOfferPayments.php",
					type:"POST",
					data:{PayDown:payDownpayment, payJobRowId:payJobRID,payRecive:payDeliver,payFinish:payInstall, paymentNotes:payNote,TAXSat:TAXStatus},
					beforeSend: function(){
						$("#savePayInOfferBTN").prop('disabled', true);	
					},
					success: function(doneAddPayments){
						
						if(doneAddPayments == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $("#Payment").removeClass("btn-dark");
							 $("#Payment").addClass("btn-info");
							 $(".policyTable").show();
							 $(".paymentTH").show();
							 $(".paymentData").show();
							 $(".paymentData").html(payNote);
							 
						}
						else
						{
							alert(doneAddPayments);
							$("#savePayInOfferBTN").prop('disabled', false);	
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