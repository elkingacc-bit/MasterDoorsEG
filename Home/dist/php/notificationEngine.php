 <?php
 @session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

if(empty($_SESSION['username']))
{
	header("location:../../MasterDoorsEG/");
}
$countAllnofti = 0;
$userCode = $_SESSION['code'];
$today = date("Y-m-d");
if($_SESSION['Dept'] == 'Admin' || $_SESSION['Dept'] == 'Manager')
{

// excesed jobs
$sqlGetWaitingJobs="SELECT COUNT(`jobId`)As CountWaiting FROM `job` WHERE `jobref` = 1 ";
$queryWaitingJobs=mysqli_query($link,$sqlGetWaitingJobs)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryWaitingJobs) > 0)
{
	$resWaitingJobs= mysqli_fetch_assoc($queryWaitingJobs);
	$waitingJobs = $resWaitingJobs['CountWaiting'];
	$countAllnofti += $waitingJobs;
}
else
{
	$waitingJobs = 0;
}

// expired items


$sqlGetExcededSalesOrder="SELECT COUNT(`poId`)As CountExcedOrder FROM `customerpo` 
WHERE `deleveryDate` <= NOW() AND `poRef` IS NULL";
$queryGetExcededSalesOrder=mysqli_query($link,$sqlGetExcededSalesOrder)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryGetExcededSalesOrder) > 0)
{
	$resGetExcededSalesOrder = mysqli_fetch_assoc($queryGetExcededSalesOrder);
	$excededOrder = $resGetExcededSalesOrder['CountExcedOrder'];
	$countAllnofti += $excededOrder;
}
else
{
	$excededOrder = 0;
}


$sqlGetExcedSupp="SELECT COUNT(`SOId`)As excedSuppOrder FROM `supplierorder`
 WHERE `deliveryDate` <= NOW() AND `SORef` = 1";
$queryGetExcedSupp=mysqli_query($link,$sqlGetExcedSupp)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryGetExcedSupp) > 0)
{
	$resGetExcedSupp= mysqli_fetch_assoc($queryGetExcedSupp);
	$excedSuppOrder = $resGetExcedSupp['excedSuppOrder'];
	$countAllnofti += $excedSuppOrder;
}
else
{
	$excedSuppOrder = 0;
}

?>  
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showWaitningOffer" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue"></i> 
			&nbsp; <?php echo $waitingJobs;?> Offers Waiting Confirm
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showExcededOrder" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true" style="font-size:14px;color:orange">
            </i> <?php echo $excededOrder ;?> daily delivery Items
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showDelaiedOrder" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-exclamation-triangle"  aria-hidden="true" style="font-size:14px;color:red"></i> 
			&nbsp; <?php echo $excedSuppOrder;?> daily receive Items
          </a>
          
          
<?php
}

else if($_SESSION['Dept'] == 'Technical' )
{

$sqlGetWaitingJobs="SELECT COUNT(`jobId`)As CountWaiting FROM `job` WHERE `jobref` IS NULL ";
$queryWaitingJobs=mysqli_query($link,$sqlGetWaitingJobs)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryWaitingJobs) > 0)
{
	$resWaitingJobs= mysqli_fetch_assoc($queryWaitingJobs);
	$waitingJobs = $resWaitingJobs['CountWaiting'];
	$countAllnofti += $waitingJobs;
}
else
{
	$waitingJobs = 0;
}

$sqlGetNewSalesOrder="SELECT COUNT(`poId`)As CountNewOrder FROM `customerpo` WHERE (`orderType` = 'Doors' 
AND `poId` NOT IN (SELECT `custPOId` FROM `supplierorder`)) OR (`orderType` = 'Automatic' 
AND `poId` NOT IN (SELECT `custPOId` FROM `supplierorder`))";
$queryGetNewSalesOrder=mysqli_query($link,$sqlGetNewSalesOrder)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryGetNewSalesOrder) > 0)
{
	$resGetNewSalesOrder = mysqli_fetch_assoc($queryGetNewSalesOrder);
	$NewOrder = $resGetNewSalesOrder['CountNewOrder'];
	$countAllnofti += $NewOrder;
}
else
{
	$NewOrder = 0;
}

$sqlGetExcededSalesOrder="SELECT COUNT(`poId`)As CountExcedOrder FROM `customerpo` 
WHERE `deleveryDate` <= NOW() AND `poRef` IS NULL";
$queryGetExcededSalesOrder=mysqli_query($link,$sqlGetExcededSalesOrder)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryGetExcededSalesOrder) > 0)
{
	$resGetExcededSalesOrder = mysqli_fetch_assoc($queryGetExcededSalesOrder);
	$excededOrder = $resGetExcededSalesOrder['CountExcedOrder'];
	$countAllnofti += $excededOrder;
}
else
{
	$excededOrder = 0;
}
	
?>

 		  <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showTechOffer" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue"></i> 
			&nbsp; <?php echo $waitingJobs;?> New Customer Offer
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showTechOrder" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue"></i> 
			&nbsp; <?php echo $NewOrder;?> New Customer Order
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showExcededOrder" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true" style="font-size:14px;color:orange">
            </i> <?php echo $excededOrder ;?> daily delivery Items
          </a>
<?php
}
else if($_SESSION['Dept'] == 'Sales' )
{
	$salesCode = $_SESSION['code'];
$sqlGetWaitingJobs="SELECT COUNT(`jobId`)As CountWaiting FROM `job` WHERE `jobref` = 2 AND `salesman` = 
$salesCode";
$queryWaitingJobs=mysqli_query($link,$sqlGetWaitingJobs)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryWaitingJobs) > 0)
{
	$resWaitingJobs= mysqli_fetch_assoc($queryWaitingJobs);
	$waitingJobs = $resWaitingJobs['CountWaiting'];
	$countAllnofti += $waitingJobs;
}
else
{
	$waitingJobs = 0;
}

$sqlGetExcededSalesOrder="SELECT COUNT(`poId`)As CountExcedOrder FROM `customerpo`
 WHERE `deleveryDate` <= NOW() AND `poRef` IS NULL AND `salesCode` = $salesCode";
$queryGetExcededSalesOrder=mysqli_query($link,$sqlGetExcededSalesOrder)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryGetExcededSalesOrder) > 0)
{
	$resGetExcededSalesOrder = mysqli_fetch_assoc($queryGetExcededSalesOrder);
	$excededOrder = $resGetExcededSalesOrder['CountExcedOrder'];
	$countAllnofti += $excededOrder;
}
else
{
	$excededOrder = 0;
}	

?>
  
  <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item ConfirmedOffer" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue">
            </i> <?php echo $waitingJobs ;?> Confrimed Offers
          </a>
  <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item showExcededOrder" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true" style="font-size:14px;color:orange">
            </i> <?php echo $excededOrder ;?> daily delivery Items
          </a>

<?php
}
else if($_SESSION['Dept'] == 'Store' )
{

$sqlGetWaitingStock="SELECT `supplierInvoiceDataId` As WaitingStock FROM `supplierInvoiceData` 
WHERE `ref` = 0 GROUP BY `supplierInvoiceNumber`";
$queryWaitingStock=mysqli_query($link,$sqlGetWaitingStock)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryWaitingStock) > 0)
{
	//$resWaitingStock= mysqli_fetch_assoc($queryWaitingJobs);
	$waitingStock = mysqli_num_rows($queryWaitingStock);
	$countAllnofti += $waitingStock;
}
else
{
	$waitingStock = 0;
}

$sqlGetOrderStock="SELECT COUNT(`poId`) As StockOrders FROM `customerpo` 
WHERE `poRef` IS NULL AND `orderType` = 'Stock' ";
$queryGetOrderStock=mysqli_query($link,$sqlGetOrderStock)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($queryGetOrderStock) > 0)
{
	$resGetOrderStock= mysqli_fetch_assoc($queryGetOrderStock);
	$StockOrders = $resGetOrderStock['StockOrders'];
	$countAllnofti += $StockOrders;
}
else
{
	$StockOrders = 0;
}


?>
  
  <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item waitingStock" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue">
            </i> <?php echo $waitingStock ;?> New Invoice/s Waiting Receive
          </a>
  <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item stockOrders" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue">
            </i> <?php echo $StockOrders ;?> New Stock PO
          </a>
          

<?php	
}
else if($_SESSION['Dept'] == 'Accountant' )
{
	 #1 Supplier Invoice
 $sqlSupplier="SELECT `OIId` FROM `supporderitems` WHERE `status` = 5 GROUP BY `SOIdRef`";
 $querySupplierData=mysqli_query($link,$sqlSupplier)or die("ERROR_Acc_Noti : 01");
 $suplierInvCount=mysqli_num_rows($querySupplierData);
 #2 Supplier Invoice
 $sqlSupplier2="SELECT `SOIdRef` FROM `supporderitems` WHERE `status` = 1 GROUP BY `SOIdRef`";
 $querySupplierData2=mysqli_query($link,$sqlSupplier2)or die("ERROR_Acc_Noti : 02");
 $suplierInvCount2=mysqli_num_rows($querySupplierData2);
 #3 Custody
 $sqlCustodyData="SELECT count(`empCode`) as comback FROM `custody` WHERE `custodyRef` = 1;";
 $queryCustodyData=mysqli_query($link,$sqlCustodyData)or die("ERROR_Acc_Noti : 03");
 $resCustodyData=mysqli_fetch_assoc($queryCustodyData);
 $custodyCount =$resCustodyData['comback'];
 #4 Sales Collect
 $sqlSalesData="SELECT `salesInvoiceId` FROM `salesInvoice` WHERE `totalInvoice` != `invoiceCollectAmount`";
 $querySalesData=mysqli_query($link,$sqlSalesData)or die("ERROR_Acc_Noti : 04");
 $salesInvCount = mysqli_num_rows($querySalesData); 
 #5 Sales Invoice
 $sqlMakeInv="SELECT `jobId` FROM `job` WHERE `offerStatus` = 'Won' AND `invoice` = 'No'";
 $queryMakeInv=mysqli_query($link,$sqlMakeInv)or die("ERROR_Acc_Noti : 05");
 $makeInvCount = mysqli_num_rows($queryMakeInv);
 $countAllnofti=($suplierInvCount + $suplierInvCount2 + $custodyCount + $salesInvCount + $makeInvCount);

?>  
        
		  <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item newSupplierInvNot" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-hourglass-end mr-2" aria-hidden="true" style="font-size:14px;color:blue"></i> 
			&nbsp; <?php echo $suplierInvCount;?> Paied Invoice
          </a>
          <div class="dropdown-divider"></div>
         <a href="#" class="dropdown-item newSupplierInvReceivedNot" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true" style="font-size:14px;color:orange">
            </i> <?php echo $suplierInvCount2 ;?> Received Invoice
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item allopenCustdy" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-exclamation-triangle"  aria-hidden="true" style="font-size:14px;color:red"></i> 
			&nbsp; <?php echo $custodyCount;?> Open Custody
          </a>
           <div class="dropdown-divider"></div>
         <a href="#" class="dropdown-item newCollectSalesInvNot" data-toggle="tooltip" data-placement="top" 
          title="">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true" style="font-size:14px;color:orange">
            </i> <?php echo $salesInvCount ;?> Collect Invoic
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item newSalesInvNot" data-toggle="tooltip" data-placement="bottom" 
          title="">
            <i class="fa fa-exclamation-triangle"  aria-hidden="true" style="font-size:14px;color:red"></i> 
			&nbsp; <?php echo $makeInvCount;?> Billing in Process 
          </a>

<?php
}
?>


<script type="text/javascript">
$(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
	var countNotfi = <?php echo $countAllnofti;?>;
  $(".contNotfi").html(countNotfi);
  $(".contNotfiTitel").html("<span style='color:blue;'><b>"+countNotfi+"</b></span> Pending Notifications");
 if(countNotfi > 0)
  {
 const audio = new Audio("dist/wav/notification.wav"); 
 
  audio.addEventListener('canplay', e => {
  console.log('canplay');
  
  audio.play();
});
 
		audio.play();
		
	for(var nof = 1; nof <= 2; nof++)
	{
		
		setTimeout(function(){
			$(".contNotfi").removeClass("badge-warning");
			$(".contNotfi").addClass("badge-danger");			
		}, 2500);
		
		setTimeout(function(){
			$(".contNotfi").removeClass("badge-danger");
			$(".contNotfi").addClass("badge-warning");			
		}, 5000);
	}
  }
	
	$(".showWaitningOffer").click(function(){
		
	 //$('.data_display').html('');
	 //$('.data_display').load('dist/php/showAllWaitingOffers.php');
	 //$('.m-0').html('');
	 //$('.m-0').html('All Job Waiting Confirm');
		$("#3_2").click();
		audio.pause();
		return false;
		});
		
	$(".showExcededOrder").click(function(){
		
	 $('.data_display').html('');
	 $('.data_display').load('dist/php/ShowAllExcededPO.php');
	 $('.m-0').html('');
	 $('.m-0').html('All Customer Order Exceded Delivery Date');
		
		return false;
		});	
		
	$(".showDelaiedOrder").click(function(){
		
	 $('.data_display').html('');
	 $('.data_display').load('dist/php/showAllDealiedSuppOrder.php');
	 $('.m-0').html('');
	 $('.m-0').html('All Dealied Delivery Supply Items');
		
		return false;
		});	
		
	$(".showTechOrder").click(function(){
		
	$("#5_1").click();
		
		return false;
		});	
		
	$(".showTechOffer").click(function(){
		
	$("#8_2").click();
		
		return false;
		});
		
	$(".ConfirmedOffer").click(function(){
		
	$("#8_3").click();
		
		return false;
		});	
		
	$(".waitingStock").click(function(){
		
	$("#7_1").click();
		
		return false;
	});
	
	$(".stockOrders").click(function(){
		
	$("#7_2").click();
		
		return false;
	});				
	
 $(".newSalesInvNot").click(function(){
   $(".data_display").html('');
   $(".m-0").html("Collect Billing in Process");
   $(".data_display").load("dist/html/newProjectExtract.html");
  });
  $(".newCollectSalesInvNot").click(function(){
   $(".data_display").html('');
   $(".m-0").html("Collect Invoice");
   $(".data_display").load("dist/html/newSalesInvoiceCollect.html");
  });
  $(".allopenCustdy").click(function(){
   $(".data_display").html('');
   $(".m-0").html("All Open Custody");
   $(".data_display").load("dist/php/allCustodyCountData.php");
  });
  $(".newSupplierInvNot").click(function(){
   var supplierType = 5;
   $.ajax({
    url:'dist/php/purchesingInvoiceData.php',
    type:"POST",
    data:{repType:supplierType},
    success: function(getInvData){
     $(".data_display").html('');
     $(".m-0").html("Supplier Invoice Paied");
     $(".data_display").html(getInvData)
    }
   });
   return false;
  });
  $(".newSupplierInvReceivedNot").click(function(){
   var suppType =1;
   $.ajax({
    url:'dist/php/purchesingInvoiceData.php',
    type:"POST",
    data:{repType:suppType},
    success: function(getInvPaiedData){
     $(".data_display").html('');
     $(".m-0").html("New Supplier Invoice Received");
     $(".data_display").html(getInvPaiedData)
    }
   });
   return false;
  });			
	
	
});
</script>          