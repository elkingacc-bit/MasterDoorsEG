<?php
date_default_timezone_set("Africa/Cairo");

include_once("connection.php");

$sqlCheckCustAvtivity="SELECT `activity` FROM `customers` GROUP BY `activity` ASC ";
$queryCheckCustAvtivity=mysqli_query($link,$sqlCheckCustAvtivity)or die("ERROR :01-CCA_SACA_S");
 
 if(mysqli_num_rows($queryCheckCustAvtivity) > 0)
 {	
	while($resCustAvtivity=mysqli_fetch_assoc($queryCheckCustAvtivity))
	{
		echo "<option value='".$resCustAvtivity['activity']."'>";
	}
 }

?>
