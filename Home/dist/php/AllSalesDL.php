<?php
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

	$getAllSales="SELECT `fullname`, `codeId` FROM `users` WHERE `department` = 'Sales' ORDER BY 
	`fullname` ASC ";
	$queryAllSales=mysqli_query($link,$getAllSales)or die("ERROR :01-AIC_AIDL_S");
	while($resAllSales=mysqli_fetch_assoc($queryAllSales))
	{
		echo "
				<option data-value='$resAllSales[codeId]' 
				value='$resAllSales[fullname]' >
			";
	}


?>
