<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['JRIDInfo'];
$ItemTypeInfo = $_POST['ITInfo'];

$sqlGetOfferNum="SELECT `localref`,`jobtype`, `projectName` FROM `job` WHERE `jobId` = $jobRowIdOI";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
?>
<div class="modal-header">

        <h5 class="modal-title">All Item Info For Project Name:&nbsp; <span style="color:blue;">
        <b><?php echo $resGetOfferNum['projectName']; ?></b>
        </h5>
</div>
  <div class="modal-body "> 
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
	<table class='table table-striped myTableItemsInfo' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Item</th>
                <th>Hight</th>
                <th>Width</th>
				<th>Depth</th>
                <th>M<sup>2</sup></th>
                <th>QTY</th>
				<th>F.R.Min</th>
				<th>Remarks</th>
				<th>Overlap</th>
				<th><span data-toggle='tooltip' data-placement='left' title='Hardware Group Ref'>HW</span></th>
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{
	
	$sqlGetHWVal = "SELECT  SUM(`totalprice`) AS totalHW FROM `offerproperties` WHERE  `jobidref` = $jobRowIdOI
	AND `offerItemRef` = '$resGetItemRef[itemRef]'";
	$queryGetHWVal = mysqli_query($link,$sqlGetHWVal)or die("ERROR :01-ANJ_GCN_S");
	if(mysqli_num_rows($queryGetItemRef) > 0)
	{
	$resGetHWVal = mysqli_fetch_assoc($queryGetHWVal);
	
	$totalItemPrice = round(($resGetItemRef['totalprice'] + $resGetHWVal['totalHW']));
	}
	else
	{
		$totalItemPrice = $resGetItemRef['totalprice'];
	}
	echo "
		<tr>
			<td class='col-sm-2'>$resGetItemRef[itemtype]</td>
			<td class='col-sm-3'>$resGetItemRef[itemname]</td>
			<td class='col-sm-1'>$resGetItemRef[itemhight]</td>
			<td class='col-sm-1'>$resGetItemRef[itemwidth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemdepth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemm2]</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' 
			<td class='col-sm-1'>$resGetItemRef[itemqty]</td>
			<td class='col-sm-1'>$resGetItemRef[FRMin]</td>
			<td class='col-sm-1'>$resGetItemRef[remarks]</td>
			<td class='col-sm-1'>$resGetItemRef[Overlap]</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Show Assigned HW' >
			<button class='btn btn-link showallHWMoadel' value='$jobRowIdOI,$resGetItemRef[itemRef]
			,$resGetItemRef[id],$resGetOfferNum[jobtype]'><b>$resGetItemRef[itemRef]</b>
			</button>
			</span></td>
		</tr>
	
	";
}
	
	echo "
	</tbody>
</table>
	";
}//while
}
else if($ItemTypeInfo == 'Automatic')
{
	$sqlGetItemRef = "SELECT  `id`, `doortype`, `doorspecs`, `motorspecs`, `doorprice`,
	 `doorqty`, `totalprice` FROM `autodoorsoffer` WHERE  `jobid` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableItemsInfo' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Door Specs</th>
                <th>Motor Specs</th>
                <th>QTY</th>
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{
	
	echo "
		<tr>
			<td class='col-sm-2'>$resGetItemRef[doortype]</td>
			<td class='col-sm-3'>$resGetItemRef[doorspecs]</td>
			<td class='col-sm-3'>$resGetItemRef[motorspecs]</td>
			<td class='col-sm-1'>$resGetItemRef[doorqty]</td>
		</tr>
	
	";
}
	
	echo "
	</tbody>
</table>
	";
}
}

else if($ItemTypeInfo == 'Maintenance')
{
	$sqlGetItemRef = "SELECT  `id`,`type`, `price`, `typeqty`, `totalprice` 
	FROM `maintoffers` WHERE  `jobid` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

	if(mysqli_num_rows($queryGetItemRef) > 0)
	{
		echo "
		<table class='table table-sm table-striped myTableItemsInfo' style='width:100%'>
				
				 <thead class='bg-info'>
					<th>Type</th>
					<th>QTY</th>
				 </thead>
				 <tbody class='table-bordered'>
		";
	
		while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
		{
			
			echo "
				<tr>
					<td class='col-sm-4'>$resGetItemRef[type]</td>
					<td class='col-sm-1'>$resGetItemRef[typeqty]</td>
				</tr>
			
			";
		}
		
		echo "
		</tbody>
	</table>
		";
	}
}

else if($ItemTypeInfo == 'Stock')
{
	$sqlGetItemRef = "SELECT `descripcode`, `descripqty` FROM `stockoffers` WHERE `jobref` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

	if(mysqli_num_rows($queryGetItemRef) > 0)
	{
		echo "
		<table class='table table-sm table-striped myTableItemsInfo' style='width:100%'>
				
				 <thead class='bg-info'>
					<th>Part No.</th>
					<th>Name</th>
					<th>QTY</th>
				 </thead>
				 <tbody class='table-bordered'>
		";
	
		while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
		{
			$sqlGetItemDate = "SELECT `descriptionname`, `partnumber` FROM `stockitems` 
			WHERE `description` =$resGetItemRef[descripcode]";
			$queryGetItemDate = mysqli_query($link,$sqlGetItemDate)or die("ERROR :03-ANJ_GCN_S");
			$resGetItemDate = mysqli_fetch_assoc($queryGetItemDate);
			
			$descName = $resGetItemDate['descriptionname'];	
			$PartNum = $resGetItemDate['partnumber'];
			echo "
				<tr>
					<td>$PartNum</td>
					<td>$descName</td>
					<td>$resGetItemRef[descripqty]</td>
				</tr>
			
			";
		}
		
		echo "
		</tbody>
	</table>
		";
	}
}
?>		
 </div>     
  </div>
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" id="rowIdJobLoadAllItem"/>
  
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 
	  var table = $('.myTableItemsInfo').DataTable( {
	 
	  		 fixedHeader: false,
             //scrollY:'25vh',
			 //deferRender:true,
			 //scrollX: true,
        	 //scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: true ,
		  
 

});


	$('.showallHWMoadel').click(function () {
       
	   	$(".tooltip-inner").hide();
		$(".arrow").hide();
	
		var jobRowIDHWM =  $(this).val().split(',')[0];
		var itemRefHWM = $(this).val().split(',')[1];
		var itemRowIdHWM = $(this).val().split(',')[2];
		var jobTypeHWM = $(this).val().split(',')[3];
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/showAssignedHWModelExpt.php',
                type:'POST',
                data:{ModelJobRID:jobRowIDHWM, ModelItemHWRef:itemRefHWM, ModelItemRID:itemRowIdHWM,ModelJobType:jobTypeHWM},
                
				success: function(showHWData)
				{
				//alert(showHWData);
                $('.ShowData').html('');
                $('.ShowData').html(showHWData);
				//$(".myModal").modal('toggle');
				
				}         
        	}); 
	});
});
 
 </script>