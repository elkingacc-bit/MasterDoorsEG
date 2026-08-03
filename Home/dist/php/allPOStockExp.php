<!doctype html>
<html>
<style>
.btn-link {
  padding-left: 0
}

</style>
<center>
<div align="center" class="table-responsive" >
	<table class="table table-sm myTableExptPOStock table-striped table-bordered" cellspacing="0" 
    style="width:90%" >
          <thead class="bg-info">
            <th class='col-sm-3'>Part No.</th>
            <th class='col-sm-3'>Item</th>
        	<th class='col-sm-1'>Order QTY</th>
            <th class='col-sm-1'>Exported</th>
            <th class='col-sm-1'>Export</th>
          </thead>
          <tbody>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");		  
 $PONum = $_POST['PoNumGet'];
 $PORID = $_POST['PoIdGet'];

$sqlGetOrderType = "SELECT `orderType`, `jobidref` FROM `customerpo` WHERE `poId` = $PORID ";
$queryGetOrderType=mysqli_query($link,$sqlGetOrderType)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
$resGetOrderType= mysqli_fetch_assoc($queryGetOrderType);

	$sqlGetItemData="SELECT `offproId`, `descripcode` FROM `offerproperties` WHERE   
	`jobidref` = $resGetOrderType[jobidref] GROUP BY `descripcode`";
	$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	while($resGetItemData= mysqli_fetch_assoc($queryGetItemData))
	{
	 
	$sqlGetExptQTY="SELECT SUM(`export`) AS ExptQTY FROM `warehouse`
	WHERE `description` = $resGetItemData[descripcode] AND `poIdRef` = $PORID";
	$queryGetExptQTY=mysqli_query($link,$sqlGetExptQTY)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetExptQTY= mysqli_fetch_assoc($queryGetExptQTY);
	
	if($resGetExptQTY['ExptQTY'] == "")
	{
		$exported = 0;
	}
	else
	{
		$exported = $resGetExptQTY['ExptQTY'];
	}
	
	$sqlGetRExptQTY="SELECT SUM(`exptqty`) AS replaceExptQTY FROM `replacedexpt`
	WHERE `porefrowid` = $PORID AND `offereditemcode` = $resGetItemData[descripcode]";
	$queryGetRExptQTY=mysqli_query($link,$sqlGetRExptQTY)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetRExptQTY= mysqli_fetch_assoc($queryGetRExptQTY);
		
	$sqlGetAllQTY="SELECT SUM(`descripquantity`) AS QTY FROM `offerproperties`
	WHERE `descripcode` = $resGetItemData[descripcode] AND `jobidref` = $resGetOrderType[jobidref]";
	$queryGetAllQTY=mysqli_query($link,$sqlGetAllQTY)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetAllQTY= mysqli_fetch_assoc($queryGetAllQTY);
	
	if($resGetRExptQTY['replaceExptQTY'] == "")
	{
		$exportedRStock = 0;
	}
	else
	{
		$exportedRStock = $resGetRExptQTY['replaceExptQTY'];
	}
	
		if($exportedRStock > 0)
		{
			$newExportedwithReplaced=($exported + $exportedRStock);
			$exportedStockBtn = "<button class='btn btn-link showExptDetails' data-toggle='tooltip' 
			data-placement='left' title='Show Exported Items Details'
			  value='$resGetItemData[descripcode],$PORID'>".$newExportedwithReplaced ."</button>";
		}
		else
		{
			$newExportedwithReplaced= ($exported + $exportedRStock);
			$exportedStockBtn =$newExportedwithReplaced;
		}
		
	$sqlGetPartNum="SELECT `descriptionname`, `partnumber` FROM `stockitems` 
	WHERE `description` = $resGetItemData[descripcode]";
	$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-AM_AMDL_S".mysqli_error($link));
	$resGetPartNum= mysqli_fetch_assoc($queryGetPartNum);
	
	if($resGetAllQTY['QTY'] == $newExportedwithReplaced)
	{
		$disabled = 'disabled';
		$title = 'Exported All';
	}
	else
	{
		$disabled = '';
		$title = 'Remining= '. ($resGetAllQTY['QTY'] - $newExportedwithReplaced);
	}
	
	echo " 
		<tr>
			<td class='col-sm-3'>$resGetPartNum[partnumber]</td>
            <td class='col-sm-3'>$resGetPartNum[descriptionname]</td>
            <td class='col-sm-1'>$resGetAllQTY[QTY]</td>
			<td class='col-sm-1'>$exportedStockBtn</td>
            <td class='col-sm-1'>
				<button class='btn btn-sm btn-priamry exportItem				'value='$resGetItemData[offproId],$resGetPartNum[partnumber],$resGetPartNum[descriptionname],$resGetAllQTY[QTY],$newExportedwithReplaced,$PORID,$PONum' $disabled data-toggle='tooltip' data-placement='left' title='$title'>
			<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'></i></button>
			</td>
		
		</tr>
	
	";
	 
	}
		  
?> 	
    </tbody>  
     	<tfoot >
            <th class='col-sm-3'></th>
            <th class='col-sm-3'></th>
        	<th class='col-sm-1'></th>
            <th class='col-sm-1'></th>
            <th class='col-sm-1'></th>
          </tfoot>
</table>

</div>
</center>
<script type="text/javascript">
$(document).ready(function() {

 $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
  
  var table = $('.myTableExptPOStock').DataTable( {
	 
	  fixedHeader: false,
             scrollY:'35vh',
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "asc"]],  
  "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 2 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 2, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 2 ).footer() ).html(pageTotal);	
		
		total = api
            .column( 3 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 3, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 3 ).footer() ).html(pageTotal);	
			
  		}

   });


$('.exportItem').click(function () {
       
		var OpropRID =  $(this).val().split(',')[0];
		var partNum = $(this).val().split(',')[1];
		var ItemName = $(this).val().split(',')[2];
		var orderQTY = $(this).val().split(',')[3];
		var Exported = $(this).val().split(',')[4];
		var PORID = $(this).val().split(',')[5];
		var PORNum = $(this).val().split(',')[6];
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/showExptStockModel.php',
                type:'POST',
                data:{OPRID:OpropRID, PartNo:partNum, ItemName:ItemName, OQTY:orderQTY,Expt:Exported,orderRowId:PORID,orderNum:PORNum},
                
				success: function(showExptData)
				{
				//alert(showHWData);
				$(".modal-dialog").removeClass("modal-lg");
				$(".modal-dialog").addClass("modal-sm");
                $('.ShowHWDataHist').html('');
                $('.ShowHWDataHist').html(showExptData);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
	});
	
	$(".showExptDetails").click(function(){
		
		var ExptItemCode =  $(this).val().split(',')[0];
		var ExptPoRowId = $(this).val().split(',')[1];
		
			$(".modal-dialog").removeClass("modal-sm");
				$(".modal-dialog").addClass("modal-lg");
                $('.ShowHWDataHist').html('');
                $('.ShowHWDataHist').load("dist/php/showExptOrderDetails.php",
				{PORIDExpt:ExptPoRowId,itemCodeExpt:ExptItemCode});
				$(".myModal").modal('toggle');
		
		return false;
		});
	
});


</script>
</html>