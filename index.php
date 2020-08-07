<?php

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

  // connect to mysql to get players
  define('DB_HOST',"127.0.0.1:3306");
  define('DB_NAME',"leaderboard");
  define('DB_USER',"cody");
  define('DB_PASS',"foxhat24lady");

  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }
  
  // $sql = "SELECT * FROM game_players";
	// $result = $mysqli->query($sql);
  // $num_players = $result->num_rows;
  
  // $c = 1;
  // $new_html = '';
  // while ($row = $result->fetch_assoc()) {
  //   if ($c % 2 != 0) {
  //     $new_html .= '<div class="row">';
  //   }

  //   $new_html .= '<div class="card col-sm"><div class="card-body">'.$row['player_name'].'</div></div>';

  //   if ($c % 2 == 0) {
  //     $new_html .= '</div>';
  //   }
  //   $c++;
  // }

  // if ($num_players % 2 != 0) {
  //   $new_html .= '</div>';
  // }


  $sql = "SELECT * FROM assigned_golfers";
  $result = $mysqli->query($sql);

  $assigned_golfers = array();
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $assigned_golfers[$golfer_name] = $player_id;
  }

  error_log("aaaaaaaaaaa: ".print_r($assigned_golfers, true));

  $data = json_decode($response, true);
  $tourney = $data['events'][0]['name'].'<br><br>';
  $competitors = $data['events'][0]['competitions'][0]['competitors'];
  $parsed_info = array();
  $leaderboard_info = array();
  for ($i=0; $i < count($competitors); $i++) {
    // code...
    $name = $competitors[$i]['athlete']['displayName'];
    $thru = $competitors[$i]['status']['thru'];
    $score = $competitors[$i]['statistics'][0]['value'];

    $player_info = array(
      "score" => $score,
      "name" => $name,
      "thru" => $thru
    );

    foreach($assigned_golfers as $golfer => $player_id) {
      if ($golfer == $name) {
        $parsed_info[$player_id][$golfer] = $player_info;
      }
    }

    array_push($leaderboard_info, $player_info);

  }

  error_log(print_r($parsed_info, true));
  $i = 1;
  $new_html = '';
  foreach($parsed_info as $id => $golfers) {
    $sql = "SELECT * 
            FROM game_players
            WHERE player_id = ?";
    $statement = $mysqli->prepare($sql);
    $statement->bind_param(
      'i',
      $id
    );
    $statement->execute();
    $result = $statement->get_result();
    $player = $result->fetch_assoc();

    if ($i % 2 != 0) {
      $new_html .= '<div class="row">';
    }

    $new_html .= '<div class="card col-sm"><div class="card-body"><h4>'.$player['player_name'].'</h4>';

    usort($golfers, function($a, $b) {
      return $a['score'] <=> $b['score'];
    });

    $top_three = $golfers[0]['score']+$golfers[1]['score']+$golfers[2]['score'];
    
    foreach($golfers as $golfer) {
      $new_html .= '<p>'.$golfer['score'].' '.$golfer['name'].' Thru '.$golfer['thru'].'</p>';
    }

    $new_html .= '<h4>Top Three: '.$top_three.'</h4>';
    
    $new_html .= '</div></div>';

    if ($i % 2 == 0) {
      $new_html .= '</div>';
    }

    $i++;
  }

  if (count($parsed_info) % 2 != 0) {
    $new_html .= '</div>';
  }


  $leaderboard = '';
  for ($i=0; $i < count($leaderboard_info); $i++) {
    $leaderboard .= '<tr>
        <td>'.$competitors[$i]['athlete']['displayName'].'</td>
        <td>'.$competitors[$i]['statistics'][0]['value'].'</td>
        <td>'.$competitors[$i]['status']['thru'].'</td>
      </tr>';
  }

  include "temp.html";

}