<?php


	function db_connect() {
	
			// Define connection as a static variable, to avoid connecting more than once 
		static $link;
	
			// Try and connect to the database, if a connection has not been established yet
		if(!isset($link)) {
				 // Load configuration as an array. Use the actual location of your configuration file
			$config = parse_ini_file('AuthDB.ini'); 
			$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
		}
	
			// If connection was not successful, handle the error
		if($link === false) {
				// Handle error - notify administrator, log to a file, show an error screen, etc.
			return mysqli_connect_error(); 
		}
		return $link;
	}

// Connect to the database
		$link = db_connect();
		
		//mysqli_query($link, 'SET CHARACTER SET utf8');
		// Check connection
			if ($link->connect_error) {
				die("Connection failed: " . $link->connect_error);
			}

?> 

