<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $JobRowId = $_POST['ModelJobRIDAtt'];
 
 $sqlGetAllNewJob="SELECT `customer` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetAttCurrent="SELECT `attdName` FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryGetAttCurrent=mysqli_query($link,$sqlGetAttCurrent)or die("ERROR :02-AU_AU_S");
	if(mysqli_num_rows($queryGetAttCurrent) > 0)
	{
	 	$resGetAttCurrent= mysqli_fetch_assoc($queryGetAttCurrent);
		
		$attName = $resGetAttCurrent['attdName'];
	}
	else
	{
		$attName = "";
	}
?>
 <div class="modal-header">
        <h5 class="modal-title">Add&nbsp;<span style="color:blue;"><?php echo $resGetCustomer['customername'];?></span>&nbsp; Attintion Name For Offer</h5>
        
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:50%" align="center">
   
      <thead class="bg-warning">
       	<th>Add Name</th>
      </thead>
      <tbody>
		<td>
        	<input type="text" class="form-control" id="allAttName" list="AttNameList" 
            value="<?php echo $attName;?>"/>
				<datalist id="AttNameList">
<?php 
	 $sqlGetAtt="SELECT `attdName` FROM `offerpolicy` WHERE `custcode` = $resGetAllNewJob[customer] ";
	$queryGetAtt=mysqli_query($link,$sqlGetAtt)or die("ERROR :02-AU_AU_S");
	while($resGetAtt = mysqli_fetch_assoc($queryGetAtt))
	{
		echo "
				<option value='$resGetAtt[attdName]'>
			";
	}
 }
 ?>
 </datalist>
 </td>
 <tr>
 	<td align="center">	
    	<button class="btn btn-sm btn-success" id="saveAttInOfferBTN">Save</button>
    </td> 
  </tr>
  </tbody>
     
 </table>
 </div>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoadAtt"/>
 <script type="text/javascript">
 $(document).ready(function() {

	$("#saveAttInOfferBTN").click(function(){
		
		var attJobRID = $("#rowIdJobLoadAtt").val();
		var attNameAdded = $("#allAttName").val();
		//
		if(attNameAdded == "" || attNameAdded == null)
		{
			alert('missing field');
			$('#allAttName').css("border-color","red");
			setTimeout(function(){
           		$('#allAttName').css("border-color","#EBEBEB");
				$("#allAttName").focus();				
				}, 1500);
		}
		else
		{
			
			$.ajax({
				
					url:"dist/php/saveOfferAtt.php",
					type:"POST",
					data:{AttOfferName:attNameAdded, AttJobRowId:attJobRID},
					beforeSend: function(){
						$("#saveAttInOfferBTN").prop('disabled', true);	
					},
					success: function(doneAddAtt){
						
						if(doneAddAtt == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $("#Attention").removeClass("btn-secondary");
							 $("#Attention").addClass("btn-info");
							 $(".policyTable").show();
							 $(".attTH").show();
							 $(".attData").show();
							 $(".attData").html(attNameAdded);
							 
						}
						else
						{
							alert(doneAddAtt);
							$("#saveAttInOfferBTN").prop('disabled', false);	
						}
						
					}
				});
		}
		
		return false;
		});
});
 
 </script>