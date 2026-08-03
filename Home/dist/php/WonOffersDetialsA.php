<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowIdOI = $_POST['jobRId'];

$sqlGetItemRef = "SELECT  `id`, `doortype`, `doorspecs`, `motorspecs`, `doorprice`,
	 `doorqty`, `totalprice` FROM `autodoorsoffer` WHERE  `jobid` = $jobRowIdOI";
$queryGetItemRef = mysqli_query($link,$sqlGetItemRef)or die("ERROR :01-ANJ_GCN_S");

if(mysqli_num_rows($queryGetItemRef) > 0)
{
	echo "
	<table class='table table-sm table-striped myTableWonDoors' style='width:100%'>
        	
             <thead class='bg-info'>
             	<th>Type</th>
				<th>Door Specs</th>
                <th>Motor Specs</th>
                <th>QTY</th>
				<th>Price</th>
                <th>Total</th>
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
			<td class='col-sm-1'>".number_format($resGetItemRef['doorprice'])."</td>
			<td class='col-sm-2'>".number_format($resGetItemRef['totalprice'])."</td>
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
    </tfoot>
</table>
	";
}

?>		
      
      
 <input type="text" value="<?php echo $jobRowIdOI?>" style="display:none" class="rowIdJobLoadAllItem"/>
 
 <script type="text/javascript">
 $(document).ready(function() {
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	 

$(".tooltip-inner").hide();
$(".arrow").hide();
	
	  var table = $('.myTableWonDoors').DataTable( {
	 
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
            .column( 5 )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        pageTotal = api
            .column( 5, {page: 'current'} )
            .data()
            .reduce(function(a, b){
                return intval(a) + intval(b);
            }, 0 );
        $(api.column( 5 ).footer() ).html(
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");	
			
  		}

});
	
});
 
 </script>