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
  echo "cURL Error #:" . $err;
} else {
  $data = json_decode($response, true);
  echo $data['events'][0]['name'].'<br><br>';
  $competitors = $data['events'][0]['competitions'][0]['competitors'];
  // echo var_dump($competitors[0]['status']['thru']);
  $parsed_info = array();
  for ($i=0; $i < count($competitors); $i++) {
    // code...
    $name = $competitors[$i]['athlete']['displayName'];
    $thru = $competitors[$i]['status']['thru'];
    $score = $competitors[$i]['score']['displayValue'];
    $score = ($score == "E" ? 0 : $score);

    $player_info = array(
      "score" => $score,
      "name" => $name,
      "thru" => $thru
    );

    array_push($parsed_info, $player_info);

  }

  $ryan_results = array('RYAN');
  $cody_results = array('CODY');
  $tony_results = array('TONY');
  $drew_results = array('DREW');
  $jeremy_results = array('JEREMY');
  $matt_results = array('MATT');

  //scores
  $ryan_scores = array();
  $cody_scores = array();
  $tony_scores = array();
  $drew_scores = array();
  $jeremy_scores = array();
  $matt_scores = array();

  for ($i=0; $i < count($parsed_info); $i++) {
    // echo var_dump($parsed_info[$i]['name']).'<br>';
    switch (strtoupper($parsed_info[$i]['name'])) {
      case 'DUSTIN JOHNSON':
      case 'PATRICK REED':
      case 'BRYSON DECHAMBEAU':
      case 'BUBBA WATSON':
      case 'PHIL MICKELSON':
      case '':
        //drew
        array_push($drew_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($drew_scores, $parsed_info[$i]['score']);
        break;

      case 'BROOKS KOEPKA':
      case 'JUSTIN ROSE':
      case 'TOMMY FLEETWOOD':
      case 'WEBB SIMPSON':
      case 'KEITH MITCHELL':
      case '':
        //jeremy
        array_push($jeremy_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($jeremy_scores, $player['score']);
        break;

      case 'TIGER WOODS':
      case 'FRANCESCO MOLINARI':
      case 'MATT KUCHAR':
      case 'PATRICK CANTLAY':
      case 'KEVIN KISNER':
      case '':
        //tony
        array_push($tony_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($tony_scores, $player['score']);
        break;

      case 'RICKIE FOWLER':
      case 'JON RAHM':
      case 'ADAM SCOTT':
      case 'IAN POULTER':
      case 'LOUIS OOSTHUIZEN':
      case '':
        //cody
        array_push($cody_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($cody_scores, $player['score']);
        break;

      case 'RORY MCILROY':
      case 'TONY FINAU':
      case 'SERGIO GARCIA':
      case 'HIDEKI MATSUYAMA':
      case 'KEEGAN BRADLEY':
      case '':
        //ryan
        array_push($ryan_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($ryan_scores, $player['score']);
        break;

      case 'JASON DAY':
      case 'XANDER SCHAUFFELE':
      case 'JORDAN SPIETH':
      case 'GARY WOODLAND':
      case 'PAUL CASEY':
      case '':
        //matt
        array_push($matt_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($matt_scores, $player['score']);
        break;
    }
  }

  printResults($drew_results, $drew_scores);
  printResults($jeremy_results, $drew_scores);
  printResults($tony_results, $tony_scores);
  printResults($cody_results, $cody_scores);
  printResults($ryan_results, $ryan_scores);
  printResults($matt_results, $drew_scores);

  echo '<br>LEADERBOARD<br>';
  for ($i=0; $i < count($parsed_info); $i++) {
    echo $competitors[$i]['score']['displayValue']." ".$competitors[$i]['athlete']['displayName']." thru ".$competitors[$i]['status']['thru']."<br>";
  }

}

function printResults($results, $scores) {
  foreach($results as $line) {
    echo $line."</br>";
  }

  $top = $scores[0] + $scores[1] + $scores[2];
  echo 'Top 3 Lowest Total: '.$top;
  echo "</br>";
  echo "</br>";
}

