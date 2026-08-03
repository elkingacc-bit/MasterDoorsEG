<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$orderRowId = $_POST['noteSORowId'];

$sqlGetAllNewSuppOrder="SELECT  `SuppCode`, `OrderNumber`, `orderNotes`, `custPOId` FROM `supplierorder` 
	 WHERE `SOId` = $orderRowId ";
	$queryGetAllSuppOrder=mysqli_query($link,$sqlGetAllNewSuppOrder)or die("ERROR :01-AU_AU_S");
	$resGetAllSuppOrder= mysqli_fetch_assoc($queryGetAllSuppOrder);
	
	$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
	$resGetAllSuppOrder[SuppCode]";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :03-AU_AU_S");
	$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);
	
	$sqlGetCustPO="SELECT `custCode`, `PoNum`, `orderType`, `jobidref` FROM `customerpo` 
	WHERE `poId` = $resGetAllSuppOrder[custPOId]";
	$queryGetCustPO=mysqli_query($link,$sqlGetCustPO)or die("ERROR :04-AU_AU_S");
	$resGetCustPO= mysqli_fetch_assoc($queryGetCustPO);
	
	$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` = $resGetCustPO[jobidref]";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :05_1-AU_AU_S");
	$resGetProject= mysqli_fetch_assoc($queryGetProject);
	
	$project = $resGetProject['projectName'];
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetCustPO[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :05-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);

?>
 <input type="text" value="<?php echo $orderRowId;?>" style="display:none" id="noteSOrderRID"/>
  <div class="modal-header">
        <h5 class="modal-title">Edit Supply Order Note <br>
        Maunfacture:&nbsp;<span style="color:blue"><?php echo $resGetSupplier['suppliername'];?></span>&nbsp;
        Order:&nbsp;<span style="color:blue"><?php echo $resGetCustPO['OrderNumber'];?></span>&nbsp;
        Project:&nbsp;<span style="color:blue"><?php echo $project;?></span>&nbsp;
        Customer:&nbsp;<span style="color:blue"><?php echo $resGetCustomer['customername'];?></span>&nbsp;
        
        </h5>
      </div>
 <div class="modal-body">
  
 <div class="table-responsive">
 <table class=" table  table-sm" cellspacing="0" width="99%">
        <td>
            <textarea class="form-control" id="OrderNoteEdit"><?php 
            //echo $resGetAllSuppOrder['orderNotes'];
            ?></textarea>
        </td>
	<tr>
        <td align="center">
        <button class="btn btn-sm btn-success" id="saveEditNoteBTN">Save</button>
        </td>
    </tr>
 </table> 
 
 </div>
 <script type="text/javascript">
 	$(document).ready(function() {
 
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
        
		
		
	$("#saveEditNoteBTN").click(function(){
		var suppOrderNoteID = $('#noteSOrderRID').val();
		var updatedNote = $('#OrderNoteEdit').val();
		//updatedNote = updatedNote.replace(/^\s+|\s+$|\s+(?=\s)/g, "");	
		
		if(updatedNote == "")
		{
			alert('missing field');
				$("#OrderNoteEdit").css("border-color","red");
				setTimeout(function(){
				   $("#OrderNoteEdit").css("border-color","#EBEBEB");    						
				   $("#OrderNoteEdit").focus();							
				}, 1500);
		}
		
		else
		{
			$.ajax({
					url:"dist/php/saveEditSuppOrderNote.php",
					type:"POST",
					data:{SuppORID:suppOrderNoteID,NewUpdatedNote:updatedNote},
					beforeSend: function(){
							$("#saveEditNoteBTN").prop('disabled', true);		
						},
						success: function(doneEditNote){
							
							if(doneEditNote == 1)
							{
								alert("Data Saved");
								
								$("#saveEditNoteBTN").prop('disabled', false);	
								$(".editForm").hide();
								$('.ShowData').html('');
								$(".myModal").modal('toggle');
								setTimeout(function(){
								$('.SuppOrderEdit').html('');
								$('.SuppOrderEdit').load("dist/php/allSuppOrder.php");
								}, 1000);
							}
							else
							{
								alert(doneEditNote);
								$("#saveEditNoteBTN").prop('disabled', false);	
							}
							
						}
				
				});
			
		}
			return false;
			});		
    });
 
 
 </script>  
