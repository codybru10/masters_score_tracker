<?php

ini_set('error_log', '/tmp/leaderboard.log');

$fnc = $_GET['event'];
error_log(print_r($_GET, true));
$http_proto = 'http';
$base_url = $http_proto.'://'.$_SERVER['HTTP_HOST'];

define('BASE_URL', $base_url);
define('APP_DIR', getcwd());
error_log('running file');
switch ($fnc) {
	case 'addPlayer':
		admin::addPlayer();
		break;
	
	default:
		admin::defaultRun();
		break;
}

class admin {

	function defaultRun() {
	
		include "admin.html";

	}

	function addPlayer() {
		error_log('adding');
		error_log(print_r($_POST, true));
		admin::connectToDB();
	}


	function connectToDB() {
		define('DB_HOST',"127.0.0.1:3306");
		define('DB_NAME',"leaderboard");
		define('DB_USER',"root");
		define('DB_PASS',"N2D{ZbXyrPWv");
		error_log('CONNECTING');
		$mysqli_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($mysqli -> connect_errno) {
			echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
			exit();
		}
		error_log('SUCCESS');
		return $mysqli_db;
	}
}