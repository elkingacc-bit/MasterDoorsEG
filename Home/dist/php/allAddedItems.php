<style>
h1 {font-size:14px; font-weight:bold;
}
</style>


<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$Permissiom = $_SESSION['Dept'];

if($Permissiom =="Admin" || $Permissiom == "Manager")
{
	$diplay = "";
	$colspan = 12;
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

$jobRowIdOI = $_POST['OIJRID'];

$sqlGetItemRef = "SELECT  `id`, `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`,`itemm2`, 
`msquerprice`, `shipping`, `installation`, `margin`, `itemqty`, `totalprice`,  `itemRef`, `handling`,
 `doorNumber`, `FRMin`, `remarks`, `Overlap`, `itemRal` FROM `itemoffer` WHERE `jobref` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<div class='firstForm table-responsive'>
	<table class='table table-sm table-striped myTableOldItems' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Item</th>
                <th>Hight</th>
                <th>Width</th>
				<th>Depth</th>
                <th>M<sup>2</sup></th>
                <th style='display:$diplay;'>M<sup>2</sup> Price</th>
				<th  style='display:none'>Handling</th>
				<th  style='display:none'><span data-toggle='tooltip' data-placement='left' title='Door Number'>No</span></th>
				<th>F.R.Min</th>
				<th>Remarks</th>
				<th  style='display:none'>Overlap</th>
				<th  style='display:none'>RAL</th>
				<th><span data-toggle='tooltip' data-placement='left' title='Hardware Group Ref'>HW</span></th>
				<th>QTY</th>
				 <th style='display:$diplay;'>U-Price</th>
                <th style='display:$diplay;'><span data-toggle='tooltip' data-placement='left' 
				title='Included Hardware'>Total
				</span></th>
				<th></th>
				<th></th>
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{
	
	$sqlGetHWVal = "SELECT  SUM(`unitPrice` * `descripquantity`) AS totalHW FROM `offerproperties` 
	WHERE  `ioidref` = $resGetItemRef[id]";
	$queryGetHWVal = mysqli_query($link,$sqlGetHWVal)or die("ERROR :01-ANJ_GCN_S");
	if(mysqli_num_rows($queryGetItemRef) > 0)
	{
	$resGetHWVal = mysqli_fetch_assoc($queryGetHWVal);
	
	$totalItemPrice =($resGetItemRef['totalprice'] + ($resGetHWVal['totalHW']));
	$itemPrice = round(($resGetItemRef['totalprice'] / $resGetItemRef['itemqty']) + ($resGetHWVal['totalHW'] 
	/ $resGetItemRef['itemqty']));
	$itemHWPrice = round($resGetHWVal['totalHW'] / $resGetItemRef['itemqty']);
	$itemItemAndHWPrice = round($resGetHWVal['totalHW']);
	//$itemHWPrice = round($resGetHWVal['totalHW']/ $resGetItemRef['itemqty']);
	}
	else
	{
		$totalItemPrice =($resGetItemRef['totalprice']);
		$itemPrice = round($resGetItemRef['totalprice'] / $resGetItemRef['itemqty']);
		$itemHWPrice = 0;
		$itemItemAndHWPrice = 0 ;
	}
	echo "
		<tr>
			<td class='col-sm-2'>$resGetItemRef[itemtype]</td>
			<td class='col-sm-3'>$resGetItemRef[itemname]</td>
			<td class='col-sm-1'>$resGetItemRef[itemhight]</td>
			<td class='col-sm-1'>$resGetItemRef[itemwidth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemdepth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemm2]</td>
			<td style='display:$diplay;' class='col-sm-1'><span data-toggle='tooltip' data-placement='left' 
			data-html='true'title='Item Cost = 
			".round($resGetItemRef['totalprice'] / $resGetItemRef['itemqty'])."
			<br> include Shipping = $resGetItemRef[shipping]
			<br> Installation = $resGetItemRef[installation]
			<br> Margin = ".($resGetItemRef['margin'] * 100)."%
			'>
			$resGetItemRef[msquerprice]</span></td>
			 
			<td  style='display:none' class='col-sm-1'>$resGetItemRef[handling]</td>
			<td  style='display:none' class='col-sm-1'>$resGetItemRef[doorNumber]</td>
			<td class='col-sm-1'>$resGetItemRef[FRMin]</td>
			<td class='col-sm-1'>$resGetItemRef[remarks]</td>
			<td  style='display:none' class='col-sm-1'>$resGetItemRef[Overlap]</td>
			<td  style='display:none' class='col-sm-1'>$resGetItemRef[itemRal]</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Show Assigned HW' >
			<button class='btn btn-link showallHWMoadel' value='$jobRowIdOI,$resGetItemRef[itemRef]
			,$resGetItemRef[id]'><b>$resGetItemRef[itemRef]</b>
			</button>
			</span></td>
			<td class='col-sm-1'>$resGetItemRef[itemqty]</td>
			<td style='display:$diplay;' data-toggle='tooltip' data-placement='left' 
			title='Total HW= $itemHWPrice' class='col-sm-1'>".number_format($itemPrice)."</td>
			<td style='display:$diplay;' data-toggle='tooltip' data-placement='left' 
			title='Item HW= $itemItemAndHWPrice' class='col-sm-1'>".number_format($totalItemPrice)."</td>
			<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Edit'>
			<button class='btn btn-link btn-xs editItem' value='$resGetItemRef[id]'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'>
					</i>
			</button>
			</span></td>
			<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Remove'>
			<button class='btn btn-link btn-xs removeItem' value='$resGetItemRef[id]'>
					<i class='far fa-trash-alt' aria-hidden='true' style='font-size:16px;color:#d9534f'>
					</i>
			</button>
			</span></td>
		</tr>
	
	";
}
	
	echo "
	</tbody>
	<tfoot class='bg-light'>
       	   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
           <th></th>
           <th style='display:$diplay;'></th>
           <th  style='display:none'></th>
           <th  style='display:none'></th>
           <th></th>
           <th></th>
		   <th  style='display:none'></th>
		   <th  style='display:none'></th>
		   <th ></th>
		   <th ></th>
		   <th style='display:$diplay;'></th>
		   <th style='display:$diplay;'></th>
		   <th></th>
		   <th></th>
    </tfoot>
</table>
</div>
	";
}

?>		
    
      
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" id="rowIdJobLoadAllItem"/>
 <input type="text" value="<?php echo $Permissiom?>" style="display:none" id="userPermission"/>
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 

	var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();	

var dept = $("#userPermission").val();
	  var table = $('.myTableOldItems').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: true ,

  dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'All_Doors_Offers '+datetime,
			filename: function () {
			return "All_Doors_Offers" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,7,8,9,10,11,12,13,14]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Doors_Offers '+datetime,
			 filename: function () {
			return "All_Doors_Offers" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [0,1,2,3,4,5,7,8,9,10,11,12,13,14]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Maintenance Tracker System {Master Doors EG} | All Doors in Offers '+datetime,
	  footer: true,
	  //orientation: 'landscape',
	   exportOptions: {
		   		   
                   columns: [0,1,2,3,4,5,7,8,9,10,11,12,13,14]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.2; filter: alpha(opacity=15); width: 450px; height:200px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
	
	
	 var last = null;
                var current = null;
                var bod = [];
 
                var css = '@page { size: landscape; }',
                    head = win.document.head || win.document.getElementsByTagName('head')[0],
                    style = win.document.createElement('style');
 
                style.type = 'text/css';
                style.media = 'print';
 
                if (style.styleSheet)
                {
                  style.styleSheet.cssText = css;
                }
                else
                {
                  style.appendChild(win.document.createTextNode(css));
                }
 
                head.appendChild(style);
  },
	}
 ],			 

 
   "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( 14 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 14, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
			$(api.column(14 ).footer() ).html(
			Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
			 total = api
            .column( 16 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 16, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
			if(dept == "Admin" || dept == "Manager")
			{
				$(api.column(16).footer() ).html(
					Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			}
  		}
  		
});


$(".removeItem").click(function(){
	
	var remItemRowID = $(this).val();
	
	var confRemoveItem = confirm("confirm remove Item ?");
	var rmItemJRowId = $("#rowIdJobLoadAllItem").val();
	
	if(confRemoveItem === true)
	{
		$.ajax({
				
				url:"dist/php/removeItemFromJob.php",
				type:"POST",
				data:{TRIDItem:remItemRowID,RJROIFItem:rmItemJRowId},
				beforeSend: function(){
				$(".removeItem").prop('disabled', true);	
				},
				success: function(doneRMItem){
					
					if(doneRMItem == 1)
					{
						alert("Data Saved");
						$(".oldAddItems").html("");
						$(".oldAddItems").show("");
						$(".oldAddItems").load("dist/php/allAddedItems.php",{OIJRID:rmItemJRowId});
						$(".removeItem").prop('disabled', false);	
						$.ajax({
								url:"dist/php/loadTotalOffer.php",
								type:"POST",
								data:{TotalJobRID:rmItemJRowId},
								success: function(showOfferTotal){
									$(".TotalOffer").html(showOfferTotal);
								}
							});
					}
					else
					{
						alert(doneRMItem);
						$(".removeItem").prop('disabled', false);	
					}
					
					
				}
			
			});
	}
	  
	return false; 
	});
	
	 $('.showallHWMoadel').click(function () {
       
		var jobRowIDHWM =  $(this).val().split(',')[0];
		var itemRefHWM = $(this).val().split(',')[1];
		var itemRowIdHWM = $(this).val().split(',')[2];
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/showAssignedHWModel.php',
                type:'POST',
                data:{ModelJobRID:jobRowIDHWM, ModelItemHWRef:itemRefHWM, ModelItemRID:itemRowIdHWM},
                
				success: function(showHWData)
				{
				//alert(showHWData);
                $('.ShowHWData').html('');
                $('.ShowHWData').html(showHWData);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
	});

	$(".editItem").click(function(){
		
		var IRowIdForEdit = $(this).val();
		
		$.ajax({
				
			url:"dist/php/getItemDataForEdit.php",
			type:"POST",
			data:{IRIDFEdit:IRowIdForEdit},
			dataType: "json",
			cache: false,
			beforeSend: function(){
				
				$(".editItem").prop("disabled", true);
				
			},
			success: function(showItemDataEdit){
				
				$(".editItem").prop("disabled", false);
				  
			$("#itemType").val(showItemDataEdit.editItemType);
			$("#itemName").val(showItemDataEdit.editItemName);
			$("#itemHight").val(showItemDataEdit.editItemHight);
			$("#itemWidth").val(showItemDataEdit.editItemWidth);
			$("#itemDepth").val(showItemDataEdit.editItemDepth);
			$("#itemMSq").val(showItemDataEdit.editItemMsqu);
			$("#mSqPrice").val(showItemDataEdit.editItemMsquPrice);
			$("#MPrice").val(showItemDataEdit.editItemPrice);
			$("#MPrice").prop("disabled", false);
			$("#itemQty").val(showItemDataEdit.editItemQty);
			$("#Total").val(showItemDataEdit.editItemTotalPrice);
			$("#FRMin").val(showItemDataEdit.editItemFRMin);
			$("#Remarks").val(showItemDataEdit.editItemRemk);
			$("#Overlap").val(showItemDataEdit.editItemOverlap);
			$(".margin").val('');
			$(".shipping").val('');
			$(".Installation").val('');
			$(".margin").val(showItemDataEdit.editItemSF);
			$(".shipping").val(showItemDataEdit.editItemSipping);
			$(".Installation").val(showItemDataEdit.editItemInstall);
			$(".Handl").val(showItemDataEdit.editItemHandl);
			$(".DoorNum").val(showItemDataEdit.editItemDorNum);
			$(".ral").val(showItemDataEdit.editItemRal);
		
			$(".tooltip-inner").hide();
			$(".arrow").hide(); 
			$(".EditItemOfferTR").hide();
			$(".oldAddItems").hide();
			$(".oldAddItems").html('');
			$(".EditItemInOfferTR").show();
			$("#EditItemInOfferTR").show();
			$("#rowIdItemForEdit").val('');
			$("#rowIdItemForEdit").val(IRowIdForEdit);
			$(".backBTN").hide();
			$(".backBTN2").show();
			
			}
		
		});
		return false;
		});
	
});
 
 </script>