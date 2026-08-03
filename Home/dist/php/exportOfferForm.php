<?php
 @session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 
 $Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 10;
	$colspan1 = 2;
	$colspan2 = 4;
	$colspan3 = 4;
	
}
else
{
	$diplay = "none";
	$colspan = 7;
	$colspan1 = 1;
	$colspan2 = 3;
	$colspan3 = 3;
}
 
 $jobRowIdExp = $_POST['JRIDforExport'];
 
$sqlGetJobData = "SELECT `startDate`, `jobtype`,`customer`, `salesman`, `description`, `offerValue` FROM `job` 
WHERE `jobId` = $jobRowIdExp";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_array($queryGetJobData);

$sqlGetCustName = "SELECT `customername` FROM `customers` WHERE `customercode` = $resultGetJobData[customer]";
$queryGetCustName = mysqli_query($link,$sqlGetCustName)or die("ERROR :02-ANJ_GCN_S");
$resultGetCustName = mysqli_fetch_array($queryGetCustName);

$sqlGetSalesName = "SELECT `username` FROM `users` WHERE `codeid` = $resultGetJobData[salesman]";
$queryGetSalesName = mysqli_query($link,$sqlGetSalesName)or die("ERROR :03-ANJ_GCN_S");
$resultGetSalesName = mysqli_fetch_array($queryGetSalesName);

 
?>
<script type="text/javascript" >
$(document).ready(function() {
    $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
 var dept3 = $("#userPermission3").val();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	
var jobRowIdForCheckP = $("#jRowId").val();
$.ajax({
		
		url:"dist/php/checkPolicydata.php",
		type:"POST",
		data:{JRIDFCP:jobRowIdForCheckP},
		dataType: "json",
		cache: false,
		success: function(showAvailibleData){
		
			if(showAvailibleData.Ref == 1)
			{
				//$("#Attention").removeClass("btn-secondary");
				//$("#Attention").addClass("btn-info");
				$(".policyTable").show();
				$(".attTH").show();
				$(".attData").show();
				$(".attData").html(showAvailibleData.AttN);
				
				if(showAvailibleData.ValidNote != null || showAvailibleData.ValidNote != "")
				{
					 //$("#validity").removeClass("btn-secondary");
					 //$("#validity").addClass("btn-info");
					 $(".policyTable").show();
					 $(".validtyTH").show();
					 $(".validtyData").show();
					 $(".validtyData").html(showAvailibleData.ValidNote);
				}
				
				if(showAvailibleData.DelivryNote != null || showAvailibleData.DelivryNote != "")
				{
					 //$("#Delivery").removeClass("btn-secondary");
					 //$("#Delivery").addClass("btn-info");
					 $(".policyTable").show();
					 $(".deliveryTH").show();
					 $(".deliveryData").show();
					 $(".deliveryData").html(showAvailibleData.DelivryNote);
				}
				
				if(showAvailibleData.DownPay != 0 )
				{
					 //$("#Payment").removeClass("btn-secondary");
					 //$("#Payment").addClass("btn-info");
					 $(".policyTable").show();
					 $(".paymentTH").show();
					 $(".paymentData").show();
					 $(".paymentData").html(showAvailibleData.DownPay+"  at time of order <br>"+showAvailibleData.ReceivedPay+"upon delivery before installation Delivery payment must be paid within a week from delivery date.<br>"+showAvailibleData.LastPay+" AFTER INSTALLATION");
				}
				
				if(showAvailibleData.Notes != "")
				{
					 //$("#Note").removeClass("btn-secondary");
					 //$("#Note").addClass("btn-info");
					 $(".policyTable").show();
					 $(".noteTH").show();
					 $(".noteData").show();
					 $(".noteData").html(showAvailibleData.Notes);
					 $(".exportBtn").show();
				}
				
			}
			
		}
	
	});	
	
			
		var titelCustN = $("#CustName").val();
		var titelSalesN = $("#SalesName").val();
		var titelSDate = $("#SDate").val();
		var titelTotalVal = $("#TotalOffer").val();
     $('.m-0').html('');
	 $('.m-0').html('Export Offer Form <br> <p style="font-size:12px; color:black; with:100%;">Customer | <span style="color:red">'+titelCustN+'</span> - Sales | <span style="color:red">'+titelSalesN+'</span> - Start Date |  <span style="color:red">'+titelSDate+'</span><span style="display:<?php echo $diplay;?>;">- total Value= <span style="color:blue" class="TotalOffer">'+titelTotalVal+'</span> L.E</span></p>');
	
 var table = $('.myTableExpt').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: false ,
		  
 
   "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 14 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 14, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
			
        $(api.column( 14 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

});
	 $('.showallHWMoadel').click(function () {
       
		var jobRowIDHWExpt =  $(this).val().split(',')[0];
		var itemRefHWExpt = $(this).val().split(',')[1];
		var itemRowIdHWExpt = $(this).val().split(',')[2];
		var jobTypeHW = $("#jTypeHW").val();
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/showAssignedHWModelExpt.php',
                type:'POST',
                data:{ModelJobRID:jobRowIDHWExpt, ModelItemHWRef:itemRefHWExpt, ModelItemRID:itemRowIdHWExpt, ModelJobType:jobTypeHW},
                
				success: function(showHWDataExpt)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExpt);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
	});
	
	
	$('#Attention').click(function () {
       
		var jobRowIDHWExptAtt =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferAttintion.php',
                type:'POST',
                data:{ModelJobRIDAtt:jobRowIDHWExptAtt},
                
				success: function(showHWDataExptAtt)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExptAtt);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		return false;
	});
	
	$('#validity').click(function () {
       
		var jobRowIDHWExptVaild =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferValidatiy.php',
                type:'POST',
                data:{ModelJobRIDValid:jobRowIDHWExptVaild},
                
				success: function(showHWDataExptValid)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExptValid);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		return false;
	});
	
	$('#Delivery').click(function () {
       
		var jobRowIDHWExptDeliv =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferDelivery.php',
                type:'POST',
                data:{ModelJobRIDDeliv:jobRowIDHWExptDeliv},
                
				success: function(showHWDataExptDeliv)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExptDeliv);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		return false;
	});
	
	$('#Payment').click(function () {
       
		var jobRowIDHWExptPay =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferPayment.php',
                type:'POST',
                data:{ModelJobRIDPay:jobRowIDHWExptPay},
                
				success: function(showHWDataExptPay)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExptPay);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		return false;
	});
	
	$('#Payment').click(function () {
       
		var jobRowIDHWExptPay =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferPayment.php',
                type:'POST',
                data:{ModelJobRIDPay:jobRowIDHWExptPay},
                
				success: function(showHWDataExptPay)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExptPay);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
		return false;
	});
	
	$('#Note').click(function () {
       
		var jobRowIDHWExptNote =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferNote.php',
                type:'POST',
                data:{ModelJobRIDNote:jobRowIDHWExptNote},
                
				success: function(showHWDataExptNote)
				{
				//alert(showHWData);
                $('.ShowHWDataExpt').html('');
                $('.ShowHWDataExpt').html(showHWDataExptNote);
				$(".myModal").modal('toggle');
				$(".exportBtn").show();
				}         
        	}); 
		return false;
	});
	
	$('#exportItemOffer').click(function () {
       
		var jobRowIDExport =  $("#jRowId").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferExport.php',
                type:'POST',
                data:{jobRowIdOfferExport:jobRowIDExport},
				beforeSend: function(){
					$('#exportItemOffer').prop("disabled", true);
				},
                
				success: function(doneExportedOffer)
				{
					if(doneExportedOffer == 1)
						{
						var newDocPrint = window.open("dist/php/printItemOffer.php?&JobId="+jobRowIDExport,"_balnk");							
						setTimeout(function(){
							newDocPrint.focus();
						}, 500);
						setTimeout(function(){
							$('.m-0').html('');
	 						$('.m-0').html('Export Offer');
							$('.allOffersExpt').html('');
							$('.allOffersExpt').load("dist/php/allOffersForExport.php");
						}, 1500);
					}
					else
					{
						alert(doneExportedOffer);
						$('#exportItemOffer').prop("disabled", false);
					}
				}
        	}); 
		return false;
	});



});

	 


</script>
 <input type="text" value="<?php echo $Permissiom?>" style="display:none" id="userPermission3"/>

<input type="text" value="<?php echo $jobRowIdExp;?>" id="jRowId" style="display:none"/>
<input type="text" value="<?php echo $resultGetJobData['jobtype'];?>" id="jTypeHW" style="display:none"/>
<input type="text" value="<?php echo $resultGetJobData['customer'];?>" id="CustCode" style="display:none"/>		
<input type="text" value="<?php echo $resultGetCustName['customername'];?>" id="CustName" style="display:none"/>
<input type="text" value="<?php echo $resultGetSalesName['username'];?>" id="SalesName" style="display:none"/>
<input type="text" value="<?php echo $resultGetJobData['offerValue'];?>" id="TotalOffer" style="display:none"/>
<input type="text" value="<?php echo date("d/m/Y",strtotime($resultGetJobData['startDate'])); ?>" 
id="SDate" style="display:none"/>		
<div class="panel panel-primary" style="width:99%">
<div class="panel-heading">
    <div class="btn-group" role="group" aria-label="Basic example">
      <button type="button" class="btn btn-xs btn-secondary" id="Attention">Attention</button>
      <button type="button" class="btn btn-xs btn-secondary" id="validity">validity</button>
      <button type="button" class="btn btn-xs btn-secondary" id="Delivery">Delivery</button>
      <button type="button" class="btn btn-xs btn-secondary" id="Payment">Payment</button>
      <button type="button" class="btn btn-xs btn-secondary" id="Note">Note</button>
    </div>  

</div>
    <div class="panel-body">

<div class="policyAdded table-responsive">
<table class="table table-sm policyTable" style="display:none">
<thead class="bg-warning">
<th class="attTH" style="display:none">Attention</th>
<th class="validtyTH" style="display:none">Validity</th>
<th class="deliveryTH" style="display:none">Delivery</th>
<th class="paymentTH" style="display:none">Payment</th>
<th class="noteTH" style="display:none">Note</th>
</thead>

<tbody style="font-size:12px">
<tr>
<td class="attData" style="display:none"></td>
<td class="validtyData" style="display:none"></td>
<td class="deliveryData" style="display:none"></td>
<td class="paymentData" style="display:none"></td>
<td class="noteData" style="display:none"></td>
</tr>
<tr class="exportBtn" style="display:none">
	<td colspan="5" align="center">
    	<button class="btn btn-primary btn-sm" id="exportItemOffer">Export</button>
    </td>
</tr>
</tbody>

</table> 
</div>

    <div class="OffersExportDiv">
<?php 
   $sqlGetItemRef = "SELECT  `id`, `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`,`itemm2`, 
`msquerprice`, `shipping`, `installation`, `margin`, `itemqty`, `totalprice`,  `itemRef`, `handling`, `doorNumber`, `FRMin`, `remarks`, `Overlap` 
FROM `itemoffer` WHERE  `jobref` = $jobRowIdExp";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableExpt' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Item</th>
                <th>Hight</th>
                <th>Width</th>
				<th>Depth</th>
                <th>M<sup>2</sup></th>
                <th style='display:$diplay;'>Price</th>
				<th style='display:none'>Handling</th>
				<th style='display:none'><span data-toggle='tooltip' data-placement='left' title='Door Number'>No</span></th>
				<th>F.R.Min</th>
				<th>Remarks</th>
				<th style='display:none'>Overlap</th>
				<th><span data-toggle='tooltip' data-placement='left' title='Hardware Group Ref'>HW</span></th>
				<th>QTY</th>
                <th ><span data-toggle='tooltip' data-placement='left' 
				title='Included Hardware'>Total
				</span></th>
				
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{
	
	$sqlGetHWVal = "SELECT  SUM(`totalprice`) AS totalHW FROM `offerproperties` WHERE  `ioidref` = '$resGetItemRef[id]'";
	$queryGetHWVal = mysqli_query($link,$sqlGetHWVal)or die("ERROR :01-ANJ_GCN_S");
	if(mysqli_num_rows($queryGetItemRef) > 0)
	{
	$resGetHWVal = mysqli_fetch_assoc($queryGetHWVal);
	
	$totalItemPrice = round(($resGetItemRef['totalprice'] + $resGetHWVal['totalHW']));
	}
	else
	{
		$totalItemPrice = $resGetItemRef['totalprice'];
	}
	echo "
		<tr>
			<td class='col-sm-2'>$resGetItemRef[itemtype]</td>
			<td class='col-sm-3'>$resGetItemRef[itemname]</td>
			<td class='col-sm-1'>$resGetItemRef[itemhight]</td>
			<td class='col-sm-1'>$resGetItemRef[itemwidth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemdepth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemm2]</td>
			<td style='display:$diplay;' class='col-sm-1'><span data-toggle='tooltip' data-placement='left' 
			data-html='true'title='Item Price = 
			".round($resGetItemRef['totalprice'] / $resGetItemRef['itemqty'])."
			<br> include Shipping = $resGetItemRef[shipping]
			<br> Installation = $resGetItemRef[installation]
			<br> Margin = ".($resGetItemRef['margin'] * 100)."%
			'>
			$resGetItemRef[msquerprice]</span></td>
			
			<td style='display:none' class='col-sm-1'>$resGetItemRef[handling]</td>
			<td style='display:none' class='col-sm-1'>$resGetItemRef[doorNumber]</td>
			<td class='col-sm-1'>$resGetItemRef[FRMin]</td>
			<td class='col-sm-1'>$resGetItemRef[remarks]</td>
			<td style='display:none' class='col-sm-1'>$resGetItemRef[Overlap]</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Show Assigned HW' >
			<button class='btn btn-link showallHWMoadel' value='$jobRowIdExp,$resGetItemRef[itemRef]
			,$resGetItemRef[id]'><b>$resGetItemRef[itemRef]</b>
			</button>
			</span></td>
			<td class='col-sm-1'>$resGetItemRef[itemqty]</td>
			<td  class='col-sm-1'>".number_format($totalItemPrice)."</td>
			
		</tr>
	
	";
}
	
	echo "
	</tbody>
	<tfoot class='bg-light'>
       	  <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
           <th></th>
           <th style='display:$diplay;'></th>
           <th></th>
           <th style='display:none'></th>
           <th style='display:none'></th>
           <th></th>
		   <th style='display:none'></th>
		   <th>Total: </th>
		   <th colspan='2'></th>
		   
		   
    </tfoot>
</table>
	";
}
   
?> 	
    
    </div>
                        
    </div>
</div>


