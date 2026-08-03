<style type="text/css">
.btn-link {
  padding-left: 0
}

</style>

<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['JRID'];
$ItemTypeInfo = $_POST['MoadelOrderType'];
$SuppOrderRowId = $_POST['ModelSuppORID'];

$sqlGetOfferNum="SELECT `localref`,`jobtype`, `projectName` FROM `job` WHERE `jobId` = $jobRowIdOI";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
?>
        
  <div class="body "> 
  
  <p class="title">
  <span data-toggle='tooltip' data-placement='right' title='back'>
<button class="btn btn-link btn-xs backBTN3">
<i class="fas fa-arrow-circle-left" aria-hidden="true" style="font-size:26px;color:#0275d8"></i>
</button>&nbsp;
Add Supply Item For Project Name:&nbsp; <span style="color:blue;">
        <b><?php echo $resGetOfferNum['projectName']; ?></b>
        </p>
  <center>
     
    <div class="AddSuppQTY" align="center" style="display:none; width:30%">
    	
        <table class="table table-sm">
        	<th>QTY</th>
            <td width="10px"></td>
            <td>
            <div class="col-xs-3">
            	<input type="number" min="1" class="form-control" id="addSuppQTYInput"/>
            </div>
            </td>
            <tr>
            <td colspan="3" align="center">
            	<button class="btn btn-sm btn-success" id="saveAddSuppQTYBTN">Add</button>    
            </td>
            </tr>
        </table>
    	
    </div>
</center>
  
  <div class="table-responsive">   
<?php
if($ItemTypeInfo == 'Doors')
{

$sqlGetItemRef = "SELECT  `id`, `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`,`itemm2`, 
`msquerprice`, `itemqty`, `totalprice`,  `itemRef`, `FRMin`, `remarks`, `Overlap` FROM `itemoffer` 
WHERE  `jobref` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableItemsSupply' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Item</th>
                <th>Width</th>
				<th>Hight</th>
				<th>Depth</th>
                <th>M<sup>2</sup></th>
                <th>QTY</th>
				<th>Remining</th>
				<th>F.R.Min</th>
				<th>Remarks</th>
				
				<th><span data-toggle='tooltip' data-placement='left' title='Add QTY'>
				</span></th>
             </thead>
			 <tbody class='table-bordered'>
	"; 

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{					
	$sqlGetTRecevQTY="SELECT SUM(`qty`) AS qty FROM `supporderitems` WHERE `ItemRowId` = $resGetItemRef[id] 
	AND `SOIdRef` = $SuppOrderRowId";
	$queryGetTRecevQTY=mysqli_query($link,$sqlGetTRecevQTY)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	$resGetTRecevQTY= mysqli_fetch_assoc($queryGetTRecevQTY);
		if( $resGetTRecevQTY['qty'] == "")
			{
				$reminingQTY = $resGetItemRef['itemqty'];
				$button = "<button class='btn btn-xm btn-link addSuppQTY' 
						value='$resGetItemRef[id],$ItemTypeInfo,$jobRowIdOI,$reminingQTY'>
						<i class='fa fa-cogs' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
						</button>";
				$titel = "add Supply QTY";		
				
				$buttonInfo = $resGetItemRef['itemtype'];
			}
		else if($resGetItemRef['itemqty'] > $resGetTRecevQTY['qty'])
			{
				 $reminingQTY = ($resGetItemRef['itemqty'] - $resGetTRecevQTY['qty']);
				 $button = "<button class='btn btn-xm btn-link addSuppQTY' 
						value='$resGetItemRef[id],$ItemTypeInfo,$jobRowIdOI,$reminingQTY'>
						<i class='fa fa-cogs' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
						</button>";
				$titel = "add Supply QTY";
				
				$buttonInfo = "<button class='btn btn-xm btn-link ShowAllDetials'
				value='$resGetItemRef[id],$jobRowIdOI,$SuppOrderRowId,$reminingQTY' 
				data-toggle='tooltip' data-placement='left' title='Door Details'> 
				$resGetItemRef[itemtype]</button>";
			
			}
			else
			{
				$button = "<button class='btn btn-xm btn-link EditSuppQTY' 
						value=
						'$resGetItemRef[id],$ItemTypeInfo,$jobRowIdOI,$SuppOrderRowId,$resGetItemRef[itemqty]'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i>
						</button>";
				$titel = "Edit Supply QTY";
				$reminingQTY = 0;
			
			$buttonInfo = "<button class='btn btn-xm btn-link ShowAllDetials'
				value='$resGetItemRef[id],$jobRowIdOI,$SuppOrderRowId' 
				data-toggle='tooltip' data-placement='left' title='Door Details'> 
				$resGetItemRef[itemtype]</button>";
			}
			
	
	echo " 
		<tr>
			<td class='col-sm-2'>$buttonInfo</td>
			<td class='col-sm-3'>$resGetItemRef[itemname]</td>
			<td class='col-sm-1'>$resGetItemRef[itemwidth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemhight]</td>
			<td class='col-sm-1'>$resGetItemRef[itemdepth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemm2]</td>
			<td class='col-sm-1'>$resGetItemRef[itemqty]</td>
			<td class='col-sm-1 text-primary'>$reminingQTY</td>
			<td class='col-sm-1'>$resGetItemRef[FRMin]</td>
			<td class='col-sm-1'>$resGetItemRef[remarks]</td>
			
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='$titel' >
			$button
			</span></td>
		</tr>
	
	";
}
	
	echo "
	</tbody>
	 <tfoot>
        <th></th>
		<th></th>
        <th></th>
		<th></th>
		<th></th>
        <th></th>
        <th>QTY</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		
    </tfoot>
</table>
	";
}//while
}

?>		
 </div>     
  </div>
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" id="rowIdJobLoadAllItem"/>
 <input type="text" value="<?php echo $ItemTypeInfo?>" style="display:none" id="jobTypeSupply"/>
 <input type="text" value="<?php echo $SuppOrderRowId?>" style="display:none" id="orderSupplyRID"/>
 <input type="text" value="" style="display:none" id="RemingQTY"/>
 <input type="text" value="" style="display:none" id="selectedItemRowId"/>
  
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  

var jobTypeFCBDT = $("#jobTypeSupply").val();
 
 if(jobTypeFCBDT == 'Doors')
		  {
			  var SumCloumn1 = 6;
			  var SumCloumn2 = 7;
		  }
		  else if(jobTypeFCBDT == 'Automatic')
		  {
			  var SumCloumn1 = 3;
			  var SumCloumn2 = 4;
		  }

 
	  var table = $('.myTableItemsSupply').DataTable( {
		  	 
	  		 fixedHeader: false,
             scrollY:'35vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 ordering:false,
			 searching: true ,
		  
 "footerCallback": function(row, data, start, end, display){
        var api = this.api(), data;
        var intval = function(i){
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1:
            typeof i === 'number' ?
            i : 0;
        };
        total = api
            .column( SumCloumn1 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( SumCloumn1, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( SumCloumn1 ).footer() ).html(pageTotal);
		
		total = api
            .column( SumCloumn2 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( SumCloumn2, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( SumCloumn2 ).footer() ).html(pageTotal).css("color","blue");	
			
  		}

});

$('.EditSuppQTY').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
		 
		 
		var ItemRowIDIo4 =  $(this).val().split(',')[0];
		var jobTypeIo4 = $(this).val().split(',')[1];
		var jobRowIDIo4 = $(this).val().split(',')[2];
		var SuppOrderIDIo4 = $(this).val().split(',')[3];
		var remingQTY4 = $(this).val().split(',')[4];
		
		
		$('#RemingQTY').val('');
		$('#RemingQTY').val(remingQTY4);
		$(".body").html('');
		$(".body").load('dist/php/EditSupplyDoorsDetiels.php',{itmRowId:ItemRowIDIo4, SuppORID:SuppOrderIDIo4, orderTy:jobTypeIo4, suppJobRID:jobRowIDIo4,loopQTY:remingQTY4});
		

		
return false;
});

$('.ShowAllDetials').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
		 //$(this).closest('tr').addClass('text-danger');
		 
		var ItemRowIDInfo =  $(this).val().split(',')[0];
		var jobRowIDInfo= $(this).val().split(',')[1];
		var SuppRowIdInfo= $(this).val().split(',')[2];

		$.ajax({
                url:'dist/php/suppDoorInfo.php',
                type:'POST',
                data:{ModelIemeRID:ItemRowIDInfo, MoadelJobRID:jobRowIDInfo,MoadelSuppRID:SuppRowIdInfo},
                
				success: function(showSuppDoorInfo)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showSuppDoorInfo);
				$(".myModal").modal('toggle');
				
				}         
        	});
		
return false;
});




$('.addSuppQTY').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
		 $(this).closest('tr').addClass('text-danger');
		 
		var ItemRowIDIo =  $(this).val().split(',')[0];
		var jobTypeIo = $(this).val().split(',')[1];
		var jobRowIDIo = $(this).val().split(',')[2];
		var remingQTYIo = $(this).val().split(',')[3];
		
		//alert(ItemRowIDIo);
		
		$('#addSuppQTYInput').val('');
		$('#selectedItemRowId').val('');
		$('#selectedItemRowId').val(ItemRowIDIo);
		$('#RemingQTY').val('');
		$('#RemingQTY').val(remingQTYIo);
       
		$(".AddSuppQTY").show();
		
return false;
});

	$('#saveAddSuppQTYBTN').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
	
		var jobRowIDSave =  $("#rowIdJobLoadAllItem").val();
		var jobTypeSave = $("#jobTypeSupply").val();
		var ItemRowIDSave = $("#selectedItemRowId").val();
		var suppORIDSave = $("#orderSupplyRID").val();
		var suppQTY = $("#addSuppQTYInput").val();
		var suppReminingQTY = $("#RemingQTY").val();
		suppQTY = Number(suppQTY);
		suppReminingQTY = Number(suppReminingQTY);
		if(suppQTY > suppReminingQTY)
		{
			alert("Added QTY large than the remining QTY expected <=" + suppReminingQTY);
		}
		else
		{
		
           	$.ajax({
                url:'dist/php/saveAddSupplyItemQTY.php',
                type:'POST',
                data:{SOIdRequ:suppORIDSave,JRIdRequ:jobRowIDSave, IRIdRequ:ItemRowIDSave,OTRequ:jobTypeSave,IQRequ:suppQTY},
                
				success: function(doneAddSuppQTY)
				{
					if(doneAddSuppQTY == 1)
					{
						$(".body").html('');
						$(".body").load('dist/php/addSupplyDoorsDetiels.php',{itmRowId:ItemRowIDSave, SuppORID:suppORIDSave, orderTy:jobTypeSave, loopQTY:suppQTY, suppJobRID:jobRowIDSave});
					}
					else
					{
						alert(doneAddSuppQTY);
					}
				
				}         
        	}); 
			
		}
		return false;
	});
	
	$(".backBTN3").click(function(){
		
		$(".tooltip-inner").hide();
		$(".arrow").hide();
		
		$("#5_2").click();
		
		return false;
		});
	
});
 
 </script>