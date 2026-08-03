<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$rptRef = $_POST['rptRef'];
$rptDate = $_POST['rptDate'];

if($rptRef == 1)
{
	$Titel = 'User Log Report';	
	$filename = 'User_Log_Report';	
}
else if($rptRef == 2)
{
	$Titel = 'Added Log Report';
	$filename =	'Added_Log_Report';
}
else if($rptRef == 3)
{
	$Titel = 'Edited Log Report';	
	$filename =	'Edited_Log_Report';
}
else if($rptRef == 7)
{
	$Titel = 'Jobs Log Report';	
	$filename =	'Jobs_Log_Report';
}
else if($rptRef == 6)
{
	$Titel = 'Grouping & Organize Log Report';
	$filename =	'Grouping_Organize_Log_Report';	
}
else if($rptRef == 8)
{
	$Titel = 'Assembly Kit Log Report';	
	$filename =	'Assembly_Kit_Log_Report';
}
else if($rptRef == 5)
{
	$Titel = 'Offers and PO Log Report';	
	$filename =	'Offers_and_PO_Log_Report';
}
else if($rptRef == 12)
{
	$Titel = 'Assembly Kit Log Report';	
	$filename =	'Assembly_Kit_Log_Report';
}
else if($rptRef == 9)
{
	$Titel = 'Supplier Industry Orders Log Report';	
	$filename =	'Supplier_Indusrty_Log_Report';
}
else if($rptRef == 10)
{
	$Titel = 'Installation Log Report';	
	$filename =	'Installation_Log_Report';
}
?>
<style>
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
			
 var table = $('.myTableLog').DataTable( {
	 
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
			title:'<?php echo $filename?> '+datetime,
			filename: function () {
			return "<?php echo $filename?>" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'<?php echo $filename?> '+datetime,
			 filename: function () {
			return "<?php echo $filename?>" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Maintenance Tracker System {Kandil Glass Manufacturing} |<?php echo $Titel?> '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.2; filter: alpha(opacity=15); width: 450px; height:200px" />');
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
<h5>MTS History Stps for &nbsp;<span style="color:blue"><?php echo $Titel?></span></h5>
 <table class="myTableLog table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>No.</th>
          <th>Action</th>
          <th>Date</th>
          <th>User</th>
    </thead>
    <tbody>
            
<?php
$serl = 1;
$sqlGetLogData="SELECT `action`, `dateTime`, `responsibility` FROM `logreport` WHERE `logRef` = $rptRef
AND `dateTime` BETWEEN '$rptDate' AND NOW()";
$queryGetLogData=mysqli_query($link,$sqlGetLogData)or die("ERROR :01-AU_AU_S");
while($resGetLogData = mysqli_fetch_assoc($queryGetLogData))
	{
				
		echo "
			<tr>
				<td>$serl</td>
				<td>$resGetLogData[action]</td>
				<td>$resGetLogData[dateTime]</td>
				<td>$resGetLogData[responsibility]</td>
            </tr>

			";
$serl++;
	}

 ?>
    </tbody>  
 </table>


