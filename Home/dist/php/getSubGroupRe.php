<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$GroupCodeSelected = $_POST['gSelected'];
 
	$sqlGetSGroup="SELECT `subcategoryname`, `subcategory` FROM `stockitems` WHERE 
	`subcategory` LIKE('$GroupCodeSelected%') ORDER BY `subcategoryname` ASC";
	$queryGetSGroup=mysqli_query($link,$sqlGetSGroup)or die("ERROR :01-AU_AU_S");
	while($resGetSGroup = mysqli_fetch_assoc($queryGetSGroup))
	{
		echo "<option data-value='$resGetSGroup[subcategory]' value='$resGetSGroup[subcategoryname]'>";
	}
?>