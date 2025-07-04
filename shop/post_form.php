<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// 로그인 여부 확인
if (!isset($_SESSION['username'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login/login.php';</script>";
    exit;
}

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('세션에 userID가 설정되지 않았습니다.'); location.href='login/login.php';</script>";
    exit;
}

$userID = $_SESSION['userID'];
$username = $_SESSION['username'];

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 게시글 작성 처리
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO postsTBL (userID, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userID, $title, $content);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('게시글이 작성되었습니다.'); location.href='community.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 작성</title>
    <link rel="icon" href="img/main/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        .form-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 16px;
        }
        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: #28a745;
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        textarea.form-control {
            min-height: 200px;
            resize: vertical;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #6c757d;
            font-size: 16px;
            border: 2px solid #6c757d;
            padding: 12px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-align: center;
        }
        .back-button:hover {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }
        .form-header h2 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }
        .form-header p {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 14px;
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
        <h1 class="page-title">📝 게시글 작성</h1>
        
        <div class="form-container">
            <div class="form-header">
                <h2>새로운 게시글 작성</h2>
                <p>커뮤니티에 새로운 이야기를 공유해보세요!</p>
            </div>

            <!-- 게시글 작성 폼 -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">📋 제목</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="게시글 제목을 입력하세요" required>
                </div>

                <div class="form-group">
                    <label for="content">📄 내용</label>
                    <textarea id="content" name="content" class="form-control" placeholder="게시글 내용을 입력하세요" required></textarea>
                </div>

                <button type="submit" class="btn-submit">💾 게시글 저장하기</button>
            </form>
            
            <div class="button-group">
                <a href="community.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> 게시판으로 돌아가기
                </a>
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
