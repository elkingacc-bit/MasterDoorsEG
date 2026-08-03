
<style>
.img-editItemTable {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 40px;
  height:40px;
  
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
		 

     });
	 
	$(".editSF").click(function(){
		
		var itemID = $(this).val();
			$(".showData").html("");
			$.ajax({
					url:"dist/php/editItemSFForm2.php",
					type:"POST",
					data:{itemRId:itemID},
					cache: false,
					success: function(showSalesFactForm){
						
						$(".myModal").modal('toggle');
						$('.ShowData').html(showSalesFactForm);
					}
				});
		
		return false;
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


	 
    });

</script>
 <table class="myTable table table-striped table-bordered" cellspacing="0" width="99%">
    <thead class="bg-warning">
          <th>Part No</th>
          <th>Item Name</th>
          <th>Description</th>
          <th>Sales Factor</th>
          <th>Over Cost</th>
          <th>Image</th>
          <th>Edit</th>
    </thead>
    <tbody>
            
<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");

$sqlGetItems="SELECT `itemsid`, `description`, `descriptionname`, `imagename`, `partnumber`, `technicalsheet`
, `salesfactor`, `overprice`FROM `stockitems` WHERE `description` IS NOT NULL AND `salesfactor` != 0 
GROUP BY `description`";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
while($resGetItems = mysqli_fetch_assoc($queryGetItems))

	{
	
		echo "
			<tr>
				<td>$resGetItems[partnumber]</td>
				<td>$resGetItems[descriptionname]</td>
				<td>$resGetItems[technicalsheet]</td>
				<td>".round($resGetItems['salesfactor'] * 100)."%</td>
				<td>$resGetItems[overprice]</td>
				<td><img data-enlargeable alt='Items Image'
				 src='dist/img/items/$resGetItems[imagename]'
				class='img-thumbnail img-editItemTable' style='cursor: zoom-in;'/></td>
              	<td>
					<button class='btn btn-xm btn-link editSF' value='$resGetItems[itemsid]'>
						<i class='fas fa-edit' aria-hidden='true' style='font-size:20px;color:#0275d8'></i>
					</button>
				</td>	
            </tr>
		
		
			";
	}

 ?>
    </tbody>  
 </table>


