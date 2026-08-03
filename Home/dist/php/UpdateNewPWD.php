 <?php
@session_start();
//echo "test". $_SESSION['id'];
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$userName=$_POST['UserN'];
$usps=$_POST['CPWD'];
include_once("hashfunc.php");
$NewuspsH=$hashed_password;

	$sqlChangePass="UPDATE `users` SET `password` = '$NewuspsH', `validation` = 1 WHERE `userid` = 
	$_SESSION[id]";
	mysqli_query($link,$sqlChangePass)or die("ERROR :01-CP_UUPWD_U");
	
$action="$userName Frist Login";
$logRef=1;
include_once("aduLog.php");
echo 1;
exit();

?>