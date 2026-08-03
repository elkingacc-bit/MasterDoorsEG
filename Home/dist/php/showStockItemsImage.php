<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $imageInfo = $_POST['imagePath'];
 $DescCode = $_POST['itemCode'];
 
 $sqlGetItem="SELECT `descriptionname`, `partnumber`, `technicalsheet` FROM `stockitems`
 WHERE `description` = $DescCode";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
$resGetItem = mysqli_fetch_assoc($queryGetItem);

	if($resGetItem['technicalsheet'] == NULL || $resGetItem['technicalsheet'] == "")
		{
			$techinaclData = "No data available";
		}
	else
		{
			$techinaclData = $resGetItem['technicalsheet'];
}

 }
 
 ?>
 <style>
.img-itemsStock {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 180px;
  height:180px;
  
}
h1 {font-size:14px; font-weight:bold;
}
</style>
<script>
    $(document).ready(function(){
        
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
 <div class="header">
<h4> <p> Part Number : <span style="color:blue">
 <?php echo $resGetItem['partnumber'];?></span>
 </span>
 &nbsp;
 Item Name : <span style="color:blue">
 <?php echo $resGetItem['descriptionname'];?></span>
 
<br>
<center>
 Technical Data : <span style="color:blue">
 <?php echo $techinaclData;?></span>
 </center>
 </p>
 </h4>
 </div>
 
 <div class="body">
<img data-enlargeable  alt='Item Image' src='dist/img/items/<?php echo $imageInfo;?>' 
class='img-thumbnail img-itemsStock'/>
<p>Click on image to open fullscreen size</p>
</div>