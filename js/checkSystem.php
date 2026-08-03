<?php 
date_default_timezone_set("Africa/Cairo");
$url = "C:/xampp/htdocs/Maintenance_Tracker/Home/sysLicense.lic";

if(file_exists($url))
{
	echo 1;
}
else
{
	echo 0;
}
	exit();


?>