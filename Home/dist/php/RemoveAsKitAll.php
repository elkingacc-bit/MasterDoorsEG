<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
//echo "test--> ";
$asKitRowId = $_POST['RmvAsKitRowID'];

$sqlGetAsKitName="SELECT `kitName` FROM `assemblykits` WHERE `id` = $asKitRowId";
$queryGetAsKitName=mysqli_query($link,$sqlGetAsKitName)or die("ERROR :01-AU_AU_S");
$resGetAsKitsName = mysqli_fetch_assoc($queryGetAsKitName);

$AsKitName = $resGetAsKitsName['kitName'];

$sqlRemoveKitCompnt = "DELETE FROM `kitscomponents` WHERE `assemplyRowId` = $asKitRowId";
mysqli_query($link,$sqlRemoveKitCompnt)or die("ERROR :02-ANJ_GCN_S");

$sqlRemoveKitName = "DELETE FROM `assemblykits` WHERE `id` = $asKitRowId";
mysqli_query($link,$sqlRemoveKitName)or die("ERROR :03-ANJ_GCN_S");
	
	$action="Deleted Assembly Kit : $AsKitName and all assigned components";
$logRef=5;	
include_once("aduLog.php");
	
	echo 1;


?>