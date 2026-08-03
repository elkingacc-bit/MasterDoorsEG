<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");
if(!empty($_SESSION['username']))
{
	$sqlGetAllTeams="SELECT `id`, `teamname` FROM `teams` GROUP BY `teamname` ASC";
	$queryGetAllTeams=mysqli_query($link,$sqlGetAllTeams)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	if(mysqli_num_rows($queryGetAllTeams) > 0)
	{
		while($resGetAllTeams= mysqli_fetch_assoc($queryGetAllTeams))
		{
		
			
			echo "<option data-value='$resGetAllTeams[id]' value='$resGetAllTeams[teamname]'>";
		}
	}
}
else
{
	echo 9;
}
?>
