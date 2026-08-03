<?php 
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$attName = $_GET['Atntion'];
$orderRowId = $_GET['OrderTableRID'];
$orderType = $_GET['OrderTypeExp'];
$idRowJobOld = $_GET['jojbTableRowId'];
$SelectedItems = explode(',',$_GET['selectedItem']);
$countSeletced = count($SelectedItems);
	
	$sqlGetSuppOrder="SELECT `SuppCode`, `OrderNumber`, `deliveryDate`, `orderNotes` FROM `supplierorder` WHERE `SOId` = $orderRowId";
	$queryGetSuppOrder=mysqli_query($link,$sqlGetSuppOrder)or die("ERROR :01-AU_AU_S");
	$resGetSuppOrder= mysqli_fetch_assoc($queryGetSuppOrder);
	
	$sqlGetSuppName="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
	$resGetSuppOrder[SuppCode]";
	$queryGetSuppName=mysqli_query($link,$sqlGetSuppName)or die("ERROR :02-AU_AU_S");
	$resGetSuppName= mysqli_fetch_assoc($queryGetSuppName);
	
	$orderNo = $resGetSuppOrder['OrderNumber'];
	$Supplier = $resGetSuppName['suppliername'];
	$DDate = $resGetSuppOrder['deliveryDate'];
	$OrderNote = $resGetSuppOrder['orderNotes'];

?>	


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Order No: <?php echo $orderNo;?> Date : <?php echo date("d/m/Y");?>
&nbsp;For:&nbsp;<?php echo $Supplier;?></title>
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
  @page {size:A4 landscape}
  @page { size:margin: 0;}	
  body {
    zoom: 99%;
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
	rotate: 0deg;
   }
  

</style>
</head>

<body style="font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif" >
 <div class="watermark">
      <div><img src="../img/WM.png" /></div>
     
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
   
    	<p><b><span style="font-size:22px">Industry Order</span>
        <br>
        No:&nbsp; <?php echo $orderNo;?>
        </b></p>
    </center>
   <table>
      <tr>
       <td style=""><b>To:&nbsp;</b>  <?php echo $Supplier;?> </td>
    <td width="50%"> </td> 
	<td><b>FROM&nbsp;</b>  <?php echo $_SESSION['fname'];?></td>
     </tr>
     
       <td> <b>Att:&nbsp;</b>  <?php echo $attName;?> </td>
    <td width="50%"> </td>
    <td><b>E-mail:&nbsp;</b>MASTER_DOORS@OUTLOOK.COM</td>
    
     <tr>
      <td><b>Date:&nbsp;</b>  <?php echo date("d/m/Y");?> </td>
      
    <td width="50%"> </td>
     <td><b>Mobile:&nbsp;</b>+201069838212</td>
     </tr>
	</table>
    <br>
      <!-- Start Print Content -->
      <center>
<table class="table" height="auto" style="border:solid; border:1px">

    	<thead style="border-style:solid; border-width:2px; border-color:#000000;" class='bg-light'>
        	<td class='col-sm-1' style="width:5%" align="center"><b>Sr.</b></td>
            <td class='col-sm-1' style="width:10%" ><b>&nbsp;Door Type</b></td>
            <td class='col-sm-2' style="width:30%" align="center"><b>&nbsp;DOOR SPECS</b></td>
            <td class='col-sm-2' style="width:30%" align="center"><b>MOTOR SPECS</b></td> 
            <td class='col-sm-1' style="width:5%" align="center"><b>QTY</b></td>
        </thead>
        <tbody style="border-style:solid; border-width:2px; border-color:#000000; font-size:12px">
<?php
		$SN=1;
		$doorTotalQTY = 0;
		$doorType = "";
		for($ItemRID = 0; $ItemRID < $countSeletced; $ItemRID++)
		{
			$sqlGetSuppItem = "SELECT `ItemRowId`, `qty` FROM `supporderitems` WHERE `OIId` = 
			".$SelectedItems[$ItemRID]."";
			$queyGetSuppItem=mysqli_query($link,$sqlGetSuppItem)or die("ERROR :05-FOJ_EOJO_S"
			.mysqli_error($link));
			$resGetSuppItem = mysqli_fetch_assoc($queyGetSuppItem);
			
			$sqlFetchOldJob="SELECT `id`, `doortype`, `doorspecs`, `motorspecs`, `doorqty`
			 FROM `autodoorsoffer` WHERE  `id` = $resGetSuppItem[ItemRowId]";
	 	    $queyFetchOldJob=mysqli_query($link,$sqlFetchOldJob)or die("ERROR :04-FOJ_EOJO_S".
			mysqli_error($link));
			$resFetchOldJob = mysqli_fetch_assoc($queyFetchOldJob);
			
			echo "
    <tr>
   <td class='col-sm-1 td' style='width:5%' align='center'>$SN </td>
   	 	<td class='col-sm-1 td' style='width:10%'>&nbsp;".$resFetchOldJob['doortype']."</td>
     
	<td class='col-sm-2 td' style='width:30%' align='left'>&nbsp;".$resFetchOldJob['doorspecs']."</td>
     
    <td class='col-sm-2 td'style='width:30%'  align='left'>".$resFetchOldJob['motorspecs']."</td>
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['doorqty']."</td>
</tr>  ";
		$doorTotalQTY+=$resFetchOldJob['doorqty'];
		
$doorType .="Type: ".$resFetchOldJob['doortype']." QTY:".$resFetchOldJob['doorqty']."|";		
$SN++;
		}
	
?>                      
      <tr style="border-style:solid; border-width:2px; border-color:#000000; ">
     	<td class='th' align="center" colspan="4"><b>Total</b></td>
        <td class='th' align='center' colspan=""><b><?php echo $doorTotalQTY;?> &nbsp;</b>
        </td>
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
      
  <?php
	 $chechLang = preg_match('/\p{Arabic}/u', $OrderNote);
	 
	 if($chechLang == 1)
	 {
		 $align = "right";
		 $direction = "rtl";
	 }
	 if($chechLang == 0)
	 {
		 $align = "left";
		 $direction = "ltr";
	 }

	 ?> 
 <div style="margin:2%" align="<?php echo $align;?>">
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Material Cost:</b>&nbsp;<br><?php echo ($OrderNote); ?></p>  
 
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Standard Payment Terms:</b>&nbsp;100% After Delivery</p> 
 
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Delivery Time:&nbsp;</b>&nbsp;<?php echo ($DDate); ?></p>  
  
</div>
<br>

<div align="left" style=" margin-top:">
<p style="margin-left:5%;">
Best Regards
<br>
<span style="margin-left:2%;"><b><?php echo $_SESSION['fname'];?></b></span>
<br>
<span><b><?php echo $_SESSION['Dept'];?></b></span>
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