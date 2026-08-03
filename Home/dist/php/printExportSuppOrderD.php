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
  font-size: 10px;
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
    zoom: 99.9%;
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
	rotate: 35deg;
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
      <img src="../img/Header.png" style="width:auto; height:2cm; display: block;" >
      <br>
      </td>
      
    </tr>
  </thead>
  <!-- End Header -->
  <tr>
    <td>
    <div class="landscaep">
     <center>
   
    	<p><b><span style="font-size:14px">Industry Order</span>
        <br>
        <span style="font-size:12px">No:&nbsp; <?php echo $orderNo;?></span>
        </b></p>
    </center>
   <table style="font-size:10px">
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
<table class="table table-sm" height="auto" style="border:solid; border:1px; font-size:10px">

    	<thead style="border-style:solid; border-width:2px; border-color:#000000;
        font-size:10px" class='bg-light'>
        	<td class='col-sm-1' style="width:5%" align="center"><b>Sr.</b></td>
            <td class='col-sm-1' style="width:10%" ><b>&nbsp;DoorType</b></td>
            <td class='col-sm-1' style="width:5%" align="center"><b>Door No</b></td>
            <td class='col-sm-2' style="width:20%" ><b>&nbsp;Item</b></td>
            <td class='col-sm-1' style="width:5%" align="center"><b>Width</b></td> 
            <td class='col-sm-1' style="width:5%" align="center"><b>Height</b></td> 
            <td class='col-sm-1' style="width:5%" align="center"><b>Depth</b></td> 
            <td class='col-sm-1' style="width:5%" align="center"><b>F.R Min.</b></td> 
            <td class='col-sm-1' style="width:5%" align="center"><b>HW</b></td> 
            <td class='col-sm-1' style="width:7%" align="center"><b>handle</b></td> 
            <td class='col-sm-1' style="width:7%" align="center"><b>Remarks</b></td> 
            <td class='col-sm-1' style="width:7%" align="center"><b>Frame Overlap</b></td>
            <td class='col-sm-1' style="width:7%" align="center"><b>RAL</b></td> 
        </thead>
        <tbody style="border-style:solid; border-width:2px; border-color:#000000; font-size:12px">
<?php
		$SN=1;
		$doorTotalQTY = 0;
		$doorType = "";
		for($ItemRID = 0; $ItemRID < $countSeletced; $ItemRID++)
		{
			$sqlGetSuppItem = "SELECT `ItemRowId`, `qty`, `Handle`, `doornumber`, `Overlap`, `RAL` 
			FROM `supporderitems` WHERE `OIId` = ".$SelectedItems[$ItemRID]."";
			$queyGetSuppItem=mysqli_query($link,$sqlGetSuppItem)or die("ERROR :05-FOJ_EOJO_S"
			.mysqli_error($link));
			$resGetSuppItem = mysqli_fetch_assoc($queyGetSuppItem);
			
			$arrayKey = 0;
			
			$sqlFetchOldJob="SELECT `id`, `itemname`, `itemtype`, `itemhight`, `itemwidth`, `itemdepth`, 
			`itemm2`, `itemqty`,`itemRef`,`handling`, `doorNumber`, `FRMin`, `remarks`, `Overlap`, 
			`itemRal` FROM `itemoffer` WHERE `id` =	$resGetSuppItem[ItemRowId]";
	 	    $queyFetchOldJob=mysqli_query($link,$sqlFetchOldJob)or die("ERROR :04-FOJ_EOJO_S".
			mysqli_error($link));
			$resFetchOldJob = mysqli_fetch_assoc($queyFetchOldJob);
			
			$doorNo = explode(',' ,$resGetSuppItem['doornumber']);
			//print_r($doorNo = array_combine(range(1, count($doorNo)), $doorNo));
			$overlap = explode(',' ,$resGetSuppItem['Overlap']);
			$RAL = explode(',' ,$resGetSuppItem['RAL']);
			$handle = explode(',' ,$resGetSuppItem['Handle']);
			
			
			
			$sqlCheckHW="SELECT `offproId` FROM `offerproperties` 
			WHERE `ioidref` = $resFetchOldJob[id]";
			$queyCheckHW=mysqli_query($link,$sqlCheckHW)or die("ERROR :03_1-FOJ_EOJO_S"
			.mysqli_error($link));   
			if(mysqli_num_rows($queyCheckHW) == 0)
			{
				$itemRef = 'Non';
			}
			else
			{
				$itemRef = $resFetchOldJob['itemRef'];
			}
			for($q = 1; $q <= $resGetSuppItem['qty']; $q++)
			{
			/*$sqlFetchItemDet="SELECT `ral`, `doornumber`, `handlingSupp` FROM `suppitemdetails` 
			WHERE `oiidRef` = ".$SelectedItems[$ItemRID]."";
	 	    $queyFetchItemDet=mysqli_query($link,$sqlFetchItemDet)or die("ERROR :04-FOJ_EOJO_S".
			mysqli_error($link));
			if(mysqli_num_rows($queyFetchItemDet) > 0 )
			{
			$resFetchItemDet = mysqli_fetch_assoc($queyFetchItemDet);
				$doornumber = $resFetchItemDet['doornumber'];
				$ral = $resFetchItemDet['ral'];
				$handlingSupp = $resFetchItemDet['handlingSupp'];
			}
			else
			{
				$doornumber = "";
				$ral = "";
				$handlingSupp = "";
			}*/
			echo "
			
			
    <tr>
   <td class='col-sm-1 td' style='width:5%' align='center'>$SN </td>
   	<td class='col-sm-1 td' style='width:10%'>&nbsp;".$resFetchOldJob['itemtype']."</td>
    <td class='col-sm-2 td' style='width:5%'>&nbsp;".$doorNo[$arrayKey]."</td> 
	<td class='col-sm-2 td' style='width:20%'>&nbsp;".$resFetchOldJob['itemname']."</td>
     
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['itemwidth']."</td>
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['itemhight']."</td>
  
   <td class='col-sm-1 td' style='width:5%' align='center'>".$resFetchOldJob['itemdepth']."</td>
  
   <td class='col-sm-1 td' style='width:5%' align='center'>".$resFetchOldJob['FRMin']."</td>
  
   <td class='col-sm-1 td text-primary' style='width:5%' align='center'>".$itemRef."</td>
  
  <td class='col-sm-1 td' style='width:7%'  align='center'>".$handle[$arrayKey]."</td>
  
   <td class='col-sm-1 td' style='width:7%'  align='center'>".$resFetchOldJob['remarks']."</td>
     
   <td class='col-sm-1 td' style='width:7%' align='center'>".$overlap[$arrayKey]."</td>
   <td class='col-sm-1 td' style='width:7%'  align='center'>".$RAL[$arrayKey]."</td>
";
$arrayKey++;
$SN++;
		}

$doorType .="Type: ".$resFetchOldJob['itemtype']." QTY:".$resFetchOldJob['itemqty']."|";	

$doorTotalQTY += $resGetSuppItem['qty'];
		}
	
?>                  
      <tr style="border-style:solid; border-width:2px; border-color:#000000; ">
     	<td class='th' align="center" colspan="3"><b>Total</b></td>
        <td class='th' align='center' colspan=""><b><?php echo $doorTotalQTY;?> &nbsp;</b>
        </td>
        <td class='th' align="center" colspan="9"></td>
      </tr>  
    
 </tbody>
  </table>
   
  <div class="Portriy" align="center">
	
<?php
$No=1;

$sqlFetchItemRef2="SELECT `id`, `itemRef` FROM `itemoffer`, `offerproperties` WHERE `jobref` = $idRowJobOld 
AND `ioidref` = `id` AND `id` IN (SELECT `ItemRowId` FROM `supporderitems` WHERE `SOIdRef` = $orderRowId)
 GROUP BY `itemRef`" ;
$queyFetchItemRef2=mysqli_query($link,$sqlFetchItemRef2)or die("ERROR :06-FOJ_EOJO_S".mysqli_error($link));   

if(mysqli_num_rows($queyFetchItemRef2) != 0)
{
while($resFetchItemRef = mysqli_fetch_assoc($queyFetchItemRef2))
	{
		
	echo "
		<table class='table table-bordered table-sm' style='width:60%'>
			<td class='bg-light' colspan='4' align='center'><b>HW Group $resFetchItemRef[itemRef]</b></th>
			<tr class='bg-light'>
				<th>No.</th>
				<th>Description</th>
				<th>MFR</th>
				<th>QTY</th>
			</tr>
		
	";	
		
	$sqlFetchHW="SELECT `descripcode`, `descripquantity` FROM `offerproperties` 
	WHERE `ioidref` = $resFetchItemRef[id]";
	$queyFetchHW=mysqli_query($link,$sqlFetchHW)or die("ERROR :07-FOJ_EOJO_S".mysqli_error($link));   
	while($resFetchHW = mysqli_fetch_assoc($queyFetchHW))	
	{
		$sqlFetchHWData="SELECT `descriptionname` FROM `stockitems` 
		WHERE `description` = $resFetchHW[descripcode]";
		$queyFetchHWData=mysqli_query($link,$sqlFetchHWData)or die("ERROR :08-FOJ_EOJO_S".mysqli_error($link));
		$resFetchHWData = mysqli_fetch_assoc($queyFetchHWData);
		
		$sqlGetHWManuf="SELECT `manufacturing` FROM `stockitems` 
		WHERE `description` = $resFetchHW[descripcode]";
		$queyGetHWManuf=mysqli_query($link,$sqlGetHWManuf)or die("ERROR :09-FOJ_EOJO_S".mysqli_error($link));
		$resGetHWManuf = mysqli_fetch_assoc($queyGetHWManuf);
		
		if($resGetHWManuf['manufacturing'] == 0)
		{
			$manuf = "N/A";
		}
		else
		{
			$sqlGetHWManufName="SELECT `manufactuername` FROM `allmanufactuers` 
			WHERE `manufactuercode` = $resGetHWManuf[manufacturing]";
			$queyGetHWManufName=mysqli_query($link,$sqlGetHWManufName)or die("ERROR :09-FOJ_EOJO_S"
			.mysqli_error($link));
			$resGetHWManufName = mysqli_fetch_assoc($queyGetHWManufName);
			$manuf = $resGetHWManufName['manufactuername'];
		}
		echo "
			<tr>
				<td>$No</td>
				<td>$resFetchHWData[descriptionname]</td>
				<td>$manuf</td>
				<td>$resFetchHW[descripquantity]</td>
			</tr>
		
		";
		$No++;
	}
		
	echo "</table><br>";
	$No =1;
	}
}


?>

</div>	 

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
<div align="left" style=" font-size:11px">
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
      <td style="height: 0cm">
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

<?php

$action="Export Supplier Order for Supplier: $Supplier order Items: $doorType ";
		
$logRef=9;	
include_once("aduLog.php");
exit();			
?>     

</body>
</html>