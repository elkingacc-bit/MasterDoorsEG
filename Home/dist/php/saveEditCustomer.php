<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
$RowID=$_POST['CRowId'];
$CustName=$_POST['CustomerN'];
$CustArea=$_POST['CustAreaN'];
$CustActivity=$_POST['CustActivityN'];
$CustCode=$_POST['OldCode'];

$sqlCheckNewCust="SELECT `customername` FROM `customers` WHERE `customername` = '$CustName' AND `customersid` 
!= $RowID";
$queryCheckCust=mysqli_query($link,$sqlCheckNewCust)or die("ERROR :01-CNS_ANS_S");

	if(mysqli_num_rows($queryCheckCust) > 0)
	{
		echo 0;
	}

	else
	{
		 
		$sqlUpdateNewCode="UPDATE `customers` SET `customername` = '$CustName', `activity` = '$CustActivity',
		`area` = '$CustArea' WHERE `customersid` = $RowID ";
		mysqli_query($link,$sqlUpdateNewCode)or die("ERROR :03-INC_ANS_I".mysqli_error($link));
		
		$sqlUpdateAllCode="UPDATE `allcode` SET `codename` = '$CustName' WHERE `code` = $CustCode";
		mysqli_query($link,$sqlUpdateAllCode)or die("ERROR :04-INC_ANS_I".mysqli_error($link));
		
		
		$action="Edit Customer old code $CustCode to - $CustName";
		$logRef=2;
		include_once("aduLog.php");
		echo 1;
		exit();
	}
}//session
else
{
	echo 9;
	exit();	
}

?>