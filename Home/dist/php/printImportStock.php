<?php 
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$invoiceNum=$_GET['InvoiceNo'];
	
	$sqlGetAllInvoData="SELECT `suppliersInvoiceNumber`, `supplierCode` FROM `supplierInvoice` WHERE `suppliersInvoiceNumber` = '$invoiceNum'";
	$queryGetAllInvoData=mysqli_query($link,$sqlGetAllInvoData)or die("ERROR :01-AU_AU_S");
	$resGetAllInvoData= mysqli_fetch_assoc($queryGetAllInvoData);
	
	$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = $resGetAllInvoData[supplierCode]";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :02-AU_AU_S");
	$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);
	
	$SuppName = $resGetSupplier['suppliername'];

	$sqlGetDocNum="SELECT `docSerial` FROM `warehouse` WHERE `invoicenumber` = '$invoiceNum' AND `whref` = 0 ORDER BY `docSerial` DESC LIMIT 1";
	$queryGetDocNum=mysqli_query($link,$sqlGetDocNum)or die("ERROR :03-AU_AU_S");
	$resGetDocNum= mysqli_fetch_assoc($queryGetDocNum);
	
	$docNum = $resGetDocNum['docSerial'];
?>	


<!doctype html>
<html>
<head>
<meta charset="utf-8">
 <link rel="icon"  href="../img/logoMarkerSmall.png" sizes="128x128" />
<title>Import Stock Doc Num:&nbsp; CMS-NS_<?php echo $docNum;?> &nbsp;From Supplier :&nbsp;<?php echo $SuppName;?> &nbsp; Invoice No:&nbsp;<?php echo $invoiceNum;?> 
&nbsp;Date : <?php echo date("d/m/Y");?></title>
<link rel="stylesheet" href="../../plugins/bootstrap5/css/bootstrap.min.css">
 <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../plugins/bootstrap5/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	window.print();
	//window.onafterprint = window.close();
	

	});
</script>

<style>
table {
  width: 100%;
}

table.print-content {
  font-size: 12px;
  border: 1px solid #000000;
  border-collapse: collapse !important;
  border:thin;
  
}
.th {
  border: .5px solid black;
}
.td {
  border: .5px solid black;
}
table.print-content th,
table.print-content td {
  padding: .2rem .4rem;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #000000;
}

@media print {
  .print-footer {
    position: fixed;
    bottom: 0;
    left: 0;
  }
  .no-print {
    display: none
  }
  .landscaep {size:A4 portrait}
  @page { size:margin: 0;}	
  body {
    zoom: 100%;
  }
  div.Portriy {
    page-break-before: always;
  }
}

 .watermark
   {
      display:block;
	  margin-top:45%;
      z-index: 99999; 
      width: 100%;
  position:fixed;

  text-align:center!important;
   }
   .watermark img
   {
    opacity: 0.2;
    filter: alpha(opacity=15);
	rotate: 30deg;
   }
    .watermark_text
  {
    width: 100%;
    color: #d0d0d0;
    font-size: 2.5em;
    text-align: center; 
    margin: 0 auto;
	margin-top:55%;
    z-index: 10000;
    opacity: 0.4;
    filter: alpha(opacity=15);
    white-space: normal;
    word-wrap: break-word !important;
    overflow: visible;
  }

</style>
</head>

<body style="font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif" >
 <div class="watermark">
      <div><img src="../img/WM.png" /></div>
      <div class="watermark_text">
       Master Doors EG. 
      </div>
    </div>
<table>
  <!-- Start Header -->
  <thead>
    <tr>
      <td>
      <img src="../img/Header.png" style="width:auto; height:3cm; display: block;" >
      <br>
      </td>
      
    </tr>
  </thead>
  <!-- End Header -->
  <tr>
    <td>
    <div class="landscaep">
     <center>
   
    	<p><b><span style="font-size:22px">Import Stock Document</span>
        <br>CMS-NS_
        <?php echo $docNum;?>
        </b></p>
    </center>
   <table>
      <td><b>Date:&nbsp;</b>  <?php echo date("d/m/Y");?> </td>
      <tr>
       <td><b>Supplier:&nbsp;</b>  <?php echo $SuppName?> </td>
      </tr>
      <tr>
       <td><b>Invoice No:&nbsp;</b>  <?php echo $invoiceNum?> </td>
      </tr>
	</table>
    <br>
      <!-- Start Print Content -->
      <center>
<table class="table" height="auto" style="border:solid; border:1px">

    	<thead style="border-style:solid; border-width:2px; border-color:#000000;" class='bg-light'>
        	<td class='col-sm-1' style="width:5%" align="center"><b>Sr.</b></td>
            <td class='col-sm-2' style="width:10%" ><b>&nbsp;Part No</b></td>
            <td class='col-sm-2' style="width:30%" align="center"><b>&nbsp;Item Name</b></td>
            <td class='col-sm-1' style="width:5%" align="center"><b>QTY</b></td> 
        </thead>
        <tbody style="border-style:solid; border-width:2px; border-color:#000000; font-size:12px">
        	<?php
		$SN=1;
		
	$sqlFetchNewStock="SELECT `warehouseId`, `description`, `income` FROM `warehouse` WHERE  `docSerial` 
	= $docNum AND `invoicenumber` = '$invoiceNum'"; 
	$queyFetchNewStock=mysqli_query($link,$sqlFetchNewStock)or die("ERROR :04-FOJ_EOJO_S".mysqli_error($link));
   while($resFetchNewStock = mysqli_fetch_assoc($queyFetchNewStock))
   {
	   $ItemCode = $resFetchNewStock['description'];
	   $WHRID = $resFetchNewStock['warehouseId'];
	   
	   
	$sqlGetItemInfo = "SELECT `descriptionname`, `partnumber` FROM `stockitems` 
	WHERE `description` = $ItemCode";
	$queryGetItemInfok = mysqli_query($link,$sqlGetItemInfo)or die("ERROR :05-AM_AMDL_S"
	.mysqli_error($link));
	$resGetItemInfo = mysqli_fetch_assoc($queryGetItemInfok);
	
	   
echo "
    <tr>
   <td class='col-sm-1 td' style='width:5%' align='center'>$SN </td>
   	<td class='col-sm-1 td' style='width:10%'>&nbsp;".$resGetItemInfo['partnumber']."</td>
     
	<td class='col-sm-2 td' style='width:30%' align='left'>&nbsp;".$resGetItemInfo['descriptionname']."</td>
     
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchNewStock['income']."</td>
  
   </tr>     
";

	$sqlUpdateWHRef = "UPDATE `warehouse` SET `whref` = 1 WHERE `warehouseId` = $WHRID";
	mysqli_query($link,$sqlUpdateWHRef)or die("ERROR :06-AM_AMDL_S".mysqli_error($link));
$SN++;
 }
 ?>

      
        	<?php
				$sqlGetTotalStock = "SELECT SUM(`income`) AS TotalStock FROM `warehouse` WHERE `docSerial` = $docNum";
				$queyGetTotalStock=mysqli_query($link,$sqlGetTotalStock)or die("ERROR :05-FOJ_EOJO_S"
				.mysqli_error($link));
				$resGetTotalStock = mysqli_fetch_assoc($queyGetTotalStock);
				$SumTotal = $resGetTotalStock['TotalStock'];
			
			
			?>
                        
      <tr style="border-style:solid; border-width:2px; border-color:#000000; ">
     	<td class='th' align="right" colspan="3"><b>Total Items QTY</b></td>
        <td class='th' align='center' colspan="1"><b><?php echo $SumTotal;?></b></td>
      
      </tr>
 </tbody>
  </table>
  </center>
      <!-- End Print Content -->
      </div>
    </td>
  </tr>
  <tr>
    <td>
      
<div align="left" style="">
<p style="margin-left:5%;">
Added By
<br>
<span style="margin-left:2%;"><b><?php echo $_SESSION['fname'];?></b></span>
<br>
<span><b>Warehouse Keeper</b></span>
</p>
 
 
</div>
 
    </td>
  </tr>
  <!-- Start Space For Footer -->
  <tfoot>
    <tr>
      <td style="height: 1cm">
        <!-- Leave this empty and don't remove it. This space is where footer placed on print -->
      </td>
    </tr>
  </tfoot>
  <!-- End Space For Footer -->
</table>

<!-- Start Footer -->
<div class="print-footer">
 <p style="font-family:Baskerville, 'Palatino Linotype', Palatino, 'Century Schoolbook L', 'Times New Roman', serif; font-size:10px">Powered by <a href="https://QMS-EGYPT.COM">QMS</a></p>
</div>
<!-- End Footer -->

     

</body>
</html>