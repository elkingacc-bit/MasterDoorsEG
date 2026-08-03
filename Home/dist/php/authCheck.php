<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
if (empty($_SESSION['Dept']) || $_SESSION['Dept'] !== 'Accountant') {
	http_response_code(403);
	die('Unauthorized');
}
