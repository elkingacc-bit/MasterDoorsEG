<?php 
@session_start(); 
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$attName = $_GET['Atntion'];
$orderRowId = $_GET['OrderTableRID'];
$orderType = $_GET['OrderTypeExp'];
$idRowJobOld = $_GET['jojbTableRowId'];
$SelectedItems = explode(',',$_GET['selectedItem']);
$SelectedItems = array_keys(array_flip($SelectedItems));
$countSeletced = count($SelectedItems);
	
//print_r($SelectedItems);
	
	$sqlGetSuppOrder="SELECT `custCode`, `PoNum`, `deleveryDate` FROM `customerpo` WHERE `poId` = $orderRowId";
	$queryGetSuppOrder=mysqli_query($link,$sqlGetSuppOrder)or die("ERROR :01-AU_AU_S");
	$resGetSuppOrder= mysqli_fetch_assoc($queryGetSuppOrder);
	
	$sqlGetSuppName="SELECT `customername` FROM `customers` WHERE `customercode` = 
	$resGetSuppOrder[custCode]";
	$queryGetSuppName=mysqli_query($link,$sqlGetSuppName)or die("ERROR :02-AU_AU_S");
	$resGetSuppName= mysqli_fetch_assoc($queryGetSuppName);
	
	$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` =	$idRowJobOld";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :02_1-AU_AU_S");
	$resGetProject= mysqli_fetch_assoc($queryGetProject);
	
	$orderNo = $resGetSuppOrder['PoNum'];
	$Customer = $resGetSuppName['customername'];
	$DDate = $resGetSuppOrder['deleveryDate'];
	$Project = $resGetProject['projectName'];

?>	


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Order No: <?php echo $orderNo;?> Date : <?php echo date("d/m/Y");?>
&nbsp;For:&nbsp;<?php echo $Customer;?></title>
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
   
    	<p><b><span style="font-size:14px">Delivery Note</span>
        <br>
        <span style="font-size:12px">No:&nbsp; <?php echo $orderNo;?></span>
        <br>
       <span style="font-size:12px"> Project:&nbsp; <?php echo $Project;?></span>
        </b></p>
    </center>
   <table style="font-size:10px">
      <tr>
       <td style=""><b>To:&nbsp;</b>  <?php echo $Customer;?> </td>
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
<table class="table table-sm" height="auto" style="border:solid; border:1px">

    	<thead style="border-style:solid; border-width:2px; border-color:#000000;
        font-size:11px" class='bg-light'>
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
		//print_r($SelectedItems);
		
		//$DoorNo = array();
		//$Handling = array();
		//$RAL = array();
		//$overLap =array();
		
		for($ItemRID = 0; $ItemRID < $countSeletced ; $ItemRID++)
		{
			//echo "<b>";
			//$arrayKey = 0;
			
			//print_r($SelectedItems);
				
			
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
				//echo $resGetSuppItem['qty'];
			$sqlFetchItemDet="SELECT `ral`, `doornumber`, `handlingSupp` FROM `suppitemdetails` 
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
			}
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
     	<td class='th' align="center" colspan="3"><b>Total Doors QTY</b></td>
        <td class='th' align='center' colspan=""><b><?php echo $doorTotalQTY;?> &nbsp;</b>
        </td>
        <td class='th' align="center" colspan="9"></td>
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
 
<br>
<div align="left" style=" font-size:11px">
<p style="margin-left:5%;">
Best Regards
<br>
<span style="margin-left:2%;"><b><?php echo $_SESSION['fname'];?></b></span>
<br>
<span><b><?php echo $_SESSION['Dept'];?></b></span>
<div align="right" style="margin-top:-5%">
<span style="margin-right:4%;"><b>Name: ---------------------------------</b></span>
<br>
<br>
<br>
<span style="margin-right:4%;"><b>Signature: --------------------------------</b></span>
</div>

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

<?php

$action="Export Delivery Note for Project: $Project Customer: $Customer order Items: $doorType ";
		
$logRef=9;	
include_once("aduLog.php");
exit();			
?>     

</body>
</html>