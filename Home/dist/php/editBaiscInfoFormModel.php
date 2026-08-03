<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 
 //echo "test";
 $JobRowId = $_POST['ModelEditBIJobRID'];
 
	 $sqlGetProjectN = "SELECT `startDate`, `projectName`, `customer`, `Commotion`, `description`, `jobtype`
	 , `salesman` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetProjectN = mysqli_query($link,$sqlGetProjectN)or die("ERROR :01-ANJ_GCN_S");
	$resGetProjectN = mysqli_fetch_assoc($queryGetProjectN);
	$projectName = 	$resGetProjectN['projectName'];
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetProjectN[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetSales="SELECT `username` FROM `users` WHERE `codeid` = $resGetProjectN[salesman]";
	$queryGetSales=mysqli_query($link,$sqlGetSales)or die("ERROR :01-AU_AU_S");
	$resGetSales= mysqli_fetch_assoc($queryGetSales);
	
	
?>

 <div class="modal-header">
        <h5 class="modal-title">Edit Offer Basic Information for Project <span style="color:blue;">&nbsp;
        <b><?php echo $projectName; ?>
        </b></span> 
        &nbsp;
        <button class="btn btn-danger btn-xs" id="deleteOfferBTN" value="<?php echo $JobRowId; ?>">Delete</button>
        </h5>       
      </div>
       <div class="modal-body ">
       <div class="table-responsive">
       	  <table class="table table-sm" style="width:99%" align="center">
	
    <thead class="bg-warning">
        <th>Customer</th>
        <th>Type</th>
        <th>Project Name</th>
        <th>Sales</th>
        <th>Commission</th>
        <th>Start</th>
    </thead>
    
    <tbody>
    <tr>
   <td>
    	<input type="text" list="customerList" class="form-control" id="ChoseCustName" 
        placeholder="Customer Name" value="<?php echo $resGetCustomer['customername'];?>" autocomplete="off">
        
        <datalist id="customerList">
        </datalist>
    </td>
    <td>
    	<select class="form-control" id="jobtype" style="width:auto">
        <option data-toggle="tooltip" data-placement="bottom"  value="<?php echo $resGetProjectN['jobtype'];?>">
		<?php echo $resGetProjectN['jobtype'];?></option>
        <option data-toggle="tooltip" data-placement="bottom"  value="Doors">Doors</option>
        <option data-toggle="tooltip" data-placement="bottom"  value="Automatic">Automatic Door</option>
        <option data-toggle="tooltip" data-placement="bottom"  value="Stock">Stock</option>
        <option data-toggle="tooltip" data-placement="bottom"  value="Maintenance">Maintenance</option>
        </select>
    </td>
     <td>
    	<input type="text"  class="form-control" id="projName" placeholder="Project Name"
        autocomplete="off" value="<?php echo $resGetProjectN['projectName'];?>">
    </td>
    <td>
    	<input type="text" list="SalesList" class="form-control" id="SalesName" placeholder="Sales Name"
        autocomplete="off" value="<?php echo $resGetSales['username'];?>">
        
        <datalist id="SalesList">
        </datalist>
    </td>
     <td  width="150px">
        <select class="form-control" id="SalesCommCode">
        <option value="<?php echo $resGetProjectN['Commotion'];?>">%
		<?php echo ($resGetProjectN['Commotion'] * 100);?></option>
        <option value=".03">%3</option>
        <option value=".02">%2</option>
        <option value=".01">%1</option>
        <option value="0">%0</option>
        </select>
    </td>
    <td>
    	<input type="date" class="form-control" id="OfferStartDate" 
        value="<?php echo $resGetProjectN['startDate'];?>">
    </td>
    </tr>
    <tr class="bg-warning">
    <td colspan="7"><b>Description</b></td>
    </tr>
    <tr>
    <td colspan="7">
    	<textarea id="jobDescrip" class="form-control"><?php echo trim($resGetProjectN['description']);?></textarea>
    </td>
    </tr>
    </tbody>
</table>
<center><button class="btn btn-sm btn-success" id="EditBIOfferBtn">Save</button></center>

       </div>
 <input type="text" value="<?php echo $JobRowId;?>" style="display:none" id="rowIdJobBIEdit"/>
  </div>
  </div>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 

$('#customerList').load("dist/php/allCustCode.php");
$('#SalesList').load("dist/php/getSalesCode.php");
 
						
$("#EditBIOfferBtn").click(function(){


var jobBasicIRowId = $("#rowIdJobBIEdit").val();


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
	var jType = $("#jobtype").val();
	var jPjtName = $("#projName").val();
	//var jAttPers=$('#AttentionPers').val();
	var jStartDate = $("#OfferStartDate").val();
	//jReqRef=jReqRef.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	var DescribJob = $("#jobDescrip").val();
	var SalesCommCode = $("#SalesCommCode").val();
	
	DescribJob=DescribJob.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
	
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
		
	  else if( jType == "")
	  {
		alert('missing field');
			$('#jobtype').css("border-color","red");
			setTimeout(function(){
           		$('#jobtype').css("border-color","#EBEBEB");
				$('#jobtype').focus();				
				}, 1500);
	  }
	  else if( jPjtName == "")
	  {
		alert('missing field');
			$('#projName').css("border-color","red");
			setTimeout(function(){
           		$('#projName').css("border-color","#EBEBEB");
				$('#projName').focus();				
				}, 1500);
	  }
	  else if( jStartDate == "")
	  {
		alert('missing field');
			$('#OfferStartDate').css("border-color","red");
			setTimeout(function(){
           		$('#OfferStartDate').css("border-color","#EBEBEB");
				$('#OfferStartDate').focus();				
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
		else
		{
			$.ajax({
				
					url:"dist/php/saveEditBIOffer.php",
					type:"POST",
					data:{custNameJ:jCustm ,SalesNJ:jSalesName, jobType:jType, jobName:jPjtName,salesComm:SalesCommCode,jSDate:jStartDate, jobDesc:DescribJob,jobBIRowId:jobBasicIRowId},
					
					beforeSend: function(){
						$("#EditBIOfferBtn").prop("disabled",true);	
					},
					
				success: function(doneEditOffer){
				   
				    if(doneEditOffer == 1)
					{
						 alert("Data Saved");
					  $('.ShowHWDataExpt').html('');
					  $(".myModal").modal('toggle');
						setTimeout(function(){				
							$("#editDoorsPriceBTN").prop('disabled', false);
							$("#3_2").click();
      					}, 1500);
					}

					else if(doneEditOffer == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "../";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#EditBIOfferBtn").prop('disabled', false);
						alert(doneEditOffer);
					}
			}
				
				});
		}
	
	});
	
	$("#deleteOfferBTN").click(function(){
		
		var deleteJobRID = $(this).val();
		
		var confirmDeleteJob = confirm("confirm delete all Offer Data (Item & hardware set)??");
		
		if(confirmDeleteJob === true)
		{
			$.ajax({
					
					url:"dist/php/removeAllJobData.php",
					type:"POST",
					data:{DJobRowId:deleteJobRID},
					beforeSend: function(){
					
					$("#deleteOfferBTN").prop("disabled",true);		
					},
					success: function(doneRemoveJob){
						
						if(doneRemoveJob == 1)
						{
							$.ajax({
								
								url:"dist/php/updateWHLookUp.php",
								type:"GET",
								beforeSend: function(){
									
								$('.ShowData').html("<center><img src='dist/img/loadingColor.gif' alt='loading'><br><h3>Please Wait System Updating Stock </h3></center>"); 
								},
								
							success: function(doneLoadStock){
							
							alert("Data Saved");
						  
						   $('.ShowHWDataExpt').html('');
						   $(".myModal").modal('toggle');
						   setTimeout(function(){				
							$("#deleteOfferBTN").prop('disabled', false);
							$("#3_2").click();
      					   }, 500);
							}
							});
						
						}
						
						else if(doneRemoveJob == 2)
						{
							alert("Un-Expected Error!!");
						   $("#deleteOfferBTN").prop('disabled', false);
						}
						else if(doneRemoveJob == 9)
						{
							alert("Sorry Session expired please re-login again");
							
							setTimeout(function(){
							var ref1 = "../";
							window.location.href= ref1;
												
							}, 1500);
						}
						else
						{
							$("#deleteOfferBTN").prop('disabled', false);
							alert(doneRemoveJob);
						}
						
					}
				
				
				});
		}
		
		return false;
		});
});
 
 </script>