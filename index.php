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
  $data = json_decode($response, true);
  $tourney = $data['events'][0]['name'].'<br><br>';
  $competitors = $data['events'][0]['competitions'][0]['competitors'];
  $parsed_info = array();
  for ($i=0; $i < count($competitors); $i++) {
    // code...
    $name = $competitors[$i]['athlete']['displayName'];
    $thru = $competitors[$i]['status']['thru'];
    $score = $competitors[$i]['statistics'][0]['value'];
    // $score = ($score == "E" ? 0 : $score);

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
    switch (strtoupper($parsed_info[$i]['name'])) {
      case 'JON RAHM':
      case 'PATRICK CANTLAY':
      case 'HIDEKI MATSUYAMA':
      case 'MATT KUCHAR':
      case 'IAN POULTER':
      case 'DANIEL BERGER':
        //drew
        array_push($drew_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($drew_scores, $parsed_info[$i]['score']);
        break;

      case 'DUSTIN JOHNSON':
      case 'BROOKS KOEPKA':
      case 'COLLIN MORIKAWA':
      case 'TONY FINAU':
      case 'PAUL CASEY':
      case 'LOUIS OOSTHUIZEN':
        //jeremy
        array_push($jeremy_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($jeremy_scores, $parsed_info[$i]['score']);
        break;

      case 'BRYSON DECHAMBEAU':
      case 'WEBB SIMPSON':
      case 'ADAM SCOTT':
      case 'TYRRELL HATTON':
      case 'MATTHEW FITZPATRICK':
      case 'SCOTTIE SCHEFFLER':
        //tony
        array_push($tony_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($tony_scores, $parsed_info[$i]['score']);
        break;

      case 'XANDER SCHAUFFELE':
      case 'RICKIE FOWLER':
      case 'MARC LEISHMAN':
      case 'ABRAHAM ANCER':
      case 'SHANE LOWRY':
      case 'VIKTOR HOVLAND':
        //cody
        array_push($cody_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($cody_scores, $parsed_info[$i]['score']);
        break;

      case 'RORY MCILROY':
      case 'PATRICK REED':
      case 'SUNGJAE IM':
      case 'BILLY HORSCHEL':
      case 'SERGIO GARCIA':
      case 'MAX HOMA':
        //ryan
        array_push($ryan_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($ryan_scores, $parsed_info[$i]['score']);
        break;

      case 'JUSTIN THOMAS':
      case 'TOMMY FLEETWOOD':
      case 'GARY WOODLAND':
      case 'JUSTIN ROSE':
      case 'JORDAN SPIETH':
      case 'HARRIS ENGLISH':
        //matt
        array_push($matt_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($matt_scores, $parsed_info[$i]['score']);
        break;
    }
  }

  $drew_html = printResults($drew_results, $drew_scores);
  $jeremy_html = printResults($jeremy_results, $jeremy_scores);
  $tony_html = printResults($tony_results, $tony_scores);
  $cody_html = printResults($cody_results, $cody_scores);
  $ryan_html = printResults($ryan_results, $ryan_scores);
  $matt_html = printResults($matt_results, $matt_scores);

  $leaderboard = '';
  for ($i=0; $i < count($parsed_info); $i++) {
    $leaderboard .= '<tr>
        <td>'.$competitors[$i]['athlete']['displayName'].'</td>
        <td>'.$competitors[$i]['statistics'][0]['value'].'</td>
        <td>'.$competitors[$i]['status']['thru'].'</td>
      </tr>';
  }

  include "temp.html";

}

function printResults($results, $scores) {

  $html = '<div class="card col-sm"><div class="card-body">';

  foreach($results as $line) {
    $html .= '<p>'.$line.'</p>';
  }

  sort($scores);

  $top = $scores[0] + $scores[1] + $scores[2];
  $html .= '<h4>Top 3 Lowest Total: '.$top.'</h4>';

  $html .= '</div></div>';
  return $html;
}
