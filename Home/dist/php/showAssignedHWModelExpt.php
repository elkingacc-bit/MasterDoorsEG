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
	 
  
 $JobRowId = $_POST['ModelJobRID'];
 $ItemRef = $_POST['ModelItemHWRef'];
 $ItemRowId = $_POST['ModelItemRID'];
 $jobType = $_POST['ModelJobType'];
 
	 $sqlGetItemRef = "SELECT `itemname`, `itemqty` FROM `itemoffer` WHERE `id` = $ItemRowId";
	$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");
	$resGetItemRef = mysqli_fetch_assoc($queryGetItemRef);
	
$itemName = $resGetItemRef['itemname'];
$itemQTY = $resGetItemRef['itemqty'];
	
?>
 <div class="modal-header">
        <h5 class="modal-title">All Assigned HW for Item <span style="color:blue;"><b><?php echo $itemName; ?>
        </b></span> Group Reference:&nbsp;<span style="color:blue;"><b><?php echo $ItemRef; ?></b></span></h5>
 <span>
<button class="btn btn-xs" id="BakToItem">
<i class="fas fa-arrow-circle-left" aria-hidden="true" style="font-size:26px;color:#0275d8"></i>
</button>
</span>
      </div>
       <div class="modal-body ">
<table class="table table-sm myTableHWExpt"  style="width:100%">
   
      <thead class="bg-info">
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th style="display:<?php echo $diplay;?>">Price</th>
        <th style="display:<?php echo $diplay;?>">Total</th>
      </thead>
      <tbody>

<?php 
 $sqlGetItem="SELECT `offproId`, `descripcode`, `descripquantity`, `unitPrice`, `totalprice` FROM 
 `offerproperties` WHERE `ioidref` = $ItemRowId";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
while($resGetItem = mysqli_fetch_assoc($queryGetItem))
{

$unitPrice = $resGetItem['unitPrice'];
	$sqlGetHWName = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` = 
	$resGetItem[descripcode]";
	$queryGetHWName = mysqli_query($link,$sqlGetHWName)or die("ERROR :02-ANJ_GCN_S");
	$resGetHWName = mysqli_fetch_assoc($queryGetHWName);
	
	$HwQTY = ($resGetItem['descripquantity'] / $itemQTY);
	$totalHWPrice = round($HwQTY * $unitPrice);

echo "
		<tr>
			<td>$resGetHWName[partnumber]</td>
			<td>$resGetHWName[descriptionname]</td>
			<td>$HwQTY</td>
			<td style='display:$diplay;'>".number_format($resGetItem['unitPrice'])."</td>
			<td style='display:$diplay;'>".number_format($totalHWPrice)."</td>
			
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
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoad"/>
  <input type="text" value="<?php echo $itemName?>" style="display:none" id="ItemNameLoad"/>
  <input type="text" value="<?php echo $ItemRef?>" style="display:none" id="itemRef"/>
  <input type="text" value="<?php echo $ItemRowId?>" style="display:none" id="itemRowIdM"/>
  <input type="text" value="<?php echo $jobType ?>" style="display:none" id="jobType"/>
   <input type="text" value="<?php echo $Permissiom?>" style="display:none" id="userPermission4"/>

 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  
var dept4 = $("#userPermission4").val();

	  var table3 = $('.myTableHWExpt').DataTable( {
	 
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
			if(dept4 == "Admin" || dept4 == "Manager")
			{
        $(api.column( 4 ).footer() ).html(
			Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			}
  		}

});


$("#BakToItem").click(function(){
	
	var backjobType = $("#jobType").val();
	var backjobRowId = $("#rowIdJobLoad").val();
	
	$('.ShowData').html('');
    $('.ShowData').load("dist/php/showAlljobInfo.php",{JRIDInfo:backjobRowId,ITInfo:backjobType});
	return false;
	});
	
});
 
 </script>