 <!-- DataTables
 Canceled
 
  -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<style>
tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
.table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #D6D5D5; 
}

</style>
<table id='myTable' class='table table-striped table-bordered' cellspacing='0' width='100%'>
<thead style='color:#4A4AC5; width:100%'>
						
						
						<th scope='col'>Local ID</th>
						<th scope='col'>Description</th>
						<th scope='col'>Stock</th>
						<th scope='col' style='width:15px'>Quantity</th>
	
					</thead>
					<tbody style='font-weight:bolder;'>
<?php	
date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
	
		$sqlShowDescripSI="SELECT `warehouse`.`description`, `descriptionname`, `warehouse`.`partnumber`, SUM(`income` - `export`) 
		AS Stock FROM `stockitems`, `warehouse` WHERE `warehouse`.`description` = `stockitems`.`description` GROUP BY
		 `warehouse`.`description`";
		$queryShowDescripSI=mysqli_query($link,$sqlShowDescripSI)or die("ERROR :03-SGSI_SASTD_S".mysqli_error($link));
	 while($resultShowDescripSI=mysqli_fetch_assoc($queryShowDescripSI))
	{
		if($resultShowDescripSI['Stock'] != 0 || $resultShowDescripSI['Stock'] > 0)
		{
			
$sqlGetStockInNotfi=" SELECT SUM(`notifQuanty`) AS StNotif FROM `cms_notifications` WHERE `descriptionCode` =
$resultShowDescripSI[description] AND `notifRef` IS NULL";
$queryGetStockInNotfi=mysqli_query($link,$sqlGetStockInNotfi)or die("ERROR :01_3-GCS_IWHDA_S"
.mysqli_error($link));
$resGetStockInNotfi=mysqli_fetch_assoc($queryGetStockInNotfi);
			
			if($resGetStockInNotfi['StNotif'] == NULL || mysqli_num_rows($queryGetStockInNotfi) == 0)
			{
				$stockRequested = 0;
			}
			else
			{
				$stockRequested=$resGetStockInNotfi['StNotif'];
			}
			
			$avilbilStock=($resultShowDescripSI['Stock'] - $resGetStockInNotfi['StNotif']);
			
			if($avilbilStock < 0)
			{
				$avilbilStock = 0;
			}
				$inputCaes="<input style='width:70px' type='number' class='form-control rqQunit'
				 name='rqQunit[]' min='1' max='$avilbilStock'/>";
			echo "						
					<tr >					
						<td>$resultShowDescripSI[partnumber] </td>
						<td>$resultShowDescripSI[descriptionname] </td>
						<td>$avilbilStock</td>
						<td style='width:15px'>$inputCaes</td>
						<input type='text' style='display:none' class='form-check-input selectedRow'
						  value='$resultShowDescripSI[description]' name='WHItemIdRequested[]'/>
					</tr>
				 ";
		}
	}

?>
	</tbody>
    <tfoot style='color:#4A4AC5; width:100%'>					
		<th>Part No.</th>
		<th>Description</th>
		<th>Stock</th>
		<th style='width:15px'>Quantity</th>
	</tfoot>
</table>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
 

<script type="text/javascript">
 $(document).ready(function(){  
 
var table = $("#myTable").DataTable({
		 
		 fixedHeader: false,
             scrollY:'35vh',
			 scrollX: false,
        	 scrollCollapse: true,
        	 paging: false,
			 fixedColumns:false,
			 autoWidth:false,
			"searching": true,
			 
		 });
		 					
					
	$(".rqQunit").blur(function(){
			
			setTimeout(function(){
			$('#myTable').DataTable().search('').draw();
			 }, 200);
			});				
 });

</script>
