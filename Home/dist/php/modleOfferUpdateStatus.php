<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $JobRowId = $_POST['ModelJobRIDUp'];
 
 $sqlGetAllNewJob="SELECT `customer` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlCheckPolicy="SELECT `jobRowId` FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryCheckPolicy=mysqli_query($link,$sqlCheckPolicy)or die("ERROR :03-AU_AU_S");
	if(mysqli_num_rows($queryCheckPolicy) == 0)
	{
		$button = "disabled";
		$titel = "No Exported offer for this job";
	}
	else
	{
		$button = '';
		$titel = "";
	}
	
	$sqlChechOfferPO="SELECT `poId`, `PoNum` FROM `customerpo` WHERE `jobidref` = $JobRowId";
	$queryChechOfferPO=mysqli_query($link,$sqlChechOfferPO)or die("ERROR :02-AU_AU_S");
	if(mysqli_num_rows($queryChechOfferPO) > 0)
	{
		$resChechOfferPO= mysqli_fetch_assoc($queryChechOfferPO);
		
		$poId = $resChechOfferPO['poId'];
		$poNum = $resChechOfferPO['PoNum'];
		$ref = 1; 
	}
	else
	{
		$poId = "";
		$poNum = "";
		$ref = 0;
	}
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Update offer Status For Customer:&nbsp;<span style="color:blue;">
		<?php echo $resGetCustomer['customername'];?></span></h5>
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:60%" align="center">
   
      <tbody>
       <tr class="AllButtons">
        <td >
        <button class="btn btn-success btn-lg update" value="1" id="wonOffer" <?php echo $button;?> 
        data-toggle='tooltip' data-placement='top' title='<?php echo $titel;?>'>Won</button>
        </td>
        <td >
        <button class="btn btn-danger btn-lg update" value="2" id="lostOffer" >Lost</button>
        </td>
        <td >
        <button class="btn btn-info btn-lg update" value="3" id="closedOffer">Closed</button>
        </td>
        <td >
        <button class="btn btn-primary btn-lg update" value="4" id="closedOffer" <?php echo $button;?>
        data-toggle='tooltip' data-placement='top' title='<?php echo $titel;?>'>Demo</button>
        </td>
       </tr>
        <tr class="reasonTR" style="display:none">
        	<td colspan="4"><b>Lost/Close Reason</b></td>  
        </tr>
        <tr class="reasonTR" style="display:none">
        	<td colspan="3">
            <select class="custom-select" id="inputGroupSelect03">
                <option selected value="">Choose...</option>
                <option value="1" data-toggle='tooltip' data-placement='left' title='High Price Offered'>
                HPO</option>
                <option value="2" data-toggle='tooltip' data-placement='left' title='Customer Not Serious'>
                CNS</option>
                <option value="3" data-toggle='tooltip' data-placement='left' title='Out Of Scoop'>
                OOS</option>
            </select>
            </td>
        </tr>
        <tr class="orderTR" style="display:none" >
        	<td colspan="4"> <b>Order Number</b></td>  
        </tr>
        <tr class="orderTR" style="display:none">
        	<td colspan="4">
           <div class="input-group mb-3">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" id="LocalPO" type="button">Create</button>
              </div>
              <input type="text" class="form-control" id="orderNum" value="<?php echo $poNum;?>" />
            </div>
            </td>
        </tr>
         <tr class="demoTR" style="display:none" >
        	<td colspan="4"> <b>Demo Length (Months)</b></td>  
        </tr>
        <tr class="demoTR" style="display:none">
        	<td colspan="2" align="center">
           		<select class="form-control" id="demoLingth">
                	<option value="1">1&nbsp;Month</option>
                    <option value="2">2&nbsp;Month</option>
                    <option value="3">3&nbsp;Month</option>
               </option>
            </td>
        </tr>
        
        <tr class="saveBuutonTR" style="display:none">
        	<td colspan="3" align="center">
            	<button class="btn btn-sm btn-dark" id="saveOrderStatusBTN" >Save</button>
            </td>
        </tr>
        
  </tbody>
     
 </table>
 </div>
 <input type="text"  style="display:none" id="ButtonRef"/>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoadNote"/>
 <input type="text" value="<?php echo $poId?>" style="display:none" id="currentPoRowID"/>
 <input type="text" value="<?php echo $poNum?>" style="display:none" id="currentPoNumber"/>
 <input type="text" value="<?php echo $ref?>" style="display:none" id="OldOrderRef"/>
 <script type="text/javascript">
 $(document).ready(function() {

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
	
	$("#LocalPO").click(function(){
		
		$("#LocalPO").prop('disabled', true);
		
		$.ajax({
			
				url:"dist/php/getLocalPONum.php",
				type:"POST",
				success: function(ShowLocalNum){
					
				$("#orderNum").val(ShowLocalNum);
				$("#orderNum").prop('disabled', true);
				}
			});
		return false;
		});
		
		/*$("#orderNum").keyup(function(){
			var orderNumVal = $(this).val();
			if(orderNumVal != "")
			{
				$("#LocalPO").hide();
			}
			else if(orderNumVal == "")
			{
				$("#LocalPO").show;
			}
		});*/
	
	

	$(".update").click(function(){
		
		var btnRef = $(this).val();	
		$("#ButtonRef").val(btnRef);
		
		if(btnRef == 1)
		{
			var JobRID = $("#rowIdJobLoadNote").val();
			var PONum = $("#orderNum").val();
			var currnetPoID = $("#currentPoRowID").val();
			var currnetPoNum = $("#currentPoNumber").val();
			var currnetRef = $("#OldOrderRef").val();
			if(currnetRef == 1)
			{
				$("#LocalPO").prop("disabled", true);
				$("#orderNum").val(currnetPoNum);
				$(".AllButtons").hide();
				$(".saveBuutonTR").show();
				$(".orderTR").show();
			}
			else
			{
				$(".AllButtons").hide();
				$(".saveBuutonTR").show();
				$(".orderTR").show();
			}
		}
		
		else if(btnRef == 2 || btnRef == 3)
		{
			
			$(".AllButtons").hide();
			$(".saveBuutonTR").show();
			$(".reasonTR").show();
			
		}
		else if(btnRef == 4)
		{
			$(".AllButtons").hide();
			$(".saveBuutonTR").show();
			$(".demoTR").show();
		}
			
		return false;
	});
		
	$("#saveOrderStatusBTN").click(function(){
		  
		var valRef = $("#ButtonRef").val();	
		var currnetPoID2 = $("#currentPoRowID").val();	
		var currnetRef2 = $("#OldOrderRef").val();
		
		if(valRef == 1)
		{
			var saveJobRID = $("#rowIdJobLoadNote").val();
			var PONum = $("#orderNum").val();
			PONum = PONum.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
						
			if(PONum == "")
			{
				alert('missing field');
				$('#orderNum').css("border-color","red");
				setTimeout(function(){
					$('#orderNum').css("border-color","#EBEBEB");
					$("#orderNum").focus();				
				}, 1500);
			}
			else
			{
			$.ajax({
				
					url:"dist/php/saveOfferStatus.php",
					type:"POST",
					data:{JobRowId:saveJobRID, sendRef:valRef, status:PONum, curntPORIdNo:currnetPoID2,NewRef:currnetRef2},
					beforeSend: function(){
						$("#saveOrderStatusBTN").prop('disabled', true);	
					},
					success: function(doneAddPO){
						
						if(doneAddPO == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $('.allOffersExpt').html('');
							 $('.allOffersExpt').load("dist/php/allOffersForExport.php");
							
							 
						}
						else
						{
							alert(doneAddPO);
							$("#saveOrderStatusBTN").prop('disabled', false);	
						}
						
					}
				});
			}
		}
		
		else if(valRef == 2 || valRef == 3)
		{
			var saveJobRID = $("#rowIdJobLoadNote").val();
			var Reason = $("#inputGroupSelect03").val();
			var valRef = $("#ButtonRef").val();	
						
			if(Reason == "")
			{
				alert('missing field');
				$('#inputGroupSelect03').css("border-color","red");
				setTimeout(function(){
					$('#inputGroupSelect03').css("border-color","#EBEBEB");
					$("#inputGroupSelect03").focus();				
				}, 1500);
			}
			else
			{
			
			$.ajax({
				
					url:"dist/php/saveOfferStatus.php",
					type:"POST",
					data:{JobRowId:saveJobRID, sendRef:valRef, status:Reason},
					beforeSend: function(){
						$("#saveOrderStatusBTN").prop('disabled', true);	
					},
					success: function(doneClosedJob){
						
						if(doneClosedJob == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $('.allOffersExpt').html('');
							 $('.allOffersExpt').load("dist/php/allOffersForExport.php");
							
							 
						}
						else
						{
							alert(doneClosedJob);
							$("#saveOrderStatusBTN").prop('disabled', false);	
						}
						
					}
				});
			}
		}
		else if(valRef == 4)
		{
			var saveJobRID = $("#rowIdJobLoadNote").val();
			var demoLinght = $("#demoLingth").val();
			var valRef = $("#ButtonRef").val();	
						
			if(demoLinght == "")
			{
				alert('missing field');
				$('#demoLingth').css("border-color","red");
				setTimeout(function(){
					$('#demoLingth').css("border-color","#EBEBEB");
					$("#demoLingth").focus();				
				}, 1500);
			}
			else
			{
			
			$.ajax({
				
					url:"dist/php/saveOfferStatus.php",
					type:"POST",
					data:{JobRowId:saveJobRID, sendRef:valRef, status:demoLinght},
					beforeSend: function(){
						$("#saveOrderStatusBTN").prop('disabled', true);	
					},
					success: function(doneAddDemoForJob){
						
						if(doneAddDemoForJob == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $('.allOffersExpt').html('');
							 $('.allOffersExpt').load("dist/php/allOffersForExport.php");
							
							 
						}
						else
						{
							alert(doneAddDemoForJob);
							$("#saveOrderStatusBTN").prop('disabled', false);	
						}
						
					}
				});
			}
		}
		  
	return false;
	});	
});
 
 </script>
 <?php
 }
 ?>