<?php 
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$idRowJobOld=$_GET['JobId'];
	
	$sqlGetAllNewJob="SELECT `customer`, `localref`, `vatstatus`, `projectName` FROM `job` 
	WHERE `jobId` = $idRowJobOld";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	$Project = $resGetAllNewJob['projectName'];
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$offerNo = $resGetAllNewJob['localref'];

$sqlGetOfferPolicy = "SELECT `validate`,  `deliver`, `paynote`, `attdName`, `offerNotes` FROM `offerpolicy`
 WHERE `JobRowID` = $idRowJobOld ";
$queryGetOfferPolicy=mysqli_query($link,$sqlGetOfferPolicy)or die("ERROR :03-SPOI_GON_S".mysqli_error($link));
	$resultGetOfferPolicy=mysqli_fetch_assoc($queryGetOfferPolicy);		

if($resGetAllNewJob['vatstatus'] == 1)
{
	$vatStat = "";
}
else
{
	$vatStat = "none";
}
	
?>	


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Offer No: <?php echo $offerNo;?> Date : <?php echo date("d/m/Y");?>
&nbsp;For:&nbsp;<?php echo $resGetCustomer['customername'];?></title>
<link rel="stylesheet" href="../../plugins/bootstrap5/css/bootstrap.min.css">
 <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../plugins/bootstrap5/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	window.print();
});
</script>

<style>
table {
  width: 100%;
}

table.print-content {
  font-size: 12px;
  border: 2px solid #000000;
  border-collapse: collapse !important;
  border:solid;
  
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
  .landscaep {size:A4 landscape}
  @page { size:margin: 0;}	
  body {
    zoom: 99%;
  }
  /*div.Portriy {
    page-break-before: always;
  }*/
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
    .watermark_text
  {
    width: 100%;
    color: #d0d0d0;
    font-size: 2.5em;
    text-align: center; 
    margin: 0 auto;
	margin-top:25%;
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
   
    	<p><b><span style="font-size:22px">Quotation</span>
        <br>
       <?php echo $offerNo . " | " .$Project ;?>
        </b></p>
    </center>
   <table>
      <tr>
       <td style=""><b>To:&nbsp;</b>  <?php echo $resGetCustomer['customername'];?> </td>
    <td width="50%"> </td> 
	<td><b>FROM&nbsp;</b>  <?php echo $_SESSION['fname'];?></td>
     </tr>
     
       <td> <b>Att:&nbsp;</b>  <?php echo $resultGetOfferPolicy['attdName'];?> </td>
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
<table class="" height="auto" style="border:solid; border:2px">

    	<thead style="border-style:solid; border-width:2px; border-color:#000000; ">
        	<td class='col-sm-1 th' style="width:5%" align="center"><b>Sr.</b></td>
            <td class='col-sm-1 th' style="width:10%" ><b>&nbsp;DoorType</b></td>
            <td class='col-sm-1 th' style="width:5%; display:none" align="center"><b>Door No</b></td>
            <td class='col-sm-2 th' style="width:20%" ><b>&nbsp;Item</b></td>
            <td class='col-sm-1 th' style="width:5%" align="center"><b>QTY</b></td> 
            <td class='col-sm-1 th' style="width:5%" align="center"><b>Width</b></td> 
            <td class='col-sm-1 th' style="width:5%" align="center"><b>Height</b></td> 
            <td class='col-sm-1 th' style="width:5%" align="center"><b>Depth</b></td> 
            <td class='col-sm-1 th' style="width:5%" align="center"><b>F.R Min.</b></td> 
            <td class='col-sm-1 th' style="width:5%" align="center"><b>HW</b></td> 
            <td class='col-sm-1 th' style="width:7%; display:none" align="center"><b>handle</b></td> 
            <td class='col-sm-1 th' style="width:7%" align="center"><b>Remarks</b></td> 
            <td class='col-sm-1 th' style="width:7%; display:none" align="center"><b>Frame Overlap</b></td>
            <td class='col-sm-1 th' style="width:7%; display:none" align="center"><b>RAL</b></td> 
            <td class='col-sm-1 th' style="width:7%" align="center"><b>U.Price</b></td> 
            <td class='col-sm-2 th' style="width:7%" align='center'><b>T.Price</b> </td>
        </thead>
        <tbody style="border-style:solid; border-width:2px; border-color:#000000; font-size:12px">
        	<?php
		$SN=1;
		
	$sqlFetchOldJob="SELECT `id`, `itemname`, `itemtype`, `itemhight`, `itemwidth`, `itemdepth`, `itemm2`, 
			`msquerprice`, `itemqty`, `totalprice`, `itemRef`,`handling`, `doorNumber`, `FRMin`, `remarks`, 
			`Overlap`, `itemRal` FROM `itemoffer` WHERE `jobref` = $idRowJobOld";
	 
	  $queyFetchOldJob=mysqli_query($link,$sqlFetchOldJob)or die("ERROR :04-FOJ_EOJO_S".mysqli_error($link));

   while($resFetchOldJob = mysqli_fetch_assoc($queyFetchOldJob))
   {
	   
	$sqlCheckHW="SELECT `offproId` FROM `offerproperties` 
	WHERE `ioidref` = $resFetchOldJob[id]";
	$queyCheckHW=mysqli_query($link,$sqlCheckHW)or die("ERROR :03_1-FOJ_EOJO_S".mysqli_error($link));   
	if(mysqli_num_rows($queyCheckHW) == 0)
	{
		$itemRef = 'Non';
		$totalHW = 0;
	}
	else
	{
		$sqlGetOldTotalHW = "SELECT SUM(`totalprice`) AS HWTotal FROM `offerproperties` 
		WHERE `ioidref` = $resFetchOldJob[id]";
		$queryGetOldTotalHW = mysqli_query($link,$sqlGetOldTotalHW)or die("ERROR :07-ANJ_GCN_S");
		$resGetOldTotalHW = mysqli_fetch_assoc($queryGetOldTotalHW);
	
		$itemRef = $resFetchOldJob['itemRef'];
		$totalHW = $resGetOldTotalHW['HWTotal'];
	}
	
echo "
    <tr>
   <td class='col-sm-1 td' style='width:5%' align='center'>$SN </td>
   	<td class='col-sm-1 td' style='width:10%'>&nbsp;".$resFetchOldJob['itemtype']."</td>
    <td class='col-sm-2 td' style='width:5%; display:none'>&nbsp;".$resFetchOldJob['doorNumber']."</td> 
	<td class='col-sm-2 td' style='width:20%'>&nbsp;".$resFetchOldJob['itemname']."</td>
     
    <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['itemqty']."</td>
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['itemwidth']."</td>
  
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['itemhight']."</td>
  
   <td class='col-sm-1 td' style='width:5%' align='center'>".$resFetchOldJob['itemdepth']."</td>
  
   <td class='col-sm-1 td' style='width:5%' align='center'>".$resFetchOldJob['FRMin']."</td>
  
   <td class='col-sm-1 td text-primary' style='width:5%' align='center'>".$itemRef."</td>
  
  <td class='col-sm-1 td' style='width:7%; display:none'  align='center'>".$resFetchOldJob['handling']."</td>
  
   <td class='col-sm-1 td' style='width:7%'  align='center'>".$resFetchOldJob['remarks']."</td>
  
   <td class='col-sm-1 td' style='width:7%; display:none' align='center'>".$resFetchOldJob['Overlap']."</td>
   
   <td class='col-sm-1 td' style='width:7%; display:none'  align='center'>".$resFetchOldJob['itemRal']."</td>
     
    <td class='col-sm-1 td' style='width:7%' align='center'>"
	.number_format(round($resFetchOldJob['totalprice'] + $totalHW) / $resFetchOldJob['itemqty'])."</td>
     
	
	<td class='col-sm-2 td' align='center' style='width:7%'>"
	.number_format(round($resFetchOldJob['totalprice']) + $totalHW)."
	</td>
   </tr>  
    
     
";
$SN++;
 }
 ?>

      
        	<?php
				$sqlGetTotalVal = "SELECT offerValue AS Total FROM `job` 
				WHERE `jobId` = $idRowJobOld ";
				$queyGetTotalVal=mysqli_query($link,$sqlGetTotalVal)or die("ERROR :05-FOJ_EOJO_S"
				.mysqli_error($link));
				$resGetTotalVal = mysqli_fetch_assoc($queyGetTotalVal);
				$SumTotal = $resGetTotalVal['Total'];
				$TotalVAT = round($SumTotal * .14);
				$G_Total = round($SumTotal + $TotalVAT);	
				
				$sqlGetAllQTY="SELECT SUM(`itemqty`) AS AllQTY FROM `itemoffer` 
				WHERE `jobref` = $idRowJobOld";
	 			$queyAllQTY=mysqli_query($link,$sqlGetAllQTY)or 
				die("ERROR :04_1-FOJ_EOJO_S".mysqli_error($link));
				$resAllQTY = mysqli_fetch_assoc($queyAllQTY);
			
			?>
                        
      <tr style="border-style:solid; border-width:2px; border-color:#000000; ">
     	<td class='th' align="center" colspan="2"></td>
        <td class='th' align="center" colspan="1"><b>Total QTY</b></td>
        <td class='th' align="center" colspan="1"><b><?php echo $resAllQTY['AllQTY'];?></b></td>
        <td class='th' align="center" colspan="6"><b>Total Without VAT </b> </td>
        <td class='th' align='center' colspan="2"><b><?php echo number_format($SumTotal) .
		"<sub style='font-size=16px'>";?> </sub></b> </td>
      
      </tr>
       <tr style="display:<?php echo $vatStat;?>">
     	<td class='th' align="center"  colspan="10"><b>VAT 14%
       </b> </td>
        <td class='th' align='center'  colspan="2"><b><?php echo number_format($TotalVAT)?></b> </td>
      </tr>
       <tr style="border-style:solid; border-width:2px; border-color:#000000;  display:<?php echo $vatStat;?>">
     	<td class='th' align="center"  colspan="10"><b>Total Including VAT 
        </b> </td>
        
        <td class='th' align='center'  colspan="2"><b><?php
		
		//setlocale(LC_MONETARY,"en_US");
		
		 echo number_format($G_Total);?>&nbsp;<sub style='font-size=16px'>L.E</sub></b></td> 
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
	 $chechLang = preg_match('/\p{Arabic}/u', $resultGetOfferPolicy['offerNotes']);
	 
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
 <div style="margin:2%" align="<?php echo $align;?>"><b>
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Material Cost:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['offerNotes']); ?></p>  
 
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Standard Payment Terms:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['paynote']); ?></p> 
 
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Delivery Time:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['deliver']); ?></p>  
 
 <p style="font-size:11px; font-family:'Arial; direction:<?php echo $direction;?>">
 <b>Validity of proposal:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['validate']); ?></p>  
 
</b></div>

<div class="Portriy" align="center">
	
<?php
$No=1;
$sqlFetchItemRef="SELECT `id`, `itemRef`, `itemqty` FROM `itemoffer` WHERE `jobref` = $idRowJobOld 
GROUP BY `itemRef`" ;
$queyFetchItemRef=mysqli_query($link,$sqlFetchItemRef)or die("ERROR :06-FOJ_EOJO_S".mysqli_error($link));   

if(mysqli_num_rows($queyFetchItemRef) > 0)
{
while($resFetchItemRef = mysqli_fetch_assoc($queyFetchItemRef))
	{
	echo "
		<table class='table table-bordered table-sm' style='width:45%; font-size:10px'>
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
		$hwQTY = ($resFetchHW['descripquantity'] / $resFetchItemRef['itemqty']);
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
			<tr >
				<td>$No</td>
				<td>$resFetchHWData[descriptionname]</td>
				<td>$manuf</td>
				<td>$hwQTY</td>
			</tr>
		
		";
		$No++;
	}
		
	echo "</table>";
	$No =1;
	}
}

?>

</div>	 


<div align="left" style="">
<p style="margin-left:5%;">
Best Regards
<br>
<span style="margin-left:2%;"><b><?php echo $_SESSION['fname'];?></b></span>
<br>
<span><?php echo $_SESSION['Dept'];?></span>
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