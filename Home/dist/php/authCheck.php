<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
if (empty($_SESSION['Dept']) || !in_array($_SESSION['Dept'], ['Accountant', 'Manager'], true)) {
	http_response_code(403);
	die('Unauthorized');
}
