<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$ref = $_POST['ref'];
$postCode = $_POST['changedCode'];

if($ref == 1)
{
	$column = 'categoryname';
	$Condation = 'category';
	$head = 'Group name: ';
}

else if($ref == 2)
{
	$column = 'subcategoryname';
	$Condation = 'subcategory';
	$head = 'Sub Group name: ';
}
else if($ref == 3)
{
	$column = 'subSCatgName';
	$Condation = 'subSCatg';
	$head = 'Sub Sub Group name: ';
}



$sqlGetCodeName = "SELECT `$column` FROM `stockitems` WHERE `$Condation` = $postCode";
$queryGetCodeName=mysqli_query($link,$sqlGetCodeName)or die("ERROR :01-AU_AU_S");
$resGetCodeName = mysqli_fetch_assoc($queryGetCodeName);

$codeName = $resGetCodeName[$column];

?>
<style>
.img-Filter {
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
			title:'All_Items_Member_of<?php echo $head ."_". $codeName;?> '+datetime,
			filename: function () {
			return "All_Items_Member_of<?php echo $head ."_". $codeName;?>" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2,3]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All_Items_Member_of<?php echo $head ."_". $codeName;?> '+datetime,
			 filename: function () {
			return "All_Items_Member_of<?php echo $head . "_".$codeName;?>" },
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
	  title:'Company Managment System {Master Doors EG} | All Items Member of<?php echo $head . "  - ".$codeName;?> '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2,3]
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
<p>Items Member of&nbsp;<?php echo $head;?>&nbsp;<span style="color:blue; font-weight:bold">
<?php echo $codeName;?></span></p>
 <table class="myTableFilter table table-sm table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Part No</th>
          <th>Item Name</th>
          <th>Description</th>
          <th data-toggle='tooltip' data-placement='right' title='warehouse'>Stock</th>
          <th>Image</th>
    </thead>
    <tbody>
            
<?php

$sqlGetItems="SELECT `itemsid`, `description`, `descriptionname`, `imagename`, `technicalsheet`
FROM `stockitems` WHERE `description` IS NOT NULL AND `description` LIKE('$postCode%') GROUP BY `description`";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
while($resGetItems = mysqli_fetch_assoc($queryGetItems))
	{
		
	$sqlGetItemData="SELECT `partno`,`warehouse` FROM `lookupstock` WHERE `descriptioncode` 
	= $resGetItems[description] LIMIT 1";
	$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :02-AU_AU_S");
	$resGetItemData = mysqli_fetch_assoc($queryGetItemData);
		
		echo "
			<tr>
				<td>$resGetItemData[partno]</td>
				<td>$resGetItems[descriptionname]</td>
				<td>$resGetItems[technicalsheet]</td>
				<td>$resGetItemData[warehouse]</td>
				<td><img data-enlargeable alt='Item Image'
				 src='dist/img/items/$resGetItems[imagename]'
				class='img-thumbnail img-Filter' style='cursor: zoom-in;'/></td>
            </tr>
		
		
			";
	}

 ?>
    </tbody>  
 </table>


