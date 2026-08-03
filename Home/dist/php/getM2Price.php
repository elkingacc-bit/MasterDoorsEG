<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

if(!empty($_SESSION['username']))
{

$typeSelected = $_POST['sType'];

$sqlGetM2Price = "SELECT `priceval` FROM `pricing` WHERE `pricetype` = '$typeSelected'";
$queryGetM2Price = mysqli_query($link,$sqlGetM2Price)or die("ERROR :01-CNS_ANS_S"); 
$resGetM2Price = mysqli_fetch_assoc($queryGetM2Price);

echo $resGetM2Price['priceval'];

}
else
{
	echo 9;
	exit();
}
?>