<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetchTransit'])) {
    $origin = urlencode($_POST['origin']);
    $destination = urlencode($_POST['destination']);
    $apiKey = '86940468db8a3e69a0249a1cde207a1d556634e25398ec8ff99bad04a7939943';
    $url = "https://serpapi.com/search.json?engine=google_maps_directions&start_addr=$origin&end_addr=$destination&mode=transit&api_key=$apiKey";

    $response = file_get_contents($url);
    header('Content-Type: application/json');
    echo $response;
    exit;
}
