<?php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://golf.jacoduplessis.co.za/?format=json",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: 4c0ee4cd-f47d-4768-bdf8-d8a578d1cf53",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $data = json_decode($response, true);
  // $players = $data['Leaderboards'][0]['Players'];

  foreach($data['Leaderboards'] as $tournament) {
    if ($tournament['Tour'] == 'PGA Tour') {
      $pga = $tournament;
      break;
    }
    continue;
  }

  // error_log(print_r($pga, true));
  echo $pga['Tournament'];
  echo "</br>";
  echo 'Updated: '.$pga['Updated']."</br>";
  echo "</br>";
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

  foreach($pga['Players'] as $player) {
    // error_log($player['Name']);

    switch (strtoupper($player['Name'])) {
      case 'DUSTIN JOHNSON':
      case 'PATRICK REED':
      case 'BRYSON DECHAMBEAU':
      case 'BUBBA WATSON':
      case 'PHIL MICKELSON':
      case '':
        //drew
        array_push($drew_results, $player['Total'].' '.$player['Name'].' Thru '.$player['After']);
        array_push($drew_scores, $player['Total']);
        break;

      case 'BROOKS KOEPKA':
      case 'JUSTIN ROSE':
      case 'TOMMY FLEETWOOD':
      case 'WEBB SIMPSON':
      case 'KEITH MITCHELL':
      case '':
        //jeremy
        array_push($jeremy_results, $player['Total'].' '.$player['Name'].' Thru '.$player['After']);
        array_push($jeremy_scores, $player['Total']);
        break;

      case 'TIGER WOODS':
      case 'FRANCESCO MOLINARI':
      case 'MATT KUCHAR':
      case 'PATRICK CANTLAY':
      case 'KEVIN KISNER':
      case '':
        //tony
        array_push($tony_results, $player['Total'].' '.$player['Name'].' Thru '.$player['After']);
        array_push($tony_scores, $player['Total']);
        break;

      case 'RICKIE FOWLER':
      case 'JON RAHM':
      case 'ADAM SCOTT':
      case 'IAN POULTER':
      case 'LOUIS OOSTHUIZEN':
      case '':
        //cody
        array_push($cody_results, $player['Total'].' '.$player['Name'].' Thru '.$player['After']);
        array_push($cody_scores, $player['Total']);
        break;

      case 'RORY MCILROY':
      case 'TONY FINAU':
      case 'SERGIO GARCIA':
      case 'HIDEKI MATSUYAMA':
      case 'KEEGAN BRADLEY':
      case '':
        //ryan
        array_push($ryan_results, $player['Total'].' '.$player['Name'].' Thru '.$player['After']);
        array_push($ryan_scores, $player['Total']);
        break;

      case 'JASON DAY':
      case 'XANDER SCHAUFFELE':
      case 'JORDAN SPIETH':
      case 'GARY WOODLAND':
      case '':
      case '':
        //matt
        array_push($matt_results, $player['Total'].' '.$player['Name'].' Thru '.$player['After']);
        array_push($matt_scores, $player['Total']);
        break;
    }
  }

  // error_log(print_r($ryan_results, true));
  // error_log(print_r($cody_results, true));
  // error_log(print_r($tony_results, true));
  // error_log(print_r($drew_results, true));

  // echo var_dump($ryan_results);
  // echo var_dump($cody_results);
  // echo var_dump($tony_results);
  // echo var_dump($drew_results);

  printResults($drew_results, $drew_scores);
  printResults($jeremy_results, $drew_scores);
  printResults($tony_results, $tony_scores);
  printResults($cody_results, $cody_scores);
  printResults($ryan_results, $ryan_scores);
  printResults($matt_results, $drew_scores);

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

?>
