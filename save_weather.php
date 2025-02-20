<?php
include 'db_connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = $_POST['city'];
    $apiKey = "39973295a64c1ec8d14835ee928a6d74"; // Replace with your OpenWeather API Key

    // OpenWeather API URL
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

    // Fetch weather data from API
    $response = file_get_contents($apiUrl);
    if (!$response) {
        echo json_encode(["status" => "error", "message" => "Failed to fetch weather data."]);
        exit;
    }

    // Decode JSON response
    $weatherData = json_decode($response, true);

    // Check if the API returned valid data
    if ($weatherData["cod"] != 200) {
        echo json_encode(["status" => "error", "message" => "City not found."]);
        exit;
    }

    // Extract required weather details
    $temperature = $weatherData['main']['temp'];
    $humidity = $weatherData['main']['humidity'];
    $wind_speed = $weatherData['wind']['speed'];

    // Save data into database
    $stmt = $conn->prepare("INSERT INTO weather_records (city, temperature, humidity, wind_speed) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sddd", $city, $temperature, $humidity, $wind_speed);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "temperature" => $temperature, "humidity" => $humidity, "wind_speed" => $wind_speed]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save data."]);
    }

    $stmt->close();
}
?>
