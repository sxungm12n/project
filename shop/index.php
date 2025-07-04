<?php
// 세션이 이미 시작되어 있는지 확인
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}

require_once 'config.php';

// 세션에 사용자 이름이 저장되어 있는지 확인
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // 로그인된 사용자의 아이디를 가져옴
} else {
    // 로그인되지 않은 경우, 사용자 이름을 빈 문자열로 설정
    $username = "";
}

// 날씨 데이터 가져오기
$weatherData = getWeatherData();
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning</title>
    <link rel="icon" href="img/main/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/common.css">
</head>

<body>
<header>
    <div class="container" id="header_container">
        <a href="#">
            <div id="logo">
                <div id="black_logo_background">
                    <img id="logo_img" src="img/main/apple.jpg" alt="logo">
                </div>
                <p id="logo_text">AppRoad</p>
            </div>
        </a>

        <ul id="nav_ul">
            <li><a href="./index.php">홈</a></li>
            <li><a href="./community.php">커뮤니티</a></li>
            <?php
            // 사용자가 로그인되어 있으면 "로그아웃" 링크를 표시
            if(!empty($username)) {
                echo '<li><a href="./login/logout.php">로그아웃</a></li>';
            } else {
                // 로그인되어 있지 않은 경우, "로그인" 링크를 표시
                echo '<li><a href="./login/login.php">로그인</a></li>';
            }
            ?>
            <li><a href="sub.php">상점</a></li>
           
            <?php
            // 사용자가 로그인되어 있으면 사용자 이름을 표시
            if(!empty($username)) {
                echo '<li><a href="#">'.$username.' 님</a></li>';
            }
            ?>
        </ul>
    </div>
</header>

<div id="slider">
    <div class="container">
        <h1>
            Approad의 사이트<br>충주 사과
        </h1>
        <p>충주사과의 잇점<br>
            중주 사과를 소개합니다.<br>
            1907년 개량종 사과가 도입되었으며, 1912년 지현동에서 식재한 조생종사과가 효시가 되어 현재 도내에서 가장 넓은 면적을 차지하며 재배되어 있으며 다른 생산지에 비해 일교차가 크고 일조량이 풍부하여
            , 빛깔, 당도, 향기에서 으뜸을 차지하고 있으며, 수려한 자연환경과 어울려 충주시민의 인내와 진실함을 표현하는 대표적인 특산물임
        </p>
        <a href="sub.php">
            <div id="arrow-box">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px" y="0px" viewBox="0 0 345 116" style="enable-background:new 0 0 345 116;"
                    xml:space="preserve">
                    <g class="arrow-all">
                        <rect x="-22.25" y="-6.89" class="arrow-all-style" width="417.76" height="148.35" />
                    </g>
                    <path class="arrow-right" d="M230.7,58c0,30.59,24.97,55.4,55.78,55.4s55.78-24.8,55.78-55.4S317.28,2.6,286.48,2.6
                c-30.78,0-55.74,24.76-55.78,55.32H3.13" />
                    <polyline class="arrow-right" points="284.47,63.44 289.89,57.99 284.47,52.55 	" />
                </svg>
            </div>
        </a>
    </div>
</div>

<div id="mdPick">
    <div class="container">
        <div class="title_line">
            <h3>🌤️ 오늘의 날씨 (데모)</h3>
        </div>
        <article>
            <div class="weather-container">
                <div class="weather-left">
                    <div class="location-info">
                        <h1>충주시</h1>
                        <p class="current-time"><?php echo date('Y년 m월 d일 H:i'); ?></p>
                    </div>
                    <div class="weather-icon">
                        <img src="http://openweathermap.org/img/wn/<?php echo $weatherData['iconCode']; ?>@2x.png" alt="Weather icon">
                    </div>
                </div>
                
                <div class="weather-right">
                    <div class="weather-main">
                        <div class="temperature-section">
                            <div class="temperature-display"><?php echo $weatherData['temperature']; ?>°</div>
                            <div class="weather-description"><?php echo $weatherData['weatherDescription']; ?></div>
                        </div>
                        
                        <div class="weather-details">
                            <div class="weather-item">
                                <div class="weather-icon-small">💧</div>
                                <div class="weather-data">
                                    <div class="label">습도</div>
                                    <div class="value"><?php echo $weatherData['humidity']; ?>%</div>
                                </div>
                            </div>
                            <div class="weather-item">
                                <div class="weather-icon-small">💨</div>
                                <div class="weather-data">
                                    <div class="label">풍속</div>
                                    <div class="value"><?php echo $weatherData['windSpeed']; ?> m/s</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="weather-advice-section">
                <?php
                // 비가 오는 경우 메시지 표시
                if ($weatherData['weatherDescription'] == "비" || $weatherData['weatherDescription'] == "소나기") {
                            echo '<div class="weather-advice rain">
                                    <div class="advice-icon">🌧️</div>
                                    <div class="advice-content">
                                        <h4>비 오는 날씨</h4>
                                        <p>물을 조금만 주세요</p>
                                    </div>
                                  </div>';
                } else {
                            echo '<div class="weather-advice no-rain">
                                    <div class="advice-icon">☀️</div>
                                    <div class="advice-content">
                                        <h4>맑은 날씨</h4>
                                        <p>물을 주세요</p>
                                    </div>
                                  </div>';
                }
                ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>

<div id="QandA">
    <div class="container">
        <div class="title_line">
            <h3>Q&A</h3>
        </div>
        <p id="question">자주 하시는 질문</p>
        <div id="QandA_align">
            <a href="apple1.php">
                <div class="QandA_list">
                    <div class="QandA_imgbox">
                        <img src="img/main/apple3.png" alt="QandA_1">
                    </div>
                    <p>🍎 충주사과가 왜 유명한가요?</p>
                </div>
            </a>
            <a href="apple2.php">
                <div class="QandA_list">
                    <div class="QandA_imgbox">
                        <img src="img/main/apple2.jpg" alt="QandA_2">
                    </div>
                    <p>💪 사과의 효능은 무엇이 있나요?</p>
                </div>
            </a>
            <a href="apple3.php">
                <div class="QandA_list">
                    <div class="QandA_imgbox">
                        <img src="img/main/apple1.jpg" alt="QandA_3">
                    </div>
                    <p>⭐ 좋은 사과는 무엇인가요?</p>
                </div>
            </a>
        </div>
    </div>
</div>

<div id="company">
    <a href="#">
        <div class="container">
            <div id="company_img_container"></div>
            <div id="company_vision">
                <p id="company_vision_goal">AppRoad의 목표</p>
                <h2>충주의 발전을 위한<br>
                    사과 플랫폼 구축.</h2>
                <p id="company_vision_explanation">
                    AppRoad은 충주시에서 재배된 사과를 판매,구매할 수 있는<br>
                    플랫폼을 구축하여 지역 경제에 기여하고자 합니다.<br>
                    주 사과의 인지도를 높이고 판매율을 증가시키며, <br>
                    농가들의 소득 향상을 도모하여 많은 사람들이<br>
                    편의 누릴 수 있도록 최선을 다하겠습니다.</p>
            </div>
        </div>
    </a>
</div>

<footer>
    <div class="container">
        <div id="footer_first">
            <a href="#">
                <div id="white_logo">
                    <div id="white_logo_background">
                        <img id="white_logo_img" src="img/main/apple_logo.png" alt="white_logo">
                    </div>
                    <p id="white_logo_text">AppRoad</p>
                </div>
            </a>
            <p id="notice">본 사이트는 데이터베이스 텀프로젝트를 위한 사이트 입니다.</p>
        </div>
        <div id="footer_line"></div>
    </footer>
</body>

</html>