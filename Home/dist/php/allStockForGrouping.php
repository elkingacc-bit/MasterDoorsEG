<style>
.img-Grop {
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
		
var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
			
         var table = $('.myTable').DataTable( {
	 
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
			title:'All_Items_Info '+datetime,
			filename: function () {
			return "All_Items_Info" },
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2]
            },
			footer: false,
			
		},
		
		{
            extend: 'pdf',
            text: 'PDF',
			title:'All Items Info '+datetime,
			 filename: function () {
			return "All_Items_Info" },
            extension: '.pdf',
			className: 'btn btn-secondary',
            exportOptions: {
				
              columns: [  0,1,2]
            },
			footer: false,
			
		},
		
	{
	  extend: 'print',
	  text: 'Print',
	  className: 'btn btn-secondary',
	  title:'Maintenance Tracker System {Kandil Glass Manufacturing} | All Items Info '+datetime,
	  footer: true,
	   exportOptions: {
		   
                   columns: [ 0,1,2]
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

	$(".assignGroup").click(function(){
		
	var itemID = $(this).val();
		$.ajax({
                url:'dist/php/assignGroupForm.php',
                type:'POST',
                data:{idGrouping:itemID},
                //dataType:'html',
				success: function(showMyForm)
				{
				
                $('.ShowData').html('');
                $('.ShowData').html(showMyForm);
				$(".myModal").modal('toggle');
				}          
        		});
		
		return false;
		});




	 
 });// doc ready...******...

</script>
 <table class="myTable table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Part No</th>
          <th>Item Name</th>
          <th>Description</th>
          <th>Image</th>
          <th></th>
    </thead>
    <tbody>
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");

$sqlGetItems="SELECT `itemsid`, `description`, `descriptionname`, `imagename`, `partnumber`, `technicalsheet`
FROM `stockitems` WHERE `description` IS NOT NULL AND `description` LIKE('1611%') GROUP BY `description`";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
while($resGetItems = mysqli_fetch_assoc($queryGetItems))
	{
		
		echo "
			<tr>
				<td>$resGetItems[partnumber]</td>
				<td>$resGetItems[descriptionname]</td>
				<td>$resGetItems[technicalsheet]</td>
				<td><img data-enlargeable alt='Machine Image'
				 src='dist/img/items/$resGetItems[imagename]'
				class='img-thumbnail img-Grop' style='cursor: zoom-in;'/></td>
              	<td>
					<button class='btn btn-xs btn-link assignGroup' value='$resGetItems[itemsid]'>
					<i class='far fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'>
					</i>
					</button>
				</td>	
            </tr>
		
		
			";
	}

 ?>
    </tbody>  
 </table>


