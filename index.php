<?php
echo 'Start of the Sript';
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
  $ryan_results = array();
  $cody_results = array();
  $tony_results = array();
  $drew_results = array();

  foreach($pga['Players'] as $player) {
    // error_log($player['Name']);

    switch (strtoupper($player['Name'])) {

      case 'HIDEKI MATSUYAMA':
      case 'HENRIK STENSON':
      case 'MATT KUCHAR':
      case 'RORY MCILROY':
      case 'PHIL MICKELSON':
      case 'JUSTIN THOMAS':
        //ryan
        array_push($ryan_results, $player['Name'].': '.$player['Total']);
        break;
      case 'DUSTIN JOHNSON':
      case 'BROOKS KOEPKA':
      case 'TOMMY FLEETWOOD':
      case 'BUBBA WATSON':
      case 'XANDER SCHAUFFELE':
      case 'PATRICK REED':
        //cody
        array_push($cody_results, $player['Name'].': '.$player['Total']);
        break;

      case 'TIGER WOODS':
      case 'JON RAHM':
      case 'JORDAN SPIETH':
      case 'PAUL CASEY':
      case 'TONY FINAU':
      case 'BRYSON DECHAMBEAU':
        //tony
        array_push($tony_results, $player['Name'].': '.$player['Total']);
        break;

      case 'RICKIE FOWLER':
      case 'JUSTIN ROSE':
      case 'ADAM SCOTT':
      case 'JASON DAY':
      case 'SERGIO GARCIA':
      case 'FRANCESCO MOLINARI':
        //drew
        array_push($drew_results, $player['Name'].': '.$player['Total']);
        break;
    }
  }

  error_log(print_r($ryan_results, true));
  error_log(print_r($cody_results, true));
  error_log(print_r($tony_results, true));
  error_log(print_r($drew_results, true));


}

?>
