<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        input, button {
            padding: 12px;
            margin: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 16px;
        }
        input {
            width: 70%;
            text-align: center;
            border: 2px solid #fff;
            background: transparent;
            color: #fff;
        }
        button {
            background: #ff9800;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }
        button:hover {
            background: #e65100;
        }
        #weatherResult {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: none;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            color: #fff;
            text-align: center;
        }
        th {
            background: #ff9800;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .loading {
            width: 40px;
            height: 40px;
            border: 5px solid #fff;
            border-top: 5px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
            margin: 10px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🌤️ Weather App</h2>
        <input type="text" id="city" placeholder="Enter City">
        <button onclick="getWeather()">Get Weather</button>
        <div class="loading" id="loader"></div>
        <div id="weatherResult"></div>

        <h3>📊 Past Weather Records</h3>
        <table>
            <tr>
                <th>City</th>
                <th>Temperature (°C)</th>
                <th>Humidity (%)</th>
                <th>Wind Speed (m/s)</th>
                <th>Recorded At</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM weather_records ORDER BY recorded_at DESC LIMIT 10");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['city']}</td>
                        <td>{$row['temperature']}</td>
                        <td>{$row['humidity']}</td>
                        <td>{$row['wind_speed']}</td>
                        <td>{$row['recorded_at']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function getWeather() {
            let city = document.getElementById('city').value;
            if (city === '') {
                alert('Please enter a city name');
                return;
            }

            document.getElementById('loader').style.display = 'block';
            fetch('save_weather.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'city=' + encodeURIComponent(city)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loader').style.display = 'none';
                let weatherDiv = document.getElementById('weatherResult');
                if (data.status === "success") {
                    weatherDiv.innerHTML = `
                        <h4>🌍 Weather in ${city}</h4>
                        <p>🌡️ Temperature: <b>${data.temperature}°C</b></p>
                        <p>💧 Humidity: <b>${data.humidity}%</b></p>
                        <p>🌬️ Wind Speed: <b>${data.wind_speed} m/s</b></p>
                        <p style="color: lightgreen;">✔️ Weather saved to database!</p>
                    `;
                    weatherDiv.style.display = 'block';
                    location.reload();
                } else {
                    weatherDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
                    weatherDiv.style.display = 'block';
                }
            });
        }
    </script>
</body>
</html>