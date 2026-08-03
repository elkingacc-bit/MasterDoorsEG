<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$partNo=$_POST['partNo'];
$itemName=$_POST['ItemName'];
$Description=$_POST['ItemDesc'];

$subSubCatgN = 16111111;
$respons = $_SESSION['username'];



if($_FILES['sourceFile']['name'] == "")
{
	$checkImage = 0;
}
else
{
	$checkImage = 1;
}

if($Description == "")
{
	$Description = "No any Technical Data added ";
}



$action="Add New Item - $itemName";
$logRef=2;


$sql="SELECT `descriptionname` FROM `stockitems` WHERE `descriptionname` = '$itemName'";
$query=mysqli_query($link,$sql)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($query) > 0)
{
	echo 0;
}
else
{
	
	
	$getNewCode="SELECT `description` FROM `stockitems` WHERE `description` LIKE('$subSubCatgN%') 
	ORDER BY `description` DESC LIMIT 1";
	$queryNewCode=mysqli_query($link,$getNewCode)or die("ERROR :02-CND_AND_S");
	
	if(mysqli_num_rows($queryNewCode) == 0)
	{
		$new=10001;
		$DescCode=($subSubCatgN.$new);
	}
	else
	{
		$resNewCode=mysqli_fetch_assoc($queryNewCode);
		$DescCode=($resNewCode['description']+1);
	}	

	
if($partNo == "")
{
	$sqlGetPartNum="SELECT `partnumber` FROM `stockitems` WHERE `partnumber` LIKE 'CMS_%' 
	ORDER BY LPAD(`partnumber`, 20, '0') DESC LIMIT 1 ";
	$queryGetPartNum=mysqli_query($link,$sqlGetPartNum)or die("ERROR :01-CPN_PNFD_S");
	if(mysqli_num_rows($queryGetPartNum) == 0)
	{
		$newPartNumber="CMS_101";
	}
	else
	{
	$resGetPartNo=mysqli_fetch_assoc($queryGetPartNum);
	$crntPartNumber=substr($resGetPartNo['partnumber'], strrpos($resGetPartNo['partnumber'], '_') + 1);
	$lastPartNo=($crntPartNumber+1);
	$newPartNumber="CMS_".$lastPartNo;
	}
}
else
{
	$newPartNumber = $partNo;
}
	
	
	if($checkImage == 1)
	{

	$allowed = array('png', 'jpg','jpeg');
	$filenameVald = $_FILES['sourceFile']['name'];
	$ext = strtolower(pathinfo($filenameVald, PATHINFO_EXTENSION));
	if (!in_array($ext, $allowed)) 
	{
		echo 2;
	}
	else
	{ 
							
				
	$maxDim = 800;
	 $file_name = $_FILES['sourceFile']['tmp_name'];
	list($width, $height, $type, $attr) = getimagesize( $file_name );
		if ( $width > $maxDim || $height > $maxDim ) {
			$target_filename = $file_name;
			$ratio = $width/$height;
			if( $ratio > 1) 
			{
			$new_width = $maxDim;
			$new_height = $maxDim/$ratio;
			} 
			else 
			{
			$new_width = $maxDim*$ratio;
			$new_height = $maxDim;
			}
			$src = imagecreatefromstring( file_get_contents( $file_name ) );
			$dst = imagecreatetruecolor( $new_width, $new_height );
			imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			imagedestroy( $src );
			imagepng( $dst, $target_filename ); // adjust format as needed
			imagedestroy( $dst );
		}
	
		$target_dir = "../img/items/";
		$target_file = $target_dir . basename($imageName.".".$ext);
		
		$filetoDB = $imageName.".".$ext;
		
		$insertNewCode="INSERT INTO `stockitems` (`descriptionname`, `description`, `imagename`, `partnumber`
	   , `location`, `technicalsheet`) VALUES ('$itemName', $DescCode, '$filetoDB', '$newPartNumber',
	    'Wearhouse','$Description')";
		mysqli_query($link,$insertNewCode)or die("ERROR :03-INC_AND_I".mysqli_error($link));
		
		
			//$sqlAddItemWH="INSERT INTO `warehouse`(`description`, `date`,`income`,  `responsible`,
			// whref) VALUES ($DescCode, NOW(), 0, '$respons', 1)";
			// mysqli_query($link,$sqlAddItemWH)or die("ERROR :02-AU_AU_I");
		 
		 	 $sqlAddItemLU="INSERT INTO `lookupstock`(`descriptioncode`, `itemname`, `partno`, 
			 `warehouse`,  `manufacture`, `supplier`,  `imagename`, 
			 `lastupdate`, `ref`) VALUES ($DescCode, '$itemName', '$newPartNumber', 0,
			  'N/A', 'N/A', '$filetoDB', NOW(), 1)";
			 mysqli_query($link,$sqlAddItemLU)or die("ERROR :02-AU_AU_I");
		
		
		
		 
		if (move_uploaded_file($_FILES['sourceFile']['tmp_name'], $target_file)) 
		{

		 include_once("aduLog.php");
			echo 1;
			exit();
					
		} 
		else 
		{
			echo "Sorry, there was an error uploading your file.";
		}	
	}	//if choose valid image  		
   }// if have image
   else
   {
	   $insertNewCode="INSERT INTO `stockitems` (`descriptionname`, `description`, `imagename`,  `partnumber`
	   , `location`,`technicalsheet`) VALUES ('$itemName', $DescCode, 'defaultItem.jpg', '$newPartNumber',
	    'Wearhouse', '$Description')";
		mysqli_query($link,$insertNewCode)or die("ERROR :03-INC_AND_I".mysqli_error($link));
		
		
			//$sqlAddItemWH="INSERT INTO `warehouse`(`description`, `date`,`income`,  `partnumber`, 
			//`location`, `responsible`, whref) VALUES ($DescCode, NOW(), 0, '$newPartNumber',
			 //'Wearhouse', '$respons', 1)";
			 //mysqli_query($link,$sqlAddItemWH)or die("ERROR :02-AU_AU_I");
		 
		 	 $sqlAddItemLU="INSERT INTO `lookupstock`(`descriptioncode`, `itemname`, `partno`, 
			 `warehouse`,`manufacture`, `supplier`,  `imagename`, 
			 `lastupdate`, `ref`) VALUES ($DescCode, '$itemName', '$newPartNumber', 0,
			  'N/A', 'N/A', 'defaultItem.jpg', NOW(), 1)";
			 mysqli_query($link,$sqlAddItemLU)or die("ERROR :02-AU_AU_I");
		
		  include_once("aduLog.php");
			echo 1;
			exit();
   }
}// if not duplicated name*/
?>
