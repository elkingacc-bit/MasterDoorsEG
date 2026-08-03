<?php
date_default_timezone_set("Africa/Cairo");
@session_start();

echo "
	<span>".$_SESSION['fname']."</span><br>
	<center><span>".$_SESSION['Dept']."</span></center>
";	


?>