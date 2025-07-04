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

// 게시글 삭제 처리
if (isset($_GET['delete_post'])) {
    $postID = $_GET['delete_post'];
    // 사용자의 게시글인지 확인
    $check_sql = "SELECT * FROM postsTBL WHERE postID = $postID AND userID = $userID";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows == 1) {
        // 게시글 삭제
        $delete_sql = "DELETE FROM postsTBL WHERE postID = $postID";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<script>alert('게시글이 삭제되었습니다.');</script>";
        } else {
            echo "<script>alert('게시글 삭제에 실패했습니다.');</script>";
        }
    } else {
        echo "<script>alert('해당 게시글을 삭제할 수 있는 권한이 없습니다.');</script>";
    }
}

// 사용자가 작성한 게시글 가져오기
$sql = "SELECT * FROM postsTBL WHERE userID = $userID ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내 게시글</title>
    <link rel="icon" href="img/main/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .post {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
        }
        .post h3 {
            margin-top: 0;
            color: #333;
            font-size: 22px;
            font-weight: 600;
        }
        .post p {
            margin-top: 15px;
            color: #666;
            line-height: 1.7;
            font-size: 16px;
        }
        .post small {
            color: #888;
            font-size: 14px;
            margin-top: 15px;
            display: block;
        }
        .post-actions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .post-actions a {
            text-decoration: none;
            color: #28a745;
            margin-right: 20px;
            font-weight: 500;
            transition: color 0.3s;
        }
        .post-actions a:hover {
            color: #218838;
            text-decoration: none;
        }
        .back-button {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: white;
            background-color: #28a745;
            font-size: 16px;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #218838;
            color: white;
            text-decoration: none;
        }
        .no-posts {
            text-align: center;
            padding: 50px 20px;
            color: #666;
            font-size: 18px;
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
        <h1 class="page-title">📝 내가 쓴 게시글</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                echo "<small>📅 작성일: " . htmlspecialchars($row['created_at']) . "</small>";
                echo "<div class='post-actions'>";
                echo "<a href='edit_post.php?postID=" . $row['postID'] . "'><i class='fas fa-edit'></i> 수정</a>";
                echo "<a href='my_posts.php?delete_post=" . $row['postID'] . "' onclick='return confirm(\"정말 삭제하시겠습니까?\")'><i class='fas fa-trash-alt'></i> 삭제</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-posts'>";
            echo "<p>📭 아직 작성한 게시글이 없습니다.</p>";
            echo "<p>첫 번째 게시글을 작성해보세요!</p>";
            echo "</div>";
        }
        ?>

        <div style="text-align: center;">
            <a href="community.php" class="back-button"><i class="fas fa-arrow-left"></i> 게시판으로 돌아가기</a>
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