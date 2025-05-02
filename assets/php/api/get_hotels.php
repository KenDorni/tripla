<?php

$apiKey = '86940468db8a3e69a0249a1cde207a1d556634e25398ec8ff99bad04a7939943';
$query = 'hotels in luxembourg';

$url = "https://serpapi.com/search.json?engine=google_maps&q=" . urlencode($query) . "&type=search&api_key=" . $apiKey;

$response = file_get_contents($url);
$data = json_decode($response, true);

$results = [];

if (!empty($data['local_results'])) {
    foreach ($data['local_results'] as $hotel) {
        $results[] = [
            'title' => $hotel['title'] ?? '',
            'rating' => $hotel['rating'] ?? 'N/A',
            'reviews' => $hotel['reviews'] ?? '0',
            'address' => $hotel['address'] ?? 'Unknown',
            'image' => $hotel['serpapi_thumbnail'] ?? ''
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($results);
