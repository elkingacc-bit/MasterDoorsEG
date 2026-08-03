<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {

 $PoRId = $_POST['PoIdTable'];
 $Date = $_POST['chooseDate'];
 
 $sqlCheckPlan="SELECT `id` FROM `installplan` WHERE `dateplan` = '$Date' AND `poId` = $PoRId";
$queryCheckPlan=mysqli_query($link,$sqlCheckPlan)or die("ERROR :01-AU_AU_S");
	
	if(mysqli_num_rows($queryCheckPlan) > 0 )
	{
		echo 0;
	}
	else
	{
		$sqlAddPlan = "INSERT INTO `installplan`(`dateplan`, `poId`) VALUES('$Date', $PoRId)";
		mysqli_query($link,$sqlAddPlan)or die("ERROR :03-AU_AU_S");
		
		$action="Add New Plan For Data : $Date";
		$logRef=10;
		include_once("aduLog.php");
			echo 1;
			exit();

		
	}

 }
 else
 {
	 echo 9;
	 exit();
 }
 
 
 ?>
