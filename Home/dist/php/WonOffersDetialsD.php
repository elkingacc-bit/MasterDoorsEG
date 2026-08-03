<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['jobRId'];
$pageRef = $_POST['backRef'];

$sqlGetItemRef = "SELECT  `id`, `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`,`itemm2`, 
`msquerprice`, `shipping`, `installation`, `margin`, `itemqty`, `totalprice`,  `itemRef`, `FRMin`,
 `remarks`, `Overlap` FROM `itemoffer` WHERE  `jobref` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableWonDetails' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Item</th>
                <th>Hight</th>
                <th>Width</th>
				<th>Depth</th>
                <th>M<sup>2</sup></th>
                <th>Price</th>
                <th>QTY</th>
                <th><span data-toggle='tooltip' data-placement='left' title='Included Hardware'>Total
				</span></th>
				<th>F.R.Min</th>
				<th>Remarks</th>
				<th>Overlap</th>
				<th><span data-toggle='tooltip' data-placement='left' title='Hardware Group Ref'>HW</span></th>
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
			<td  class='col-sm-1'><span data-toggle='tooltip' data-placement='left' 
			data-html='true'title='Item Cost = 
			".round($resGetItemRef['totalprice']  / $resGetItemRef['itemqty'])."
			<br> HW = $itemHWPrice
			<br> Shipping = $resGetItemRef[shipping]
			<br> Installation = $resGetItemRef[installation]
			<br> Margin = ".($resGetItemRef['margin'] * 100)."%
			'>
			$resGetItemRef[msquerprice]</span></td>
			<td class='col-sm-1'>$resGetItemRef[itemqty]</td>
			<td class='col-sm-1'>".number_format(($totalItemPrice) , 1)."</td>
			<td class='col-sm-1'>$resGetItemRef[FRMin]</td>
			<td class='col-sm-1'>$resGetItemRef[remarks]</td>
			<td class='col-sm-1'>$resGetItemRef[Overlap]</td>
			<td class='col-sm-1'><span data-toggle='tooltip' data-placement='left' title='Show Assigned HW' >
			<button class='btn btn-link showallHWMoadel' value='$jobRowIdOI,$resGetItemRef[itemRef]
			,$resGetItemRef[id]'><b>$resGetItemRef[itemRef]</b>
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
		   <th></th>
    </tfoot>
</table>
	";
}

?>		
      
      
 <input type="text" value="<?php echo $pageRef?>" style="display:none" id="backRef"/>
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 
	
	$(".tooltip-inner").hide();
	$(".arrow").hide();
	
	  var table = $('.myTableWonDetails').DataTable( {
	 
	  		 fixedHeader: false,
             scrollY:'25vh',
			 deferRender:true,
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "desc"]],
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
            .column( 7 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 7, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 7 ).footer() ).html(pageTotal).css("color","red");
		 
        total = api
            .column( 8 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 8, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 8 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

});

	 $('.showallHWMoadel').click(function () {
       
		var jobRowIDHWM =  $(this).val().split(',')[0];
		var itemRefHWM = $(this).val().split(',')[1];
		var itemRowIdHWM = $(this).val().split(',')[2];
			//alert(jobRowIDHWM);	
           	$.ajax({
                url:'dist/php/showAssignedHWRPT.php',
                type:'POST',
                data:{ModelJobRID:jobRowIDHWM, ModelItemHWRef:itemRefHWM, ModelItemRID:itemRowIdHWM},
                
				success: function(showHWData)
				{
				//alert(showHWData);
                $('.ShowHWDataHist').html('');
                $('.ShowHWDataHist').html(showHWData);
				$(".myModal").modal('toggle');
				
				}         
        	}); 
	});

});
 
 </script>