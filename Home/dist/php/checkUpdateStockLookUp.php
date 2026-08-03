<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$date = date("Y-m-d");

	$sqlCheckLookUpTable=" SELECT * FROM `lookupstock`";
	$queryCheckLookUpTable=mysqli_query($link,$sqlCheckLookUpTable)or die("ERROR :00-CLU_CULU_S".
	mysqli_error($link));
if(mysqli_num_rows($queryCheckLookUpTable) == 0)
{
	echo 0;
}
else
{
	
	$sqlCheckLookUp=" SELECT `lastupdate` FROM `lookupstock` WHERE `lastupdate` != '$date'";
	$queryCheckLookUp=mysqli_query($link,$sqlCheckLookUp)or die("ERROR :01-CLU_CULU_S".mysqli_error($link));
	
	if(mysqli_num_rows($queryCheckLookUp) > 0)
	{
		
		echo 0;
	}
	else
	{
	$sqlCheckLookUp=" SELECT `lastupdate` FROM `lookupstock` WHERE `ref` = 0";
	$queryCheckLookUp=mysqli_query($link,$sqlCheckLookUp)or die("ERROR :01-CLU_CULU_S".mysqli_error($link));
	
		if(mysqli_num_rows($queryCheckLookUp) > 0)
		{
			
			echo 0;
		}
		else
		{
			echo 1;
		}
	
	}
}
	
	
}
else
{
	echo 9;
}


?>