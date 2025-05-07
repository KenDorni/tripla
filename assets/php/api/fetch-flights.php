<?php
header('Content-Type: application/json');

function loadCityToIATAMap($csvPath) {
    $map = [];
    if (($handle = fopen($csvPath, "r")) !== false) {
        $header = fgetcsv($handle); // skip header
        while (($row = fgetcsv($handle)) !== false) {
            $city = ucfirst(strtolower(trim($row[2])));
            $iata = trim($row[0]);
            if ($city && $iata) {
                $map[explode('-',explode(' ',$city)[0])[0]] = $iata;
            }
        }
        fclose($handle);
    }
    //echo print_r($map, true);
    return $map;
}

$csvPath = __DIR__ . '/airports.csv'; // adjust path if needed
$cityToIATA = loadCityToIATAMap($csvPath);

$fromCity = ucfirst(strtolower(trim($_GET['from'] ?? '')));;
$toCity = $_GET['to'] ?? '';
$date =  explode('T',explode(' ',$_GET['date'])[0])[0] ?? '';

$from = $cityToIATA[$fromCity] ?? '';
$to = $cityToIATA[$toCity] ?? '';

$api_key = '86940468db8a3e69a0249a1cde207a1d556634e25398ec8ff99bad04a7939943';

if (!$from || !$to || !$date) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters', 'from' => $from, 'to' => $to, 'date' => $date, '' => $cityToIATA[$fromCity] . "-" . $cityToIATA[$toCity]]);
    exit;
}

$url = "https://serpapi.com/search.json?" . http_build_query([
        'engine' => 'google_flights',
        'type' => 2, //1 - Round trip (default), 2 - One way, 3 - Multi-city
        'departure_id' => $from,
        'arrival_id' => $to,
        'outbound_date' => $date,
        'api_key' => $api_key
    ]);

$response = file_get_contents($url);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to connect to SerpAPI']);
    exit;
}

$data = json_decode($response, true);
$flights = [];

if (isset($data['other_flights'])) {
    foreach ($data['other_flights'][0]['flights'] as $flight) {
        $flights[] = $flight;/*[
            'airline' => $flight['airline']['name'] ?? 'Unknown',
            'departure_time' => $flight['departure_airport']['time'] ?? '',
            'arrival_time' => $flight['arrival_airport']['time'] ?? ''
        ];*/
    }
}
//echo json_encode(['flights' => $data]);
echo json_encode(['flights' => $flights]);

