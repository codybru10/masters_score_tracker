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
	case 'resetGame':
		admin::resetGame();
		break;
	default:
		admin::defaultRun();
		break;
}

class admin {

	function defaultRun() {
		$mysqli = admin::connectToDB();

		$sql = "SELECT * FROM game_players";
		$statement = $mysqli->prepare($sql);
		// $result = $statement->execute();
		$result = $mysqli->query($sql);

		$num_players = $result->affected_rows;
		error_log("NUM: ".$num_players);
		while ($row = $result->fetch_assoc()){
			error_log(print_r($row, true));
		}
		include "admin.html";
	}

	function addPlayer() {
		error_log('adding');
		error_log(print_r($_POST, true));
		$mysqli = admin::connectToDB();

		$sql = "INSERT INTO game_players (player_name)
				VALUES (?)";
		$statement = $mysqli->prepare($sql);
		$statement->bind_param(
			's',
			$_POST['player']
		);

		$result = $statement->execute();
	}

	function resetGame() {
		error_log('reset');
		$mysqli = admin::connectToDB();

		$sql = "DELETE FROM game_players";
		$statement = $mysqli->prepare($sql);

		$result = $statement->execute();
	}

	function connectToDB() {
		define('DB_HOST',"127.0.0.1:3306");
		define('DB_NAME',"leaderboard");
		define('DB_USER',"golf");
		define('DB_PASS',"Golf-champ-2020");

		$mysqli_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($mysqli_db -> connect_errno) {
			echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
			exit();
		}

		return $mysqli_db;
	}
}