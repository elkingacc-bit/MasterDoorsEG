<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$jobRowId = $_POST['TotalJobRID'];

$sqlGetJobData = "SELECT `offerValue` FROM `job` WHERE `jobId` = $jobRowId";
$queryGetJobData = mysqli_query($link,$sqlGetJobData)or die("ERROR :01-ANJ_GCN_S");
$resultGetJobData = mysqli_fetch_array($queryGetJobData);

echo number_format($resultGetJobData['offerValue']);

?>