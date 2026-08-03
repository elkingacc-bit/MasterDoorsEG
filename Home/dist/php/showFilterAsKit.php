<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$KitName = $_POST['AsKitName'];
$KitRowId = $_POST['AsKitRID'];

?>
<style>
.img-FilterAsKit {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 50px;
  
}
h1 {font-size:14px; font-weight:bold;
}
</style>

<script type="text/javascript">
$(document).ready(function() {

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

		
var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
			
 var table = $('.myTableFilter').DataTable( {
	 
	  fixedHeader: false,
             scrollY:'35vh',
			 scrollX: true,
        	 scrollCollapse: true,
        	 paging: false,	
			 order:[[0, "asc"]], 
		 
 dom: 'Bfrtip',
       buttons: [
	   
	   {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
			title:'All_Items_Member_of<?php echo "_". $KitName;?> '+datetime,
			filename: function () {
			return "All_Items_Member_of<?php echo "_". $KitName;?>" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Items_Member_of<?php echo "_". $KitName;?> '+datetime,
			 filename: function () {
			return "All_Items_Member_of<?php echo "_".$KitName;?>" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3,4,5,6]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Company Managment System {Master Doors EG} | All Items Member of- "<?php echo $KitName;?> '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3,4,5,6]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
	}
 ],			 

     });
	 
$('img[data-enlargeable]').addClass('img-enlargeable').click(function() {
  var src = $(this).attr('src');
  var modal;

  function removeModal() {
    modal.remove();
    $('body').off('keyup.modal-close');
  }
  modal = $('<div>').css({
    background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center',
    backgroundSize: 'contain',
    width: '100%',
    height: '100%',
    position: 'fixed',
    zIndex: '10000',
    top: '0',
    left: '0',
    cursor: 'zoom-out'
  }).click(function() {
    removeModal();
  }).appendTo('body');
  //handling ESC
  $('body').on('keyup.modal-close', function(e) {
    if (e.key === 'Escape') {
      removeModal();
    }
  });
});	

 
 });// doc ready...******...

</script>
<h5>Items Member of&nbsp;Assembly Kit:&nbsp;<span style="color:blue"><?php echo $KitName;?></span></h5>
 <table class="myTableFilter table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Part No</th>
          <th>Item Name</th>
          <th data-toggle='tooltip' data-placement='right' title='Requier Quantity'>R_QTY</th>
          <th data-toggle='tooltip' data-placement='right' title='warehouse'>Stock</th>
          <th>Image</th>
    </thead>
    <tbody>
            
<?php

$sqlGetItems="SELECT `descripcode`, `Quantity` FROM `kitscomponents` WHERE `assemplyRowId` = $KitRowId";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
while($resGetItems = mysqli_fetch_assoc($queryGetItems))
	{
		
	$sqlGetItemData="SELECT `partno`,`itemname`,`warehouse`, `imagename` 
	FROM `lookupstock` WHERE `descriptioncode` = $resGetItems[descripcode] LIMIT 1";
	$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
	$resGetItemData = mysqli_fetch_assoc($queryGetItemData);
		
		echo "
			<tr>
				<td>$resGetItemData[partno]</td>
				<td>$resGetItemData[itemname]</td>
				<td>$resGetItems[Quantity]</td>
				<td>$resGetItemData[warehouse]</td>
				<td><img data-enlargeable alt='Item Image'
				 src='dist/img/items/$resGetItemData[imagename]'
				class='img-thumbnail img-FilterAsKit' style='cursor: zoom-in;'/></td>
            </tr>
		
		
			";
	}

 ?>
    </tbody>  
 </table>


