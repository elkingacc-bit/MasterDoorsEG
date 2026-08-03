<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$SubGroupCodeSelected = $_POST['gSelected'];
 
	$sqlGetSSGroup="SELECT `subSCatgName`, `subSCatg`  FROM `stockitems` WHERE 
	`subSCatg` LIKE('$SubGroupCodeSelected%') ORDER BY `subSCatgName` ASC";
	$queryGetSSGroup=mysqli_query($link,$sqlGetSSGroup)or die("ERROR :01-AU_AU_S");
	while($resGetSSGroup = mysqli_fetch_assoc($queryGetSSGroup))
	{
		echo "<option data-value='$resGetSSGroup[subSCatg]' value='$resGetSSGroup[subSCatgName]'>";
	}
?>

