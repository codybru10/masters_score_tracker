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


  $haney_results = array('HANEY');
  $sammy_results = array('SAMMY');
  $tony_results = array('TONY');
  $johnny_results = array('JOHNNY');
  $jordan_results = array('JORDAN');
  $matt_results = array('MATT');

  //scores
  $haney_scores = array();
  $sammy_scores = array();
  $tony_scores = array();
  $johnny_scores = array();
  $jordan_scores = array();
  $matt_scores = array();

  for ($i=0; $i < count($parsed_info); $i++) {
    switch (strtoupper($parsed_info[$i]['name'])) {
      case 'BRYSON DECHAMBEAU':
      case 'HIDEKI MATSUYAMA':
      case 'DANIEL BERGER':
      case 'PATRICK REED':
      case 'ADAM HADWIN':
      case 'KEEGAN BRADLEY':
        //johnny
        array_push($johnny_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($johnny_scores, $parsed_info[$i]['score']);
        break;

      case 'JON RAHM':
      case 'PATRICK CANTLAY':
      case 'ABRAHAM ANCER':
      case 'JASON DAY':
      case 'MATTHEW FITZPATRICK':
      case 'KEVIN STREELMAN':
        //jordan
        array_push($jordan_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($jordan_scores, $parsed_info[$i]['score']);
        break;

      case 'JUSTIN THOMAS':
      case 'WEBB SIMPSON':
      case 'GARY WOODLAND':
      case 'TONY FINAU':
      case 'BILLY HORSCHEL':
      case 'SERGIO GARCIA':
        //tony
        array_push($tony_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($tony_scores, $parsed_info[$i]['score']);
        break;

      case 'RORY MCILROY':
      case 'XANDER SCHAUFFELE':
      case 'SUNGJAE IM':
      case 'IAN POULTER':
      case 'VIKTOR HOVLAND':
      case 'JOAQUIN NIEMANN':
        //sammy
        array_push($sammy_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($sammy_scores, $parsed_info[$i]['score']);
        break;

      case 'COLLIN MORIKAWA':
      case 'DUSTIN JOHNSON':
      case 'JUSTIN ROSE':
      case 'MATT KUCHAR':
      case 'PAUL CASEY':
      case 'KEVIN KISNER':
        //haney
        array_push($haney_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($haney_scores, $parsed_info[$i]['score']);
        break;

      case 'TIGER WOODS':
      case 'BROOKS KOEPKA':
      case 'RICKIE FOWLER':
      case 'JORDAN SPIETH':
      case 'SHANE LOWRY':
      case 'COREY CONNERS':
        //matt
        array_push($matt_results, $parsed_info[$i]['score'].' '.$parsed_info[$i]['name'].' Thru '.$parsed_info[$i]['thru']);
        array_push($matt_scores, $parsed_info[$i]['score']);
        break;
    }
  }

  $johnny_html = printResults($johnny_results, $johnny_scores);
  $jordan_html = printResults($jordan_results, $jordan_scores);
  $tony_html = printResults($tony_results, $tony_scores);
  $sammy_html = printResults($sammy_results, $sammy_scores);
  $haney_html = printResults($haney_results, $haney_scores);
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
