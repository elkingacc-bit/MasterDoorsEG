<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['jobRId'];
$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 10;
	$colspan1 = 2;
	$colspan2 = 2;
	$colspan3 = 2;
	
}
else
{
	$diplay = "none";
	$colspan = 10;
	$colspan1 = 2;
	$colspan2 = 2;
	$colspan3 = 2;
}

$sqlGetJobData = "SELECT `startDate`, `projectName`,`customer`, `salesman`, `description`, `offerValue` FROM `job` 
WHERE `jobId` = $jobRowId";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_assoc($queryGetJobData);

$sqlGetCustName = "SELECT `customername` FROM `customers` WHERE `customercode` = $resultGetJobData[customer]";
$queryGetCustName = mysqli_query($link,$sqlGetCustName)or die("ERROR :02-ANJ_GCN_S");
$resultGetCustName = mysqli_fetch_assoc($queryGetCustName);

$sqlGetSalesName = "SELECT `username` FROM `users` WHERE `codeid` = $resultGetJobData[salesman]";
$queryGetSalesName = mysqli_query($link,$sqlGetSalesName)or die("ERROR :03-ANJ_GCN_S");
$resultGetSalesName = mysqli_fetch_assoc($queryGetSalesName);


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

</style>
<script type="text/javascript" src="dist/js/editDoorsOfferData.js"></script>
<script type="text/javascript">

	$(document).ready(function() {
        
		$(".tooltip-inner").hide();
		$(".arrow").hide();
		
		var titelCustN = $("#CustName").val();
		var titelProject = $("#PrjtName").val();
		var titelSalesN = $("#SalesName").val();
		var titelSDate = $("#SDate").val();
		var titelTotalVal = $("#TotalOffer").val();
     $('.m-0').html('');
	 $('.m-0').html('Add Offer Data <br> <p style="font-size:12px; color:black; with:100%;">Customer | <span style="color:red">'+titelCustN+' '+titelProject+'</span> - Sales | <span style="color:red">'+titelSalesN+'</span> - Start Date |  <span style="color:red">'+titelSDate+'</span> <span style="display:<?php echo $diplay;?>">- total Value= <span style="color:blue" class="TotalOffer">'+titelTotalVal+'</span> L.E</span></p>');
   
	
/*$(".backBTN").click(function(){
	
	$("#3_2").click();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	
	return false; 
	});	*/
	
	$(".mSqPrice").dblclick(function(){
		
		var Premission = $(".UserPermiss").val();
		
		if(Premission == "Admin" || Premission == "Manager")
		{
			$(this).removeAttr('readonly');
		}
		
		
		return false;
		});
		
		
	$(".descPrice").dblclick(function(){
		
		var Premission2 = $(".UserPermiss").val();
		
		if(Premission2 == "Admin" || Premission2 == "Manager")
		{
			$(this).removeAttr('readonly');
		}
		
		
		return false;
		});	
	


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
<input type="text" value="<?php echo $jobRowId?>" id="jRowId" style="display:none"/>		
<input type="text" value="<?php echo $resultGetJobData['customer']?>" id="CustCode" style="display:none"/>		
<input type="text" value="<?php echo $resultGetCustName['customername']?>" id="CustName" style="display:none"/>
<input type="text" value="<?php echo $resultGetSalesName['username']?>" id="SalesName" style="display:none"/>
<input type="text" value="<?php echo $resultGetJobData['offerValue']?>" id="TotalOffer" style="display:none"/>

<input type="text" value="<?php echo $resultGetJobData['projectName']?>" id="PrjtName" style="display:none"/>
 <input type="text" style="display:none;" class="itemNewRowId"/>
 <input type="text" style="display:none;" class="UserPermiss" value="<?php echo $Permissiom?>"/>
<input type="text" value="<?php echo date("d/m/Y",strtotime($resultGetJobData['startDate'])); ?>" 
id="SDate" style="display:none"/>		
	<div class="firstForm table-responsive">
    <table class="table table-sm">
      <thead class="bg-warning" >
        <th>Type</th>
        <th>Item</th>
        <th>Width</th>
        <th>Hight</th>
        <th>Depth</th>
       	<th style='display:none' class='col-sm-1'>Handling</th>
        <th style='display:none' class='col-sm-1'>Door N0.</th>
        <th colspan="<?php echo $colspan1;?>">F.R Min.</th>
        <th colspan="<?php echo $colspan2;?>">Remarks</th>
        <th style='display:none'>RAL</th>
       
        
      </thead>
      <tbody>
      <tr>
      	<td class='col-sm-1'>
        	<input type="text" id="itemType" class="form-control itemType fristForm" list="searchType" 
            autocomplete="off"/>
            <datalist id="searchType"></datalist>
        </td>
        <td class='col-sm-2'>
        	<input type="text" id="itemName" class="form-control itemName fristForm" autocomplete="off"/>
        </td>
       
        <td class='col-sm-1'>
        	<input type="number" id="itemWidth" class="form-control itemWidth fristForm required-entry" min="0.01" step="0.01" />
        </td>
         <td class='col-sm-1'>
        	<input type="number" id="itemHight" class="form-control itemHight fristForm required-entry" min="0.01" step="0.01"/>
        </td>
        <td class='col-sm-1'>
        	<input type="number" id="itemDepth" class="form-control itemDepth fristForm" />
        </td>
           
         <td style='display:none' >
        	<input type="text" id="Handl" class="form-control Handl fristForm" autocomplete="off"/>
        </td>
         <td style='display:none'>
        	<input type="text" id="DoorNum" class="form-control DoorNum fristForm" autocomplete="off"/>
        </td>
        <td colspan="<?php echo $colspan1;?>">
        	<input type="number" id="FRMin" class="form-control FRMin fristForm" min='1'/>
        </td>
         <td  colspan="<?php echo $colspan2;?>">
        	<input type="text" id="Remarks" class="form-control Remarks fristForm" autocomplete="off"/>
        </td>
        <td style='display:none'>
        	<input type="text" id="ral" class="form-control ral fristForm" autocomplete="off"/>
        </td>

        </tr>
        <tr class="bg-warning">
        <th style='display:none' colspan="<?php echo $colspan3;?>">Frame Overlap</th>
        <th style="display:<?php echo $diplay;?>">Margin</th>
        <th >M<sup>2</sup></th>
        <th style="display:<?php echo $diplay;?>">M<sup>2</sup>&nbsp;Cost</th>
        <th style="display:<?php echo $diplay;?>">Shipping</th>
        <th style="display:<?php echo $diplay;?>">Install</th>
        <th>QTY</th>
        <th style="display:<?php echo $diplay;?>" class='col-sm-1'>Item Price</th>
        <th colspan="2" style="display:<?php echo $diplay;?>" class='col-sm-2'>Total</th>
        
        </tr>
        <tr >
        <td style='display:none' colspan="<?php echo $colspan3;?>">
        	<input type="text" id="Overlap" class="form-control Overlap fristForm" />
        </td>  
        <td class='col-sm-2' style="display:<?php echo $diplay;?>">
      		<div class="input-group">
              <input type="number" class="form-control margin fristForm required-entry" id="margin" 
              aria-label="%" list="presntageVal" value="0" >
              <datalist id="presntageVal">
              <?php 
			  	for($p = 1; $p <= 400; $p++)
				{
					echo "<option value='$p'>";
				}
			  
			  ?>
              </datalist>
              <div class="input-group-append">
                <span class="input-group-text">%</span>
              </div>
            </div>
        </td>
        <td class='col-sm-1'>
        	<input type="number" id="itemMSq" class="form-control-plaintext itemMSq fristForm required-entry" step="0.01"
             readonly/>
        </td>
        <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        	<input type="number" id="mSqPrice" class="form-control-plaintext mSqPrice required-entry" min="1" 
             step="0.00" readonly/>
        </td>
        
          <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        	<input type="number" id="shipping" class="form-control shipping fristForm required-entry" min="0" 
            step="0.00" value="0"/>
        </td>
        
        <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        	<input type="number" id="Installation" class="form-control Installation fristForm required-entry"
             value="0"/>
        </td>
         <td class='col-sm-1'>
         	<input type="number" id="itemQty" class="form-control itemQty fristForm required-entry" min="1"/>
        </td>
         <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        	<input type="number" id="MPrice" class="form-control-plaintext MPrice fristForm required-entry" 
            min="0.01" step="0.01" style="font-size:12px"  />
        </td>
        
        <td colspan="2" class='col-sm-2' style="display:<?php echo $diplay;?>">
        	<input type="number" id="Total" class="form-control Total fristForm" 
             value="0" style="font-size:12px" />
        </td>
        
        </tr>
        <tr class="EditItemOfferTR">
        	<td colspan="<?php echo $colspan;?>" align="center">
            <button class="btn btn-sm btn-dark" id="EditItemOfferBtn">Add</button>
            </td>
        </tr>
        <tr class="EditItemInOfferTR" style="display:none" >
        	<td colspan="<?php echo $colspan;?>" align="center">
            <button class="btn btn-sm btn-success" style="display:none" id="EditItemInOfferTR">
            Save</button>
            </td>
        </tr>
        <tr class="addMoreItemToOfferTR" style="display:none">
        	<td colspan="<?php echo $colspan;?>" align="left">
            <button class="btn btn-sm btn-danger" style="display:none" id="addMoreItemToOfferBtn">
            Finish & Add New More Item</button>
            </td>
        </tr>
      </tbody>
    </table>
    </div>
</div>
<div class="panel-body">
   <div class="d-flex align-items-center row">   
     <div class="d-inline-block col-6">
      <div class="selectedHW" style="display:none;">
      
      <div class="" align="center">Add New Group for Reqiered HW&nbsp;
      <table>
      <td>
      <button class="btn btn-sm btn-warning AssignAsKit" id="AssignAsKit">Assembly Kit</button>
      </td>
      <td> 
      <button class="btn btn-sm btn-warning AssignHWRef" id="AssignHWRef">Assign Group</button>
      </td>
      </table>
      </div>
      
      <table class="table table-sm caption-top addHWTableCalss" style=" width:100%; font-size:10px;">
      
      	<thead class="bg-info ">
        	<th>Part No</th>
            <th>Name</th>
            <th>QTY</th>
            <th style="display:<?php echo $diplay;?>">Price</th>
            <th style="display:<?php echo $diplay;?>">Total</th>
        </thead>
      	<tbody class="" style="font-size:12px">
        	<td  class='col-sm-2'>
            	<input type="text" id="partNo" class="form-control partNo sndForm" list="AllPartNum" 
                style="font-size:12px"/>
                <datalist id="AllPartNum"></datalist>
            </td>
            <td class='col-sm-2'>
            	<input type="text" id="ItemName" class="form-control ItemName sndForm" list="showAllItems"
                 style="font-size:12px"/>
                <datalist id="showAllItems"></datalist>
            </td>
            <td class='col-sm-1'>
        		<input type="number" id="descQty" class="form-control descQty sndForm" min="1" 
                 style="font-size:12px"/>
       	 	</td>
            <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        		<input type="number" id="descPrice" class="form-control descPrice sndForm" min="0.01" 
                step="0.01"  style="font-size:12px" value="0"/>
       	 	</td>
            <td class='col-sm-1' style="display:<?php echo $diplay;?>">
        		<input type="number" id="totalPrice" class="form-control-plaintext totalPrice sndForm" 
                 style="font-size:12px" readonly value="0"/>
       	 	</td>
            <tr>
            <td colspan="5" align="center">
            	<button class="btn btn-sm btn-primary" id="addItemHWBtn">Add</button>
            </td>
            </tr>
        </tbody>
      </table>
      
      </div> 
     <div class="addHWRefCalss" style="display:none;">
         
     </div>     
     </div>
      <div class="d-inline-block col-6">       
       <div align="right" class="HWadded" style="display:none;"></div> 
      </div>                 
    </div>
    
   <div class="oldAddItems"></div> 
  </div>  
</div>
 <input type="text" value="" style="display:none" id="rowIdItemForEdit"/>