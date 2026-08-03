<?php
date_default_timezone_set("Africa/Cairo");
//session_start();
include_once("connection.php");
$usps = $_POST['UsPass'];
include_once("hashfunc.php");

$fullname=$_POST['flName'];
$username=$_POST['UsName'];
$password=$hashed_password;
$department=$_POST['UsDedt'];
$action="Add New User- $fullname";
$logRef=1;
if($_FILES['sourceFile']['name'] == "")
{
	$checkImage = 0;
}
else
{
	$checkImage = 1;
}

$sql="SELECT `username` FROM `users` WHERE `username` = '$username'";
$query=mysqli_query($link,$sql)or die("ERROR :01-AU_AU_S");
if(mysqli_num_rows($query) > 0)
{
	echo 0;
}
else
{
	
	
	$contToCode="SELECT `code` FROM `allcode` WHERE `subcategoryname` = '$department' AND LENGTH(`code`) = 7 
	ORDER BY `code`	DESC LIMIT 1";
			$queryContCode=mysqli_query($link,$contToCode)or die("ERROR :02-CTC_AU_S");
			

			if(mysqli_num_rows($queryContCode) > 0)
			{
				$resContCode=mysqli_fetch_assoc($queryContCode);
				$CategCode=($resContCode['code']);
				
				$sqlGetUserCode = "SELECT `codeid` FROM `users` WHERE `codeid` LIKE('$CategCode%') ORDER BY 
				`codeid` DESC LIMIT 1";
				$queryGetUserCode=mysqli_query($link,$sqlGetUserCode)or die("ERROR :03-GC_AU_S");
				if(mysqli_num_rows($queryGetUserCode) > 0)
				{
					$resGetUserCode=mysqli_fetch_assoc($queryGetUserCode);
					
					$newCode=($resGetUserCode['codeid'] + 1);
				}
				else
				{
					$getCode="SELECT `code` FROM `allcode` WHERE `subcategoryname` = '$department'";
					$queryGetCode=mysqli_query($link,$getCode)or die("ERROR :03-GC_AU_S");
					$resGetCode=mysqli_fetch_assoc($queryGetCode);
					$BasicCode=$resGetCode['code'];
					$UserID=11;
					$newCode=$BasicCode.$UserID;
				}
			}
			
if($checkImage == 1)
{			

	$allowed = array('png', 'jpg','jpeg');
	$filenameVald = $_FILES['sourceFile']['name'];
	$ext = strtolower(pathinfo($filenameVald, PATHINFO_EXTENSION));
	if (!in_array($ext, $allowed)) 
	{
		echo 'Error it is look like not allowed image estension!!';
	}
	else
	{ 
							
				
	$maxDim = 600;
	 $file_name = $_FILES['sourceFile']['tmp_name'];
	list($width, $height, $type, $attr) = getimagesize( $file_name );
		if ( $width > $maxDim || $height > $maxDim ) {
			$target_filename = $file_name;
			$ratio = $width/$height;
			if( $ratio > 1) {
				$new_width = 160;
				$new_height = 160;
			} else {
				$new_width = 160;
				$new_height = 160;
			}
			$src = imagecreatefromstring( file_get_contents( $file_name ) );
			$dst = imagecreatetruecolor( $new_width, $new_height );
			imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			imagedestroy( $src );
			imagepng( $dst, $target_filename ); // adjust format as needed
			imagedestroy( $dst );
		}
	
	
	
		$target_dir = "../img/users/";
		$target_file = $target_dir . basename($newCode.".".$ext);
		
		$filetoDB = $newCode.".".$ext;
		
		$sqlAU="INSERT INTO `users` (`codeid`, `fullname`, `nikename`, `email`, `username`, `password`, 
		`department`, `usertype`, `empphoto`, `empphone`, `validation`, `ref`) VALUES ($newCode, '$fullname',
		 'N/A', 'N/A', '$username', '$password', '$department', '$department','$filetoDB','N/A', 0 , 1)";
		 mysqli_query($link,$sqlAU)or die("ERROR :04-AU_AU_I");
		 
		if (move_uploaded_file($_FILES['sourceFile']['tmp_name'], $target_file)) 
		{
//echo "The file ". htmlspecialchars( basename( $_FILES["sourceFile"]["name"])). " has been uploaded.";  
		 include_once("aduLog.php");
			echo 1;
			exit();
					
		} 
		else 
		{
			echo "Sorry, there was an error uploading your file.";
		}	
	}
	
}
else
{
	$filetoDB = 'adefault.jpg';
		$sqlAU="INSERT INTO `users` (`codeid`, `fullname`, `nikename`, `email`, `username`, `password`, 
		`department`, `usertype`, `empphoto`, `empphone`, `validation`, `ref`) VALUES ($newCode, '$fullname',
		 'N/A', 'N/A', '$username', '$password', '$department', '$department','$filetoDB','N/A', 0 , 1)";
		 mysqli_query($link,$sqlAU)or die("ERROR :04-AU_AU_I");
		 
		  include_once("aduLog.php");
			echo 1;
			exit();
}

}
?>
