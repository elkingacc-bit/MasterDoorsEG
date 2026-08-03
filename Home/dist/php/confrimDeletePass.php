<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$orderRowId = $_POST['DOrederRID'];
$userId = $_SESSION['id'];
 
?>
<input type="text" id="UsID" value="<?php echo $userId; ?>" style="display:none" />
<input type="text" id="ORIDD" value="<?php echo $orderRowId; ?>" style="display:none" />
<div class="modal-header">

        <h5 class="modal-title">Confirmation Password</b>
        </h5>
</div>
  <div class="modal-body "> 
  	<center>
    	<input type="password" id="confirmPass" autocomplete="new-password" class="form-control" />
        <br>
        <button class="btn btn-sm btn-dark" id="confrimDeletePassBTN">Delete</button>
    </center>
  </div>
  
<script type="text/javascript">
	
	$(document).ready(function() {
        
		
	$("#confrimDeletePassBTN").click(function(){
		var confirmedORID = $("#ORIDD").val();
		var UsRID = $("#UsID").val();
		var PassVal = $("#confirmPass").val();
		
		if(PassVal == "")
		{
			alert('missing field');
				$('#confirmPass').css("border-color","red");
				setTimeout(function(){
					$('#confirmPass').css("border-color","#EBEBEB");
					$('#confirmPass').focus();				
				}, 1500);
		}
		else
		{
			$.ajax({
				
					url: "dist/php/removeAllOrdersAndCloseOffer.php",
					type:"POST",
					data: {COdRID:confirmedORID,Userid:UsRID,pasVal:PassVal},
					beforeSend: function(){
						$("#confrimDeletePassBTN").prop('disabled', true);		
					},
					success: function(doneDeleteClosed){
						
						if(doneDeleteClosed == 1)
						{
							alert("Data Saved");
						
						$('.ShowData').html('');
						
						
						$.ajax({
								
								url:"dist/php/updateWHLookUp.php",
								type:"GET",
								beforeSend: function(){
									
								$('.ShowData').html("<center><img src='dist/img/loadingColor.gif' alt='loading'><br><h3>Please Wait System Updating Stock </h3></center>");
								},
								
								success: function(doneLoadStock){
									$(".myModal").modal('toggle');
									setTimeout(function(){
										$('.custOrderDiv').html('');
										$('.custOrderDiv').load("dist/php/custOrderRpt.php");
									}, 500);
							
								}
							});
						
						}
						
						else if(doneDeleteClosed == 0)
						{
							alert("incorrect Password");
							$("#confrimDeletePassBTN").prop('disabled', false);
						}
						else if(doneDeleteClosed == 9)
						{
							alert("Sorry Session expired please re-login again");
							
							setTimeout(function(){
							var ref1 = "/../MasterDoorsEG/";
							window.location.href= ref1;
												
							}, 1500);
						}
						else
						{
							alert(doneDeleteClosed);
							$("#confrimDeletePassBTN").prop('disabled', false);
						}
						
					}
				
				});
		}
		
		return false;
		});
		
    });

</script>  