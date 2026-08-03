<?php

date_default_timezone_set("Africa/Cairo");

include_once("connection.php");


$sqlCheckCustN="SELECT `customername`,`customercode` FROM `customers`
ORDER BY `customername` ASC";
$queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
if(mysqli_num_rows($queryCheckCustN) > 0)
{
	while($resCheckCustN=mysqli_fetch_assoc($queryCheckCustN))
	{
		echo " <option data-value='".$resCheckCustN['customercode']."' value='".$resCheckCustN['customername']."'>";
	}

 }
 
?>