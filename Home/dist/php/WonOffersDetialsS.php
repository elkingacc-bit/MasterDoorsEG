
<table class="table myTableWonItems"  style="width:100%">
   
      <thead class="bg-dark">
       	<th>Part No.</th>
        <th>Name</th>
        <th>QTY</th>
        <th>Price</th>
        <th>Total</th>
      </thead>
      <tbody>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['jobRId'];

$sqlGetHW = "SELECT `id`, `descripcode`, `descripqty`, `descripprice`, `totalprice`, `jobref`, `ref` FROM
 `stockoffers` WHERE `jobref` = $jobRowId";
$queryGetHW = mysqli_query($link,$sqlGetHW)or die("ERROR :02-ANJ_GCN_S");
while($resGetHW = mysqli_fetch_assoc($queryGetHW))
{
	$sqlGetItemDate = "SELECT `descriptionname`, `partnumber` FROM `stockitems` WHERE `description` =$resGetHW[descripcode]";
	$queryGetItemDate = mysqli_query($link,$sqlGetItemDate)or die("ERROR :03-ANJ_GCN_S");
	$resGetItemDate = mysqli_fetch_assoc($queryGetItemDate);
	
	$descName = $resGetItemDate['descriptionname'];
	
	$PartNum = $resGetItemDate['partnumber'];
	
	echo "
		<tr>
			<td>$PartNum</td>
			<td>$descName</td>
			<td>$resGetHW[descripqty]</td>
			<td>".number_format($resGetHW['descripprice'])."</td>
			<td>".number_format($resGetHW['totalprice'])."</td>
		</tr>
	
	";
}



?>		
      </tbody>
      <tfoot class="bg-light">
       	<th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tfoot>
 </table>
 
 <script type="text/javascript">
 $(document).ready(function() {
   
   $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	

$(".tooltip-inner").hide();
$(".arrow").hide();
	 
	  var table2 = $('.myTableWonItems').DataTable( {
	 
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