<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $NameAndImage = $_POST['nameAndImageVal'];
 $imageInfo = substr($NameAndImage, strrpos($NameAndImage, ',') + 1);
 $imageInfo = trim($imageInfo);
 $DescCode = substr($NameAndImage, 0, strpos($NameAndImage, ','));
 $DescCode = trim($DescCode);
 
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
 <div class="header">
 <p> Part Number : <span style="color:blue">
 <?php echo $resGetItem['partnumber'];?></span>
 </span>
 &nbsp;
 Item Name : <span style="color:blue">
 <?php echo $resGetItem['descriptionname'];?></span>
 
 &nbsp;
 Technical Data : <span style="color:blue">
 <?php echo $techinaclData;?></span>
 </p>
 </div>
 
 <div class="body">
<img data-enlargeable alt='Line Image' src='dist/img/items/<?php echo $imageInfo;?>' 
class='img-thumbnail img-itemsMintRpt'/>
</div>