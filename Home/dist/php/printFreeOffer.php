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

$sqlGetOfferPolicy = "SELECT `validate`,  `deliver`, `paynote`, `attdName`, `offerNotes`
FROM `offerpolicy` WHERE `JobRowID` = $idRowJobOld ";
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
<table class="table" height="auto" style="border:solid; border:1px">

    	<thead style="border-style:solid; border-width:2px; border-color:#000000;" class='bg-light'>
        	<td class='col-sm-1' style="width:5%" align="center"><b>Sr.</b></td>
            <td class='col-sm-4' style="width:30%" ><b>&nbsp;Type</b></td>
            <td class='col-sm-1' style="width:5%" align="center"><b>QTY</b></td> 
            <td class='col-sm-1' style="width:10%" align="center"><b>U.Price</b></td> 
            <td class='col-sm-1' style="width:10%" align='center'><b>T.Price</b> </td>
        </thead>
        <tbody style="border-style:solid; border-width:2px; border-color:#000000; font-size:12px">
        	<?php
		$SN=1;
		
	$sqlFetchOldJob="SELECT `type`, `price`, `typeqty`, `totalprice` 
   FROM `maintoffers` WHERE  `jobid` = $idRowJobOld"; 
	$queyFetchOldJob=mysqli_query($link,$sqlFetchOldJob)or die("ERROR :04-FOJ_EOJO_S".mysqli_error($link));
   while($resFetchOldJob = mysqli_fetch_assoc($queyFetchOldJob))
   {
	   
echo "
    <tr>
   <td class='col-sm-1 td' style='width:5%' align='center'>$SN </td>
   	<td class='col-sm-4 td' style='width:30%'>&nbsp;".$resFetchOldJob['type']."</td>
       
   <td class='col-sm-1 td'style='width:5%'  align='center'>".$resFetchOldJob['typeqty']."</td>
  
   <td class='col-sm-1 td' style='width:10%' align='center'>".number_format($resFetchOldJob['price'])."
   </td>

	<td class='col-sm-1 td' align='center' style='width:10%'>".number_format($resFetchOldJob['totalprice'])."
	<sub style='font-size=16px'>L.E</sub></td>
   </tr>  
    
     
";
$SN++;
 }
 ?>

      
        	<?php
				$sqlGetTotalVal = "SELECT SUM(`totalprice` ) AS Total FROM `maintoffers` 
				WHERE `jobid` = $idRowJobOld ";
				$queyGetTotalVal=mysqli_query($link,$sqlGetTotalVal)or die("ERROR :05-FOJ_EOJO_S"
				.mysqli_error($link));
				$resGetTotalVal = mysqli_fetch_assoc($queyGetTotalVal);
				$SumTotal = $resGetTotalVal['Total'];
				$TotalVAT = round($SumTotal * .14);
				$G_Total = round($SumTotal + $TotalVAT);	
			
			
			?>
                        
      <tr style="border-style:solid; border-width:2px; border-color:#000000; ">
     	<td class='th' align="center" colspan="3"><b>Total</b></td>
        <td class='th' align='center' colspan="2"><b><?php echo number_format($SumTotal);?> &nbsp;
        <sub style='font-size=16px'>L.E</sub></b> </td>
      
      </tr>
       <tr style="display:<?php echo $vatStat;?>">
     	<td class='th' align="center"  colspan="3"><b>VAT 14%
       </b> </td>
        <td class='th' align='center'  colspan="2"><b><?php echo number_format($TotalVAT)?></b> </td>
      </tr>
       <tr style="border-style:solid; border-width:2px; border-color:#000000; display:<?php echo $vatStat;?>">
     	<td class='th' align="center"  colspan="3"><b>Total Including VAT 
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
 <div style="margin:2%" align="left"><b>
 <p style="font-size:11px; font-family:'Arial; direction:left">
 <b>Material Cost:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['offerNotes']); ?></p>  
 
 <p style="font-size:11px; font-family:'Arial; direction:left">
 <b>Standard Payment Terms:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['paynote']); ?></p> 
 
 <p style="font-size:11px; font-family:'Arial; direction:left">
 <b>Delivery Time:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['deliver']); ?></p>  
 
 <p style="font-size:11px; font-family:'Arial; direction:left">
 <b>Validity of proposal:</b>&nbsp;<br><?php echo ($resultGetOfferPolicy['validate']); ?></p>  
 
</b></div>
<br>
<div align="left" style="">
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