<?php

ini_set('error_log', '/tmp/leaderboard.log');

$fnc = $_GET['event'];

$http_proto = 'http';
$base_url = $http_proto.'://'.$_SERVER['HTTP_HOST'];

define('BASE_URL', $base_url);
define('APP_DIR', getcwd());

switch ($fnc) {
	case 'addPlayer':
		admin::addPlayer();
		break;
	case 'resetGame':
		admin::resetGame();
		break;
	case 'addGolfer';
		admin::addGolfer();
		break;
	default:
		admin::defaultRun();
		break;
}

class admin {

	function defaultRun() {
		$mysqli = admin::connectToDB();

		$sql = "SELECT * FROM game_players";
		$result = $mysqli->query($sql);
		$num_players = $result->num_rows;

		// get list of golfers
		$golfers_html = admin::getGolfers();

		$i = 1;
		$players = '';
		while ($row = $result->fetch_assoc()){
			if ($i % 2 != 0) {
				$players .= '<div class="row">';
			}

			// get assigned golfers
			$sql2 = "SELECT * 
					FROM assigned_golfers
					WHERE player_id = ?";
			$statement = $mysqli->prepare($sql2);
			$statement->bind_param(
				'i',
				$row['player_id']
			);
	
			$statement->execute();
			$result2 = $statement->get_result();
			// $result = $mysqli->query($sql);
			$list = '<ul>';
			while ($golfer = $result2->fetch_assoc()) {
				error_log(print_r($golfer, true));
				$list .= '<li>'.$golfer['golfer_name'].'</li>';
			}
			$list .= '</ul>';

			$players .= '<div class="card col-sm">
				<div class="card-body">
					<h3>'.$row['player_name'].'</h3>
					'.$list.'
					<button type="button" class="btn-small player-val" data-toggle="modal" data-target="#exampleModal" value="'.$row['player_id'].'">Add Golfer</button>
				</div>
			</div>';

			if ($i % 2 == 0) {
				$players .= '</div>';
			}

			$i++;
		}

		if ($num_players % 2 != 0) {
			$players .= '</div>';
		}

		include "admin.html";
	}

	function getGolfers() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "http://site.api.espn.com/apis/site/v2/sports/golf/leaderboard",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS => "",
		CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"Cache-Control: no-cache",
			"Connection: keep-alive",
			"Content-Type: application/json",
			"Host: site.api.espn.com",
			"User-Agent: PostmanRuntime/7.11.0",
			"accept-encoding: gzip, deflate",
			"cache-control: no-cache",
			"cookie: SWID=5585BF87-D6BE-4E7F-CE7B-073F0CE0ADDE"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo $err;
		} else {
			$data = json_decode($response, true);
			$competitors = $data['events'][0]['competitions'][0]['competitors'];

			$golfers_html = "<select name='golfer-name' id='golfer-name'>";
			for ($i=0; $i < count($competitors); $i++) {
				// code...
				$name = $competitors[$i]['athlete']['displayName'];

				$golfers_html .= "<option value='".$name."'>".$name."</option>";

			}
			$golfers_html .= "</select>";
		}	


		return $golfers_html;
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

	function addGolfer() {
		error_log('addGolfer()');
		error_log(print_r($_POST, true));

		$mysqli = admin::connectToDB();

		$sql = "INSERT INTO assigned_golfers (golfer_name, player_id)
				VALUES (?, ?)";
		$statement = $mysqli->prepare($sql);
		$statement->bind_param(
			'si',
			$_POST['golfer'],
			$_POST['player_id']
		);

		$result = $statement->execute();
	}

	function connectToDB() {
		define('DB_HOST',"127.0.0.1:3306");
		define('DB_NAME',"leaderboard");
		define('DB_USER',"cody");
		define('DB_PASS',"foxhat24lady");

		$mysqli_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($mysqli_db -> connect_errno) {
			echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
			exit();
		}

		return $mysqli_db;
	}
}