<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['jobRId'];

$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 6;
	$colspan1 = 2;
	$colspan2 = 4;
	$colspan3 = 4;
	
}
else
{
	$diplay = "none";
	$colspan = 4;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
}


$sqlGetJobData = "SELECT `startDate`, `customer`, `salesman`, `description`, `offerValue` FROM `job` 
WHERE `jobId` = $jobRowId";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_array($queryGetJobData);

$sqlGetCustName = "SELECT `customername` FROM `customers` WHERE `customercode` = $resultGetJobData[customer]";
$queryGetCustName = mysqli_query($link,$sqlGetCustName)or die("ERROR :02-ANJ_GCN_S");
$resultGetCustName = mysqli_fetch_array($queryGetCustName);

$sqlGetSalesName = "SELECT `username` FROM `users` WHERE `codeid` = $resultGetJobData[salesman]";
$queryGetSalesName = mysqli_query($link,$sqlGetSalesName)or die("ERROR :03-ANJ_GCN_S");
$resultGetSalesName = mysqli_fetch_array($queryGetSalesName);


?>

<style>
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
caption{
  color:blue;
  font-size:14px;
  font-weight:bold;
  caption-side: top;
  margin-left:4%;
	
}
$('').css({marginTop: '-=15px'});
</style>
<script type="text/javascript" src="dist/js/editAutomaticOfferData.js"></script>
<script type="text/javascript">

	$(document).ready(function() {
        
		$(".tooltip-inner").hide();
		$(".arrow").hide();
		
		var titelCustN = $("#CustName").val();
		var titelSalesN = $("#SalesName").val();
		var titelSDate = $("#SDate").val();
		var titelTotalVal = $("#TotalOffer").val();
     $('.m-0').html('');
	 $('.m-0').html('Add Offer Data <br> <p style="font-size:12px; color:black; with:100%;">Customer | <span style="color:red">'+titelCustN+'</span> - Sales | <span style="color:red">'+titelSalesN+'</span> - Start Date |  <span style="color:red">'+titelSDate+'</span> <span style="display:<?php echo $diplay;?>">- total Value= <span style="color:blue" class="TotalOffer">'+titelTotalVal+'</span> L.E</span></p>');
    });
	
$(".backBTN").click(function(){
	var permsion = $(".UserPermiss").val();
	if(permsion == 'Admin' || permsion == 'Manager')
	{
		$("#3_2").click();
	}
	else
	{
		$("#8_2").click();
	}
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	
	return false; 
	});	
</script>
<div class="BackFromEdit" style="margin-top:-4%">
<span data-toggle='tooltip' data-placement='right' title='back'>
<button class="btn btn-link btn-xs backBTN">
<i class="fas fa-arrow-circle-left" aria-hidden="true" style="font-size:26px;color:#0275d8"></i>
</button>
<button class="btn btn-link btn-xs backBTN2" style="display:none">
<i class="fas fa-arrow-circle-left" aria-hidden="true" style="font-size:26px;color:#0275d8"></i>
</button>
</span>
</div>
<div class="panel panel-primary" style="width:99%">
<div class="panel-heading">

</div>
    <div class="panel-body">
<input type="text" value="<?php echo $jobRowId;?>" id="jRowId" style="display:none"/>		
<input type="text" value="<?php echo $resultGetJobData['customer'];?>" id="CustCode" style="display:none"/>		
<input type="text" value="<?php echo $resultGetCustName['customername'];?>" id="CustName" 
style="display:none"/>
<input type="text" value="<?php echo $resultGetSalesName['username'];?>" id="SalesName" style="display:none"/>
<input type="text" value="<?php echo number_format($resultGetJobData['offerValue']);?>" 
id="TotalOffer" style="display:none"/>
 <input type="text" style="display:none;" id="rowIdDoorForEdit"/>
<input type="text" value="<?php echo date("d/m/Y",strtotime($resultGetJobData['startDate'])); ?>" 
id="SDate" style="display:none"/>
<input type="text" style="display:none;" class="UserPermiss" value="<?php echo $Permissiom?>"/>
<input type="text" style="display:none;" class="SearchKeyWordRes2"/>	
	<div class="firstForm">
    <table class="table table-sm">
      <thead class="bg-warning" >
        <th>Type</th>
        <th>Door Specs</th>
        <th>Motor Specs</th>
        <th style="display:<?php echo $diplay;?>">Price</th>
        <th>QTY</th>
        <th style="display:<?php echo $diplay;?>">Total</th>
      </thead>
      <tbody>
      <tr>
      	<td class='col-sm-2'>
        	<input type="text" id="SHType" class="form-control SHType fristForm" list="searchType2"/>
            <datalist id="searchType2"></datalist>
        </td>
       	<td class='col-sm-3'>
       		<textarea class="form-control DSpecs fristForm" id="DSpecs" style="height:150px"></textarea> 
       	</td>
        <td class='col-sm-3'>
       		<textarea class="form-control MSpecs fristForm" id="MSpecs" style="height:150px"></textarea> 
       	</td>
        <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        	<input type="number" id="itemPrice" class="form-control itemPrice fristForm" min="0" 
            value="0"/>
        </td>
        <td class='col-sm-1'>
        	<input type="number" id="itemQty" class="form-control itemQty fristForm" min="1"/>
        </td>
        <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        	<input type="number" id="Total" class="form-control-plaintext Total fristForm" value="0" readonly/>
        </td>
        </tr>
        <tr class="AddDoorOfferTR">
        	<td colspan="<?php echo $colspan;?>" align="center">
            <button class="btn btn-sm btn-success" id="AddShutterBtn">Save</button>
            </td>
        </tr>
        <tr class="EditDoorOfferTR" style="display:none">
        	<td colspan="<?php echo $colspan;?>" align="center">
            <button class="btn btn-sm btn-success" id="EditDoorOfferBtn">Save</button>
            </td>
        </tr>
      </tbody>
    </table>
    </div>
    
   <div class="addedAutoDoor"></div> 
</div>
</div>
