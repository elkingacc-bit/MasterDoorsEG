<?php
 @session_start();
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 $jobRowIdExp = $_POST['JRIDforExport'];
 
$sqlGetJobData = "SELECT `startDate`, `customer`, `salesman`, `description`, `offerValue` FROM `job` 
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
 
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	
var jobRowIdForCheckP = $("#jRowIdAuto").val();
$.ajax({
		
		url:"dist/php/checkPolicydata.php",
		type:"POST",
		data:{JRIDFCP:jobRowIdForCheckP},
		dataType: "json",
		cache: false,
		success: function(showAvailibleData){
		
			if(showAvailibleData.Ref == 1)
			{
				$(".Attention").removeClass("btn-secondary");
				$(".Attention").addClass("btn-info");
				$(".policyTable").show();
				$(".attTH").show();
				$(".attData").show();
				$(".attData").html(showAvailibleData.AttN);
				
				if(showAvailibleData.ValidNote != null || showAvailibleData.ValidNote != "")
				{
					 $(".validity").removeClass("btn-secondary");
					 $(".validity").addClass("btn-info");
					 $(".policyTable").show();
					 $(".validtyTH").show();
					 $(".validtyData").show();
					 $(".validtyData").html(showAvailibleData.ValidNote);
				}
				
				if(showAvailibleData.DelivryNote != null || showAvailibleData.DelivryNote != "")
				{
					 $(".Delivery").removeClass("btn-secondary");
					 $(".Delivery").addClass("btn-info");
					 $(".policyTable").show();
					 $(".deliveryTH").show();
					 $(".deliveryData").show();
					 $(".deliveryData").html(showAvailibleData.DelivryNote);
				}
				
				if(showAvailibleData.DownPay != 0 )
				{
					 $(".Payment").removeClass("btn-secondary");
					 $(".Payment").addClass("btn-info");
					 $(".policyTable").show();
					 $(".paymentTH").show();
					 $(".paymentData").show();
					 $(".paymentData").html(showAvailibleData.DownPay+"  at time of order <br>"+showAvailibleData.ReceivedPay+"upon delivery before installation Delivery payment must be paid within a week from delivery date.<br>"+showAvailibleData.LastPay+" AFTER INSTALLATION");
				}
				
				if(showAvailibleData.Notes != "")
				{
					 $(".Note").removeClass("btn-secondary");
					 $(".Note").addClass("btn-info");
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
	 $('.m-0').html('Export Offer Form <br> <p style="font-size:12px; color:black; with:100%;">Customer | <span style="color:red">'+titelCustN+'</span> - Sales | <span style="color:red">'+titelSalesN+'</span> - Start Date |  <span style="color:red">'+titelSDate+'</span>- total Value= <span style="color:blue" class="TotalOffer">'+titelTotalVal+'</span> L.E</p>');
	
 var table = $('.myTableExpt').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'30vh',
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
            .column( 3 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 3, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 3 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

});
	
	
	$('#Attention').click(function () {
       
		var jobRowIDHWExptAtt =  $("#jRowIdAuto").val();
		
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
       
		var jobRowIDHWExptVaild =  $("#jRowIdAuto").val();
		
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
       
		var jobRowIDHWExptDeliv =  $("#jRowIdAuto").val();
		
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
       
		var jobRowIDHWExptPay =  $("#jRowIdAuto").val();
		
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
       
		var jobRowIDHWExptPay =  $("#jRowIdAuto").val();
		
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
       
		var jobRowIDHWExptNote =  $("#jRowIdAuto").val();
		
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
	
	$('#exportAutoDoorOffer').click(function () {
       
		var jobRowIDExportAuto =  $("#jRowIdAuto").val();
		
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/addOfferExport.php',
                type:'POST',
                data:{jobRowIdOfferExport:jobRowIDExportAuto},
				beforeSend: function(){
					$('#exportAutoDoorOffer').prop("disabled", true);
				},
                
				success: function(doneExportedAutoOffer)
				{
					if(doneExportedAutoOffer == 1)
						{
						var newAutoDocPrint = window.open("dist/php/printFreeOffer.php?&JobId="+jobRowIDExportAuto,"_balnk");							
						setTimeout(function(){
							newAutoDocPrint.focus();
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
						alert(doneExportedAutoOffer);
						$('#exportAutoDoorOffer').prop("disabled", false);
					}
				}
        	}); 
		return false;
	});



});

	 


</script>

<input type="text" value="<?php echo $jobRowIdExp?>" id="jRowIdAuto" style="display:none"/>		
<input type="text" value="<?php echo $resultGetJobData['customer']?>" id="CustCode" style="display:none"/>		
<input type="text" value="<?php echo $resultGetCustName['customername']?>" id="CustName" style="display:none"/>
<input type="text" value="<?php echo $resultGetSalesName['username']?>" id="SalesName" style="display:none"/>
<input type="text" value="<?php echo $resultGetJobData['offerValue']?>" id="TotalOffer" style="display:none"/>
<input type="text" value="<?php echo date("d/m/Y",strtotime($resultGetJobData['startDate'])); ?>" 
id="SDate" style="display:none"/>		
<div class="panel panel-primary" style="width:99%">
<div class="panel-heading">
    <div class="btn-group" role="group" aria-label="Basic example">
      <button type="button" class="btn btn-xs btn-secondary Attention" id="Attention">Attention</button>
      <button type="button" class="btn btn-xs btn-secondary validity" id="validity">validity</button>
      <button type="button" class="btn btn-xs btn-secondary Delivery" id="Delivery">Delivery</button>
      <button type="button" class="btn btn-xs btn-secondary Payment" id="Payment">Payment</button>
      <button type="button" class="btn btn-xs btn-secondary Note" id="Note">Note</button>
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
    	<button class="btn btn-primary btn-sm" id="exportAutoDoorOffer">Export</button>
    </td>
</tr>
</tbody>

</table> 
</div>

    <div class="OffersExportDiv">
<?php 
   $sqlGetAutoDooes = "SELECT `id`, `type`, `price`, `typeqty`, `totalprice` 
   FROM `maintoffers` WHERE  `jobid` = $jobRowIdExp";
$queryGetAutoDooes = mysqli_query($link,$sqlGetAutoDooes)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetAutoDooes) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableExpt' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th class='col-sm-5'>Type</th>
				<th>QTY</th>
				<th>Price</th>
				
				<th>Total</th>
				
             </thead>
			 <tbody class='table-bordered'>
	";
 
while($resGetAutoDooes = mysqli_fetch_assoc($queryGetAutoDooes))
{
	echo "
		<tr>
			<td class='col-sm-5'>$resGetAutoDooes[type]</td>
			<td class='col-sm-1'>$resGetAutoDooes[typeqty]</td>
			<td class='col-sm-1'>".number_format($resGetAutoDooes['price'])."</td>
			<td class='col-sm-2'>".number_format($resGetAutoDooes['totalprice'])."</td>
			
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
    </tfoot>
</table>
	";
}
   
?> 	
    
    </div>
                        
    </div>
</div>


