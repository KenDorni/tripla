<?php
header("Content-Type: application/json"); // Ensure JSON output

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["place_name"])) {
    $place = urlencode($_POST["place_name"]);
    $api_key = "5c3331a286514fa0587cdede852f5ab95cb6c754cd25ee63ab7929c7b4acb132";

    $url = "https://serpapi.com/search?engine=google_maps_reviews&q={$place}&api_key={$api_key}";

    $response = @file_get_contents($url); // Suppress errors
    if ($response === FALSE) {
        echo json_encode(["status" => "error", "message" => "Failed to connect to API"]);
        exit;
    }

    $data = json_decode($response, true);
    if (isset($data["error"])) { // Handle API errors
        echo json_encode(["status" => "error", "message" => $data["error"]]);
        exit;
    }

    if (!empty($data["reviews"])) {
        $reviews = [];
        foreach ($data["reviews"] as $review) {
            $reviews[] = [
                "author" => $review["user"]["name"] ?? "Anonymous",
                "rating" => $review["rating"] ?? "N/A",
                "text" => $review["text"] ?? "No review text available."
            ];
        }
        echo json_encode(["status" => "success", "reviews" => $reviews]);
    } else {
        echo json_encode(["status" => "error", "message" => "No reviews found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
