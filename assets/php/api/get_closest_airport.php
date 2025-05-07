<?php
// backend.php

// Function to get coordinates from a location string using OpenCage Geocoder API
function getCoordinates($location) {
    $apiKey = 'ae9ae6312a21466e86bef61c00dea860';  // Replace with your OpenCage API key
    $url = "https://api.opencagedata.com/geocode/v1/json?q=" . urlencode($location) . "&key=" . $apiKey;

    // Make API request
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Check if the response contains valid data
    if (isset($data['results'][0])) {
        $lat = $data['results'][0]['geometry']['lat'];
        $lon = $data['results'][0]['geometry']['lng'];
        return ['lat' => $lat, 'lon' => $lon];
    } else {
        return null;
    }
}

// Function to get the closest airport based on coordinates
function getClosestAirport($lat, $lon) {
    //$apiKey = '20e9703dc1mshccd1a427ba4814bp1a1f31jsn011ae0ab208c';  // Replace with your Aviation API key
    //$url = "https://aviation-api.p.rapidapi.com/airports/nearby/{$lat},{$lon}";
    /*$url = "https://iatacodes-iatacodes-v1.p.rapidapi.com/api/v9/nearby?lat={$lat}&lng={$lon}&distance=20";

    $options = [
        "http" => [
            "header" => "X-RapidAPI-Host: aviation-api.p.rapidapi.com\r\n" .
                "X-RapidAPI-Key: {$apiKey}\r\n"
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);*/


    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://iatacodes-iatacodes-v1.p.rapidapi.com/api/v9/nearby?lat={$lat}&lng={$lon}&distance=80",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: iatacodes-iatacodes-v1.p.rapidapi.com",
            "x-rapidapi-key: 20e9703dc1mshccd1a427ba4814bp1a1f31jsn011ae0ab208c"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // Check if airports data is returned
    if (!empty($data)) {
        return $data[0];  // Assuming the first result is the closest airport
    } else {
        return null;
    }
}

// Handle the request from JavaScript
if (isset($_GET['location'])) {
    $location = $_GET['location'];

    // Get coordinates for the location
    $coordinates = getCoordinates($location);

    if ($coordinates) {
        $lat = $coordinates['lat'];
        $lon = $coordinates['lon'];

        // Get the closest airport
        $airport = getClosestAirport($lat, $lon);

        echo  json_encode($coordinates);

        if ($airport) {
            echo json_encode($airport);  // Return the closest airport in JSON format
        } else {
            echo json_encode(['error' => 'No nearby airports found.']);
        }
    } else {
        echo json_encode(['error' => 'Location not found.']);
    }
}
