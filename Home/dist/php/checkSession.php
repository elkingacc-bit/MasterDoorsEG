<?php 
@session_start();
include_once("connection.php");

if(empty($_SESSION['id']))
	{
		echo 1;
	}

?>