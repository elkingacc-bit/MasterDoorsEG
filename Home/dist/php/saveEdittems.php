<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$itemRowID= $_POST['RowId'];

$partNo=$_POST['partNo'];
$itemName=$_POST['ItemName'];
$Manufactuer=$_POST['allManufactuer'];
//$Supplier=$_POST['allSupplier'];
$Description=$_POST['itemDesc'];
$imageName = str_replace(' ', '',$itemName)."_".date("dmY-hisa");


$sql="SELECT `descriptionname` FROM `stockitems` WHERE `descriptionname` = '$itemName' 
AND `itemsid` != $itemRowID";
$query=mysqli_query($link,$sql)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($query) > 0)
	{
		echo 0;
	}
else
{
	
$sqlGetDecription="SELECT `description` FROM `stockitems` WHERE `itemsid` = $itemRowID";
$queryGetDecription=mysqli_query($link,$sqlGetDecription)or die("ERROR :02-AU_AU_S");	
$resGetDecription=mysqli_fetch_assoc($queryGetDecription);
$descriptionCode = $resGetDecription['description'];




$respons = $_SESSION['username'];
$action="Edit Bisc Info for Item - $itemName for Code = $descriptionCode";
$logRef=3;

/*if($Supplier == "" || $Supplier == "N/A" )
	{
		$SupplierCode = 0;
	}
else
	{
	$sqlGetSupplier="SELECT  `suppliercode` FROM `allsuppliers` WHERE `suppliername` = '$Supplier'";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :04-AU_AU_S".mysqli_error($link));
	$resGetSupplier = mysqli_fetch_assoc($queryGetSupplier);
	$SupplierCode = $resGetSupplier['suppliercode'];	
	}
*/
if($Manufactuer == "N/A" || $Manufactuer == "")
	{
		$ManufCode = 0;
	}
	else
	{
	$sqlGetManuf="SELECT  `manufactuercode` FROM `allmanufactuers` WHERE `manufactuername` = '$Manufactuer'";
	$queryGetManuf=mysqli_query($link,$sqlGetManuf)or die("ERROR :05-AU_AU_S".mysqli_error($link));
	$resGetManuf = mysqli_fetch_assoc($queryGetManuf);
	$ManufCode = $resGetManuf['manufactuercode'];

	}

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
		
		$insertNewCode="UPDATE `stockitems` SET `descriptionname` = '$itemName', `imagename` = 
		'$filetoDB', `partnumber` = '$partNo', `technicalsheet` = '$Description', `manufacturing` = $ManufCode
		 WHERE `itemsid` = $itemRowID";
		mysqli_query($link,$insertNewCode)or die("ERROR :06-INC_AND_I".mysqli_error($link));
					 
		$sqlUpdateLookUp="UPDATE `lookupstock` SET `itemname` = '$itemName', `partno` = '$partNo',
		 `manufacture` = '$Manufactuer', `imagename` = '$filetoDB' 
		 WHERE `descriptioncode` = $descriptionCode";
		mysqli_query($link,$sqlUpdateLookUp)or die("ERROR :08-AU_AU_I");	 
			 
			 
		
		 
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
   }// if have image ''
   else
   {
		$insertNewCode="UPDATE `stockitems` SET `descriptionname` = '$itemName',`partnumber` = '$partNo',
		`technicalsheet` = '$Description', `manufacturing` = $ManufCode WHERE `itemsid` = $itemRowID";
		mysqli_query($link,$insertNewCode)or die("ERROR :08-INC_AND_I".mysqli_error($link));
			
		$sqlUpdateLookUp="UPDATE `lookupstock` SET `itemname` = '$itemName', `partno` = '$partNo',
		 `manufacture` = '$Manufactuer' WHERE `descriptioncode` = $descriptionCode";
		mysqli_query($link,$sqlUpdateLookUp)or die("ERROR :10-AU_AU_I");
	
			 
		  include_once("aduLog.php");
			echo 1;
			exit();
   }
}// if not duplicated name*/
?>
