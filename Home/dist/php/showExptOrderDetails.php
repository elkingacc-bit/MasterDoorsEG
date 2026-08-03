<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
	  
 $orderRowId = $_POST['PORIDExpt'];
 $offeredItemCode = $_POST['itemCodeExpt']; 
		 
?>
<!doctype html>
<html>

 <div class="modal-header">
      <h5 class="modal-title" align="center">
      <center>
      	Exported Items Details
        </center>
      </h5>
      </div>
       <div class="modal-body ">
       
       <div class="ExptQTYDetails" style=" width:100%">
       	
        <table class="table table-sm myTableExptDatiels table-striped table-bordered" cellspacing="0" 
    	style="width:90%" >
        	
            <thead class="bg-info">
            
            	<th>No.</th>
                <th>Part No</th>
                <th>Item</th>
                <th>QTY</th>
                
            </thead>
            <tbody >
            	<?php
				
				$ser = 1;
					
					$sqlGetExptStock = "SELECT `description` , `export` FROM `warehouse` WHERE `poIdRef`
					= $orderRowId AND `description` = $offeredItemCode AND `export` != 0";
					$queryGetExptStock=mysqli_query($link,$sqlGetExptStock)or 
					die("ERROR :01-AM_AMDL_S".mysqli_error($link));
					while($resGetExptStock= mysqli_fetch_assoc($queryGetExptStock))
					{
					$sqlGetItem = "SELECT `descriptionname` , `partnumber` FROM `stockitems` WHERE 
					`description` = $offeredItemCode";
					$queryGetItem=mysqli_query($link,$sqlGetItem)or 
					die("ERROR :02-AM_AMDL_S".mysqli_error($link));
					$resGetItem= mysqli_fetch_assoc($queryGetItem);
					
					echo "
						<tr class='bg-warning' data-toggle='tooltip' data-placement='top' title='Offered'>
							<td class='col-sm-1'>$ser</td>
							<td class='col-sm-2'>$resGetItem[partnumber]</td>
							<td class='col-sm-3'>$resGetItem[descriptionname]</td>
							<td class='col-sm-2'>$resGetExptStock[export]</td>
						
						</tr>
					
					";
						$ser++;
					}
					
					$sqlGetRExptStock = "SELECT `descriptionRCode` , `exptqty` FROM `replacedexpt` 
					WHERE `porefrowid` = $orderRowId AND `offereditemcode` = $offeredItemCode";
					$queryGetRExptStock=mysqli_query($link,$sqlGetRExptStock)or 
					die("ERROR :03-AM_AMDL_S".mysqli_error($link));
					while($resGetRExptStock= mysqli_fetch_assoc($queryGetRExptStock))
					{
					$sqlGetItem2 = "SELECT `descriptionname` , `partnumber` FROM `stockitems` WHERE 
					`description` = $resGetRExptStock[descriptionRCode]";
					$queryGetItem2=mysqli_query($link,$sqlGetItem2)or 
					die("ERROR :04-AM_AMDL_S".mysqli_error($link));
					$resGetItem2= mysqli_fetch_assoc($queryGetItem2);
					
					echo "
						<tr data-toggle='tooltip' data-placement='bottom' title='Replaced'>
							<td class='col-sm-1'>$ser</td>
							<td class='col-sm-2'>$resGetItem2[partnumber]</td>
							<td class='col-sm-3'>$resGetItem2[descriptionname]</td>
							<td class='col-sm-1'>$resGetRExptStock[exptqty]</td>
						 
						</tr>
					
					";
					
						$ser++;
					}
				
				
				?>
            </tbody>
            <tfoot>
            
            	<th></th>
                <th></th>
                <td align="right"><b>Total</b></td>
                <th></th>
                
            </tfoot>
            
        </table>
        
       </div>
       
 
  </div>
</html>
<?php
 }
 ?>
 
 <script type="text/javascript">
 
 	$(document).ready(function() {
		
		 $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	
        
		  var table = $('.myTableExptDatiels').DataTable( {
	 
	  		fixedHeader: false,
             //scrollY:'35vh',
			 //scrollX: true,
        	 //scrollCollapse: true,
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

		
    });
 
 
 </script>