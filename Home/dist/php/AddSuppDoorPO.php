<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['jobRId'];

$sqlGetJobData = "SELECT `startDate`, `customer`, `salesman`, `description`, `offerValue` FROM `job` 
WHERE `jobId` = $jobRowIdOI";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_array($queryGetJobData);

$sqlGetCustName = "SELECT `customername` FROM `customers` WHERE `customercode` = $resultGetJobData[customer]";
$queryGetCustName = mysqli_query($link,$sqlGetCustName)or die("ERROR :02-ANJ_GCN_S");
$resultGetCustName = mysqli_fetch_array($queryGetCustName);

$sqlGetSalesName = "SELECT `username` FROM `users` WHERE `codeid` = $resultGetJobData[salesman]";
$queryGetSalesName = mysqli_query($link,$sqlGetSalesName)or die("ERROR :03-ANJ_GCN_S");
$resultGetSalesName = mysqli_fetch_array($queryGetSalesName);
?>
<input type="text" value="<?php echo $jobRowId?>" id="jRowId" style="display:none"/>		
<input type="text" value="<?php echo $resultGetJobData['customer']?>" id="CustCode" style="display:none"/>		
<input type="text" value="<?php echo $resultGetCustName['customername']?>" id="CustName" style="display:none"/>
<input type="text" value="<?php echo $resultGetSalesName['username']?>" id="SalesName" style="display:none"/>
<input type="text" value="<?php echo $resultGetJobData['offerValue']?>" id="TotalOffer" style="display:none"/>
 <input type="text" style="display:none;" class="itemNewRowId"/>
<input type="text" value="<?php echo date("d/m/Y",strtotime($resultGetJobData['startDate'])); ?>" 
id="SDate" style="display:none"/>		


<?php

$sqlGetItemRef = "SELECT  `id`, `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`,`itemm2`, 
`msquerprice`, `itemqty`, `totalprice`,  `itemRef`, `FRMin`, `remarks`, `Overlap` FROM `itemoffer` 
WHERE  `jobref` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableOldItems' style='width:100%'>
        	
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
				<th></th>
             </thead>
			 <tbody class='table-bordered'>
	";

while($resGetItemRef = mysqli_fetch_assoc($queryGetItemRef))
{
	
	echo "
		<tr>
			<td class='col-sm-2'>$resGetItemRef[itemtype]</td>
			<td class='col-sm-3'>$resGetItemRef[itemname]</td>
			<td class='col-sm-1'>$resGetItemRef[itemhight]</td>
			<td class='col-sm-1'>$resGetItemRef[itemwidth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemdepth]</td>
			<td class='col-sm-1'>$resGetItemRef[itemm2]</td>
			<td class='col-sm-1'>$resGetItemRef[itemqty]</td>
			<td class='col-sm-1'>$resGetItemRef[FRMin]</td>
			<td class='col-sm-1'>$resGetItemRef[remarks]</td>
			<td class='col-sm-1'>$resGetItemRef[Overlap]</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Show Assigned HW' >
			<button class='btn btn-link showallHWMoadel' value='$jobRowIdOI,$resGetItemRef[itemRef]
			,$resGetItemRef[id]'><b>$resGetItemRef[itemRef]</b>
			</button>
			</span></td>
			<td class='col-sm-0'><span data-toggle='tooltip' data-placement='left' title='Edit'>
			<button class='btn btn-link btn-xs addSuppOrder' value='$resGetItemRef[id]'>
					<i class='fa fa-cart-plus' aria-hidden='true' style='font-size:16px;color:#0275d8'>
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
           <th></th>
           <th></th>
           <th></th>
           <th></th>
           <th></th>
		   <th></th>
    </tfoot>
</table>
	";
}

?>		
      
      
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" id="rowIdJobLoadAllItem"/>
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 

$(".tooltip-inner").hide();
$(".arrow").hide();

    var titelCustN = $("#CustName").val();
	var titelSalesN = $("#SalesName").val();
	var titelSDate = $("#SDate").val();
	var titelTotalVal = $("#TotalOffer").val();
     $('.m-0').html('');
	 $('.m-0').html('Add Supplier Order For <br> <p style="font-size:12px; color:black; with:100%;">Customer | <span style="color:red">'+titelCustN+'</span> - Sales | <span style="color:red">'+titelSalesN+'</span> - Start Date |  <span style="color:red">'+titelSDate+'</span></p>');
   
	
$(".backBTN").click(function(){
	
	$("#5_1").click();
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	
	return false; 
	});
	

	  var table = $('.myTableOldItems').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
			 searching: true ,
		  
});


	
	 $('.addSuppOrder').click(function () {
       
		var itemRowId =  $(this).val();
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/assigningItemModel.php',
                type:'POST',
                data:{ModelItemRID:itemRowId},
                
				success: function(showSuppForm)
				{
				//alert(showHWData);
                $('.ShowHWData').html('');
                $('.ShowHWData').html(showSuppForm);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
	});

	
});
 
 </script>