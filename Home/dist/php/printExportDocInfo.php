<?php 
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$DocNumExportPrint = $_GET['DocNumPEx'];

	$sqlGetExportDoc="SELECT  DATE(`date`) AS ExportDate, 
	TIME(`date`) AS ExportTime, `poIdRef`, `custcode`, `responsible`, `docSerial` FROM `warehouse` 
	WHERE `poIdRef` != 0 AND `custcode` IS NOT NULL AND `docSerial` = $DocNumExportPrint";
	$queryGetExportDoc =mysqli_query($link,$sqlGetExportDoc)or die("ERROR :01-AU_AU_S");
	$resGetExportDoc = mysqli_fetch_assoc($queryGetExportDoc);
	
	$sqlGetCust = "SELECT `customername` FROM `customers` WHERE `customercode` =
	$resGetExportDoc[custcode]";
	$queryGetCust = mysqli_query($link,$sqlGetCust)or die("ERROR :02-ANJ_GCN_S");
	$resultGetCust = mysqli_fetch_array($queryGetCust);	
			
	$sqlGetJobRef = "SELECT `jobidref`, `PoNum`, `projectName` FROM `customerpo`, `job` WHERE `poId` =
	$resGetExportDoc[poIdRef] AND `jobidref` = `jobId`";
	$queryGetJobRef=mysqli_query($link,$sqlGetJobRef)or die("ERROR :03-AU_AU_S");
	$resGetJobRef= mysqli_fetch_assoc($queryGetJobRef);
	
	$Project = $resGetJobRef['customername'];
	$Customer = $resultGetCust['invoicenumber'];
	$PO = $resGetJobRef['PoNum'];
?>	


<!doctype html>
<html>
<head>
<meta charset="utf-8">
 <link rel="icon"  href="../img/logoMarkerSmall.png" sizes="128x128" />
<title>Export Stock Doc Num:&nbsp; CMS-NS_<?php echo $DocNumExportPrint;?> &nbsp;For Customer :&nbsp;<?php echo $Customer;?> &nbsp; Project :&nbsp;<?php echo $Project. " | ". $PO;?> 
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
   
    	<p><b><span style="font-size:22px">Export Stock Document - Copy</span>
        <br>CMS-ES_
        <?php echo $DocNumExportPrint;?>
        </b></p>
    </center>
   <table>
      <td><b>Date:&nbsp;</b>  <?php echo date("d/m/Y");?> </td>
      <tr>
       <td><b>Customer:&nbsp;</b>  <?php echo $Customer?> </td>
      </tr>
      <tr>
       <td><b>Project:&nbsp;</b>  <?php echo $Project. " | " . $PO?> </td>
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
		
	 $sqlGetItem="SELECT `description`, `export`, `salesprice` FROM`warehouse` WHERE `poIdRef` != 0 
	 AND `custcode` IS NOT NULL AND `docSerial` = $DocNumExportPrint";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
while($resGetItem = mysqli_fetch_assoc($queryGetItem))
{

	$sqlGetHWName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` = 
	$resGetItem[description]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :02-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);
	
	   
echo "
    <tr>
   <td class='col-sm-1 td' style='width:5%' align='center'>$SN </td>
   	<td class='col-sm-1 td' style='width:10%'>&nbsp;".$resGetHWName['partnumber']."</td>
     
	<td class='col-sm-2 td' style='width:30%' align='left'>&nbsp;".$resGetHWName['descriptionname']."</td>
     
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resGetItem['export']."</td>
  
   </tr>     
";

$SN++;
 }
 ?>

      
        	<?php
				$sqlGetTotalStock = "SELECT SUM(`export`) AS TotalStock FROM `warehouse` WHERE `poIdRef` != 0 
	 			AND `custcode` IS NOT NULL AND `docSerial` = $DocNumExportPrint";
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
Printed By
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