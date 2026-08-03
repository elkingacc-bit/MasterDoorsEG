<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$orderRowId = $_POST['ExptSORowId'];
$OrderType = $_POST['ExpotOTypeR'];
$jobRowIDForEdit = $_POST['ExptRJobRID'];

?>
 <input type="text" value="<?php echo $orderRowId;?>" style="display:none" id="recevSOrderRID"/>
  <input type="text" value="<?php echo $OrderType;?>" style="display:none" id="recevSOrderType"/>
 <input type="text" value="<?php echo $jobRowIDForEdit;?>" style="display:none" id="recevSOJobRowId"/> 
  <div class="modal-header">
        <h5 class="modal-title">Export Supply Order</h5>
      </div>
 <div class="modal-body">
    <div class="exportFormDiv" align="left" style="margin-right:2%">
 	<table>
    	<th>Attintion</th>
        <td style="width:20px"></td>
        <td>
        	<input type="text" id="attName" class="form-control" />
        </td>
        <td style="width:20px"></td>
        <td align="center" colspan="">
        	<button class="btn btn-sm btn-success" id="saveExportSupplyOrderBTN">Export</button>
        </td>
        
    </table>
 </div> 
 <div class="table-responsive">
 <table class=" table  table-bordered myTableExptSupp" cellspacing="0" width="99%">
  <?php
  $ser = 1;
  if($OrderType == "Doors")
	{
		
	echo"
		<thead class='bg-info'>
             	<th>No</th>
				<th>Type</th>
				<th>Item</th>
                <th>Hight</th>
                <th>Width</th>
				<th>Depth</th>
                <th>M<sup>2</sup></th>
                <th>QTY</th>
				<th>Handling</th>
				<th>Overlap</th>
				<th>RAL</th>
				<th>
				<input type='checkbox' id='checkAll' checked disabled/>
				</th>
		</thead>
		<tbody>		
		
	";
	$sqlGetOrderItem="SELECT `OIId`,`ItemRowId`, (`qty` - `receivedQTY`) AS qty, `Handle`, `Overlap`, 
	`RAL` FROM `supporderitems` WHERE `SOIdRef` = $orderRowId AND `OIRef` != 2 AND `OIRef` != 3";
	$queryGetOrderItem=mysqli_query($link,$sqlGetOrderItem)or die("ERROR :01-AU_AU_S");
	while($resGetOrderItem= mysqli_fetch_assoc($queryGetOrderItem))
	{
			$sqlGetItemData = "SELECT `itemtype`, `itemname`,  `itemhight`, `itemwidth`, 
			`itemdepth`,`itemm2`,`itemqty`, `handling`, `Overlap`, `itemRal`
			 FROM `itemoffer` WHERE `id` = $resGetOrderItem[ItemRowId]";
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
			
		/*$sqlFetchItemDet="SELECT `ral`, `doornumber`, `handlingSupp`, `overlap` FROM `suppitemdetails` 
			WHERE `oiidRef` = ".$resGetOrderItem['OIId']."";
	 	    $queyFetchItemDet=mysqli_query($link,$sqlFetchItemDet)or die("ERROR :04-FOJ_EOJO_S".
			mysqli_error($link));
			if(mysqli_num_rows($queyFetchItemDet) > 0 )
			{
			$resFetchItemDet = mysqli_fetch_assoc($queyFetchItemDet);
				$doornumber = $resFetchItemDet['doornumber'];
				$ral = $resFetchItemDet['ral'];
				$handlingSupp = $resFetchItemDet['handlingSupp'];
				$overlap = $resFetchItemDet['overlap'];
			}
			else
			{
				$doornumber = "";
				$ral = "";
				$handlingSupp = "";
				$overlap = "";
			}
			*/
			if($resGetOrderItem['qty'] != 0)
			{
				echo "
				<tr class='validateCheckBox'>
				<td class='col-sm-1' > $ser</td>
				<td class='col-sm-1' > $resGetItemData[0]</td>
				<td class='col-sm-3'> $resGetItemData[1]</td>
				<td class='col-sm-3'> $resGetItemData[2]</td>
				<td class='col-sm-1'> $resGetItemData[3]</td>
				<td class='col-sm-1'> $resGetItemData[4]</td>
				<td class='col-sm-1'> $resGetItemData[5]</td>
				<td class='col-sm-1'> $resGetOrderItem[qty]</td>
				<td class='col-sm-1'> $resGetOrderItem[Handle]</td>
				<td class='col-sm-1'> $resGetOrderItem[Overlap]</td>
				<td class='col-sm-1'> $resGetOrderItem[RAL]</td>
				<td class='col-sm-1' > 
				<input type='checkbox' value='$resGetOrderItem[OIId]' class='selectedItem' name='selectedItem[]' checked disabled/>
				</td>
				</tr>
				
				";	
			}
	}
		
	}
else if($OrderType == "Automatic")
	{
		echo"
		<thead class='bg-info'>
             	<th>Type</th>
				<th>Door Specs</th>
                <th>Motor Specs</th>
                <th>QTY</th>
				<th>Supply</th>
				<th>Select</th>
		</thead>
		<tbody>		
		
		";
	
  
		
	$sqlGetOrderItem="SELECT `OIId`,`ItemRowId`, (`qty` - `receivedQTY`) AS qty FROM `supporderitems` 
	WHERE `SOIdRef` = $orderRowId AND `OIRef` != 2 AND `OIRef` != 3";
	$queryGetOrderItem=mysqli_query($link,$sqlGetOrderItem)or die("ERROR :01-AU_AU_S");
	while($resGetOrderItem= mysqli_fetch_assoc($queryGetOrderItem))
	{
			$sqlGetItemData = "SELECT `doortype`, `doorspecs`, `motorspecs`,`doorqty` FROM `autodoorsoffer`
			 WHERE `id` = $resGetOrderItem[ItemRowId]";
			$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
			$resGetItemData= mysqli_fetch_array($queryGetItemData);
		
        echo "
		<tr class='validateCheckBox'>
        <td class='col-sm-1' class='ItemTypeTh'> $resGetItemData[0]</td>
        <td class='col-sm-3'> $resGetItemData[1]</td>
        <td class='col-sm-3'> $resGetItemData[2]</td>
        <td class='col-sm-1'> $resGetItemData[3]</td>
        <td class='col-sm-1'> $resGetOrderItem[qty]</td>
        <td class='col-sm-1' > 
		<input type='checkbox' value='$resGetOrderItem[OIId]' class='selectedItem form-control'
		 name='selectedItem[]' style=' transform: scale(.5)'/>
		</td>
        </tr>
		";
	}
	
	}
 ?>
	</tbody>
    
 </table> 
 
 </div>
 <script type="text/javascript">
 	$(document).ready(function() {
 
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
        
		var OrderTypeDTable= $("#recevSOrderType").val();
		
		if(OrderTypeDTable == 'Doors')
		{
			numberOfCloumn = [0,1,2,3,4,5,6,7,8,9];
		}
		else if(OrderTypeDTable == 'Automatic')
		{
			numberOfCloumn = [0,1,2,3,4];	
		}
		
		var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();			
   
   var table = $('.myTableExptSupp').DataTable( {
	 
	  fixedHeader: true,
             //scrollY:'35vh',
			 //scrollX: true,
        	 scrollCollapse: true,
        	 //paging: false,	
			 order:[[0, "asc"]], 
		 
 dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'All_New_Supp_Order '+datetime,
			filename: function () {
			return "All_New_Supp_Order" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: numberOfCloumn 
            },
			footer: false,
			
		},
		
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | All New Supplier Order '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: numberOfCloumn 
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:7cm; left:30%; opacity: 0.2; filter: alpha(opacity=15); width: 450px; height:200px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
	}
 ],			 
 
   });
	 
	
		
	$("#saveExportSupplyOrderBTN").click(function(){
		var suppOrderTID = $('#recevSOrderRID').val();
		var JobRowTID = $('#recevSOJobRowId').val();
		var suppOrderType = $('#recevSOrderType').val();
		var AttVal = $('#attName').val();
		var ids = [];
		$('.selectedItem:checked').each(function() {
   ids.push(this.value); 
});		
		AttVal = AttVal.replace(/^\s+|\s+$|\s+(?=\s)/g, "");	
		
		if(AttVal == "")
		{
			alert('Please Add Supplier Staff Attention Name');
				$("#attName").css("border-color","red");
				setTimeout(function(){
				   $("#attName").css("border-color","#EBEBEB");    						
				   $("#attName").focus();							
				}, 1500);
		}
		else if(!$(".selectedItem").is(':checked'))
		{
			alert('Please Check one item to export order form');
				$(".validateCheckBox").addClass('bg-danger');
				setTimeout(function(){
				   $(".validateCheckBox").removeClass('bg-danger');  												
				}, 1500);		 
		} 
		else
		{
			if(suppOrderType == 'Doors')
			{
				var newDocPrint = window.open('dist/php/printExportSuppOrderD.php?&Atntion='+AttVal+'&OrderTableRID='+suppOrderTID+'&OrderTypeExp='+suppOrderType+'&jojbTableRowId='+JobRowTID+'&selectedItem='+ids,"_balnk");	
				setTimeout(function(){
					$('.ShowData').html('');
					$(".myModal").modal('toggle');
				}, 1500);

			}
			else if(suppOrderType == 'Automatic')
			{
					var newDocPrint = window.open('dist/php/printExportSuppOrderA.php?&Atntion='+AttVal+'&OrderTableRID='+suppOrderTID+'&OrderTypeExp='+suppOrderType+'&jojbTableRowId='+JobRowTID+'&selectedItem='+ids,"_balnk");	
				setTimeout(function(){
					$('.ShowData').html('');
					$(".myModal").modal('toggle');
				}, 1500);

			}
		}
			
			
			
				
			
			
			return false;
			});		
    });
 
 
 </script>  
