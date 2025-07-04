<?php
// 세션이 이미 시작되어 있는지 확인
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}

// 세션에 사용자 이름이 저장되어 있는지 확인
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // 로그인된 사용자의 아이디를 가져옴
} else {
    // 로그인되지 않은 경우, 사용자 이름을 빈 문자열로 설정
    $username = "";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>상점</title>
    <link rel="icon" href="img/main/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
        }
        .main-content {
            margin-top: 100px;
            min-height: calc(100vh - 200px);
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }
        .shop-container {
            background: white;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            padding: 48px 32px 56px 32px;
            text-align: center;
            margin: 20px auto;
            max-width: 800px;
        }
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 36px;
            letter-spacing: 0.5px;
            color: #333;
        }
        .shop-btns {
            display: flex;
            flex-direction: column;
            gap: 22px;
            align-items: center;
            margin-top: 24px;
        }
        .button {
            padding: 15px 0;
            width: 80%;
            max-width: 320px;
            font-size: 1.15em;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 24px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            box-shadow: none;
            margin: 0 auto;
            display: block;
        }
        .button:hover {
            background: #218838;
            color: white;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .shop-container { 
                padding: 24px 8px 40px 8px; 
                margin: 10px;
            }
            .page-title { 
                font-size: 1.8rem; 
            }
            .button { 
                font-size: 1em; 
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container" id="header_container">
        <a href="#">
            <div id="logo">
                <div id="black_logo_background">
                    <img id="logo_img" src="img/main/apple.jpg" alt="logo">
                </div>
                <p id="logo_text">AppRoad </p>
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

<div class="main-content">
    <div class="container">
        <div class="shop-container">
            <h1 class="page-title">🛍️ 상점</h1>
            <div class="shop-btns">
                <a href="seller.php" class="button">📦 판매하기</a>
                <a href="store.php" class="button">🛒 구매하기</a>
                <a href="mypage.php" class="button">👤 마이페이지</a>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container" id="footer_container">
        <p>본 사이트는 데이터베이스 텀프로젝트를 위한 예시 사이트입니다.</p>
    </div>
</footer>
</body>
</html>
