<?php
session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");


$usnm=$_POST['user'];
$usps=$_POST['pass'];

	$sqlCkeckUs="SELECT `userid`, `codeid`,`fullname`, `username`, `password`, `department`, `userType` 
	, `validation`, `empphoto` FROM `users` WHERE `username` = '$usnm' ORDER BY `userid` ASC LIMIT 1";
	$queryCheckUs=mysqli_query($link,$sqlCkeckUs)or die("ERROR :01-SCU_CLI_S".error_get_last());
	if(mysqli_num_rows($queryCheckUs) == 0)
	{
		echo 0;
	}
	else
	{
	$resCheckUs=mysqli_fetch_assoc($queryCheckUs);
	$uspsH=$resCheckUs['password'];
	if($resCheckUs['username'] == NULL)
	{
		echo 0;
	}
	else if($resCheckUs['validation'] == 6)
	{
		echo 2;
	}
	else if($resCheckUs['username'] == $usnm && password_verify($usps, $uspsH))
	{
		/*$sqlCkeckPs="SELECT validation FROM users WHERE username = '$usnm'";
		$queryCheckPs=mysqli_query($link,$sqlCkeckPs)or die("ERROR :02-CVC_CLI_U");
		$resCheckPs=mysqli_fetch_assoc($queryCheckPs);*/
		if( $resCheckUs['validation'] == 0)
				{
					
					  $_SESSION['username'] = $resCheckUs['username'];
					  $_SESSION['id']= $resCheckUs['userid'];
					  $_SESSION['uType']=$resCheckUs['userType'];
					  $_SESSION['Dept']=$resCheckUs['department'];
					  $_SESSION['code']=$resCheckUs['codeid'];
					  $_SESSION['fname']=$resCheckUs['fullname'];
					  $_SESSION['photo']=$resCheckUs['empphoto'];
					echo 3;
				}
		else if($resCheckUs['validation'] == NULL)
		{
			
			if($resCheckUs['validation'] == 1)
				{
					$validNum=2;
				}
			else if($resCheckUs['validation'] == 2)
				{
					$validNum=3;
				}
			else if($resCheckUs['validation'] == 3)
				{
					$validNum=4;
				}
			else if($resCheckUs['validation'] == 4)
				{
					$validNum=5;
				}
			else if($resCheckUs['validation'] == 5)
				{
					$validNum=6;
				}
				$ChangeValidCode="UPDATE `users` SET `validation` = $validNum WHERE `userid` =
				 $resCheckUs[userid]";
				mysqli_query($link,$ChangeValidCode)or die("ERROR :02-CVC_CLI_U").error_get_last();
				echo 1;
		}
		
		else if($resCheckUs['validation'] != NULL)
		{
			 
			 
			 if($resCheckUs['validation'] == 0)
			{
				
					  $_SESSION['username'] = $resCheckUs['username'];
					  $_SESSION['id']= $resCheckUs['userid'];
					  $_SESSION['uType']=$resCheckUs['userType'];
					  $_SESSION['Dept']=$resCheckUs['department'];
					  $_SESSION['code']=$resCheckUs['codeid'];
					  $_SESSION['fname']=$resCheckUs['fullname'];
					  $_SESSION['photo']=$resCheckUs['empphoto'];
				echo 3;
			}
			else
			{
				$ChangeValidCode="UPDATE `users` SET `validation` = 1, ref = 1 WHERE `userid` =
				 $resCheckUs[userid]";
				mysqli_query($link,$ChangeValidCode)or die("ERROR :02-CVC_CLI_U").error_get_last();
				
				
					  $_SESSION['username'] = $resCheckUs['username'];
					  $_SESSION['id']= $resCheckUs['userid'];
					  $_SESSION['uType']=$resCheckUs['userType'];
					  $_SESSION['Dept']=$resCheckUs['department'];
					  $_SESSION['code']=$resCheckUs['codeid'];
					  $_SESSION['fname']=$resCheckUs['fullname'];
					  $_SESSION['photo']=$resCheckUs['empphoto'];
				echo 4;
			}
		}
		else
		{
			echo "Un-Expected Error!!";
		}
	}
	}
?>