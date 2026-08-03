<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $JobRowId = $_POST['ModelJobRIDValid'];
 
 $sqlGetAllNewJob="SELECT `customer` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetAttCurrent="SELECT `validate`, `validitydate` FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryGetAttCurrent=mysqli_query($link,$sqlGetAttCurrent)or die("ERROR :02-AU_AU_S");
	$resGetAttCurrent= mysqli_fetch_assoc($queryGetAttCurrent);
	if($resGetAttCurrent['validitydate'] != "")
	{	
		$VaildDate = $resGetAttCurrent['validitydate'];
		$VaildNote = strip_tags($resGetAttCurrent['validate']);
	}
	else
	{
		$VaildDate = "";
		$VaildNote = "This proposal is only valid for (7) dayes from date mentioned above.\n If order proceeds after this certain period, therefore Master Doors could change the prices.";
	}
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Add offer validation for customer:&nbsp;<span style="color:blue;"><?php echo $resGetCustomer['customername'];?></span</h5>
        
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:100%" align="center">
   
      <thead class="bg-warning">
       	<th>Validation</th>
        <th>Validation Note</th>
      </thead>
      <tbody>
		<td class='col-sm-1'>
        	<input type="date" class="form-control" id="Validate" value="<?php echo $VaildDate;?>" />
        </td>
        <td class='col-sm-5'>
        <textarea class="form-control" id="validNotes" style="height:30vh; font-size:14px"><?php echo $VaildNote;?></textarea> 
        </td>
 <tr>
 	<td colspan="2" align="center">	
    	<button class="btn btn-sm btn-success" id="saveValidInOfferBTN">Save</button>
    </td> 
  </tr>
  </tbody>
     
 </table>
 </div>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoadValid"/>
 <script type="text/javascript">
 $(document).ready(function() {

	$("#saveValidInOfferBTN").click(function(){
		
		var validJobRID = $("#rowIdJobLoadValid").val();
		var validDateAdded = $("#Validate").val();
		var validDateNote = $("#validNotes").val();
		//
		if(validDateAdded == "" || validDateAdded == null)
		{
			alert('missing field');
			$('#Validate').css("border-color","red");
			setTimeout(function(){
           		$('#Validate').css("border-color","#EBEBEB");
				$("#Validate").focus();				
				}, 1500);
		}
		else if(validDateNote == "" || validDateNote == null)
		{
			alert('missing field');
			$('#validNotes').css("border-color","red");
			setTimeout(function(){
           		$('#validNotes').css("border-color","#EBEBEB");
				$("#validNotes").focus();				
				}, 1500);
		}
		else
		{
			
			$.ajax({
				
					url:"dist/php/saveOfferValidition.php",
					type:"POST",
					data:{validationdate:validDateAdded, ValidJobRowId:validJobRID, validationNotes:validDateNote},
					beforeSend: function(){
						$("#saveValidInOfferBTN").prop('disabled', true);	
					},
					success: function(doneAddValidate){
						
						if(doneAddValidate == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $("#validity").removeClass("btn-dark");
							 $("#validity").addClass("btn-info");
							 $(".policyTable").show();
							 $(".validtyData").show();
							 $(".validtyTH").show();
							 $(".validtyData").html(validDateNote);
							 
						}
						else
						{
							alert(doneAddValidate);
							$("#saveValidInOfferBTN").prop('disabled', false);	
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