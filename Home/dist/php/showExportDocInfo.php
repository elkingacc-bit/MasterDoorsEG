<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
	 
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
 
 $DocNumbExport = $_POST['DNumExpt'];

	$sqlGetExportDoc="SELECT  DATE(`date`) AS ExportDate, 
	TIME(`date`) AS ExportTime, `poIdRef`, `custcode`, `responsible`, `docSerial` FROM `warehouse` 
	WHERE `poIdRef` != 0 AND `custcode` IS NOT NULL AND `docSerial` = $DocNumbExport";
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
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Document No:&nbsp;<span style="color:blue;"><b><?php echo $DocNumbExport; ?>
        </b></span> For Customer:&nbsp;<span style="color:blue;"><b><?php echo $resultGetCust['customername'];
		?></b></span>&nbsp;Project:&nbsp;<span style="color:blue;"><b><?php echo 
		$resGetJobRef['projectName'] . "/ PO: ". $resGetJobRef['PoNum'];?></b></span>
        &nbsp;Date:&nbsp;<span style="color:blue;">
        <b><?php echo $resGetExportDoc['ExportDate']."-". $resGetExportDoc['ExportTime'];?></b>
 </div>
       <div class="modal-body ">
      <div class="table-responsive"> 
<table class="table table-sm myTableDocInfo"  style="width:100%">
   
      <thead class="bg-info">
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th style="display:<?php echo $diplay;?>">Price</th>
        <th style="display:<?php echo $diplay;?>">Total</th>
       
      </thead>
      <tbody>

<?php 
 $sqlGetItem="SELECT `description`, `export`, `salesprice` FROM`warehouse` WHERE  `poIdRef` != 0 AND `custcode` 
 IS NOT NULL AND `docSerial` = $DocNumbExport";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
while($resGetItem = mysqli_fetch_assoc($queryGetItem))
{

	$sqlGetHWName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` = 
	$resGetItem[description]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :02-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);

echo "
		<tr>
			<td>$resGetHWName[partnumber]</td>
			<td>$resGetHWName[descriptionname]</td>
			<td>$resGetItem[export]</td>
			<td style='display:$diplay;'>".number_format($resGetItem['salesprice'])."</td>
			<td style='display:$diplay;'>"
			.number_format(($resGetItem['export'] * $resGetItem['salesprice'])).
			"</td>
			
		</tr>
	
	";
}
 }
 ?>
  </tbody>
      <tfoot class="bg-light">
       	<th></th>
        <th></th>
        <th></th>
        <th style="display:<?php echo $diplay;?>"></th>
        <th style="display:<?php echo $diplay;?>"></th>
      </tfoot>
 </table>
 
  </div>
  </div>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  
	  var table3 = $('.myTableDocInfo').DataTable( {
	 
	  		 fixedHeader: false,
             //scrollY:'25vh',
			 deferRender:true,
			 //scrollX: true,
        	 //scrollCollapse: true,
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
            .column( 4 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 4, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 4 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

	});
	
});
 
 </script>