<?php
// 설정 파일 예시 - 이 파일을 config.php로 복사하고 실제 값들을 입력하세요
// 이 파일은 GitHub에 업로드되어 다른 개발자들이 참고할 수 있습니다

// OpenWeatherMap API 설정
$config = [
    'weather' => [
        'api_key' => 'YOUR_API_KEY_HERE', // OpenWeatherMap에서 발급받은 API 키를 입력하세요
        'city' => 'Chungju', // 도시 이름 (기본값: 충주)
        'units' => 'metric' // 단위 (metric: 섭씨, imperial: 화씨)
    ],
    'database' => [
        'host' => 'localhost', // 데이터베이스 호스트
        'username' => 'root', // 데이터베이스 사용자명
        'password' => '', // 데이터베이스 비밀번호
        'dbname' => 'shopdb' // 데이터베이스 이름
    ]
];

// 데이터베이스 연결 함수
function getDatabaseConnection() {
    global $config;
    
    $servername = $config['database']['host'];
    $username = $config['database']['username'];
    $password = $config['database']['password'];
    $dbname = $config['database']['dbname'];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// API 키가 없으면 더미 데이터 사용
function getWeatherData() {
    global $config;
    
    if (empty($config['weather']['api_key']) || $config['weather']['api_key'] === 'YOUR_API_KEY_HERE') {
        // API 키가 없으면 더미 데이터 반환
        return [
            'temperature' => 22.5,
            'weatherDescription' => '맑은 하늘',
            'humidity' => 65,
            'windSpeed' => 3.2,
            'iconCode' => '01d',
            'isDemo' => true
        ];
    }
    
    // API 키가 있으면 실제 API 호출
    $apiKey = $config['weather']['api_key'];
    $city = $config['weather']['city'];
    $units = $config['weather']['units'];
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&units={$units}&appid={$apiKey}";
    
    $response = @file_get_contents($apiUrl);
    if ($response === FALSE) {
        // API 호출 실패 시 더미 데이터 반환
        return [
            'temperature' => 22.5,
            'weatherDescription' => '맑은 하늘',
            'humidity' => 65,
            'windSpeed' => 3.2,
            'iconCode' => '01d',
            'isDemo' => true
        ];
    }
    
    $weatherData = json_decode($response, true);
    
    if ($weatherData['cod'] == 200) {
        $weatherDescription = $weatherData['weather'][0]['description'];
        
        // 날씨 설명 번역 매핑
        $weatherDescriptionMapping = [
            "clear sky" => "맑은 하늘",
            "few clouds" => "약간의 구름",
            "scattered clouds" => "드문드문 구름",
            "broken clouds" => "부서진 구름",
            "shower rain" => "소나기",
            "rain" => "비",
            "thunderstorm" => "뇌우",
            "snow" => "눈",
            "mist" => "안개",
            "overcast clouds" => "구름이 잔뜩 낌"
        ];
        
        $translatedWeatherDescription = $weatherDescriptionMapping[$weatherDescription] ?? $weatherDescription;
        
        return [
            'temperature' => round($weatherData['main']['temp'], 1),
            'weatherDescription' => $translatedWeatherDescription,
            'humidity' => $weatherData['main']['humidity'],
            'windSpeed' => $weatherData['wind']['speed'],
            'iconCode' => $weatherData['weather'][0]['icon'],
            'isDemo' => false
        ];
    } else {
        // API 응답 오류 시 더미 데이터 반환
        return [
            'temperature' => 22.5,
            'weatherDescription' => '맑은 하늘',
            'humidity' => 65,
            'windSpeed' => 3.2,
            'iconCode' => '01d',
            'isDemo' => true
        ];
    }
}
?> 