<?php
require_once 'config.php';

// 날씨 데이터 가져오기
$weatherData = getWeatherData();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>날씨 정보</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .weather-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .weather-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: block;
        }
        .temperature {
            font-size: 3rem;
            font-weight: 300;
            color: #2c3e50;
        }
        .weather-description {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 1rem;
        }
        .weather-details {
            background: rgba(52, 152, 219, 0.1);
            border-radius: 15px;
            padding: 1rem;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(52, 152, 219, 0.2);
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .demo-notice {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="weather-card p-4">
                    <?php if ($weatherData['isDemo']): ?>
                        <div class="demo-notice">
                            🎭 데모 모드 - 실제 날씨 데이터가 아닙니다
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mb-4">
                        <img src="http://openweathermap.org/img/wn/<?php echo $weatherData['iconCode']; ?>@2x.png" 
                             alt="날씨 아이콘" class="weather-icon">
                        <div class="temperature"><?php echo $weatherData['temperature']; ?>°C</div>
                        <div class="weather-description"><?php echo $weatherData['weatherDescription']; ?></div>
                    </div>
                    
                    <div class="weather-details">
                        <div class="detail-item">
                            <span>습도</span>
                            <span><?php echo $weatherData['humidity']; ?>%</span>
                        </div>
                        <div class="detail-item">
                            <span>풍속</span>
                            <span><?php echo $weatherData['windSpeed']; ?> m/s</span>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-outline-primary">홈으로 돌아가기</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
