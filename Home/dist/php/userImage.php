<?php
date_default_timezone_set("Africa/Cairo");
@session_start();

echo "
	<img src='dist/img/users/".$_SESSION['photo']."' class='img-circle elevation-2' alt='User Image'>
";	


?>