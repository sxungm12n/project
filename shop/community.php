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

// 게시글 목록 가져오기
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT postsTBL.*, userTBL.userName FROM postsTBL JOIN userTBL ON postsTBL.userID = userTBL.MemberID ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) FROM postsTBL";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_row();
$total_pages = ceil($total_row[0] / $limit);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppRoad 커뮤니티</title>
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
            margin-top: 100px; /* 헤더 높이 + 여백 */
            min-height: calc(100vh - 200px); /* 전체 높이에서 헤더와 푸터 높이 제외 */
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }
        .btn-group {
            margin-bottom: 30px;
            text-align: center;
        }
        .btn {
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 0 10px;
            display: inline-block;
        }
        .btn:hover {
            background-color: #218838;
            color: white;
            text-decoration: none;
        }
        .posts .post {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
        }
        .posts .post h2 {
            margin-top: 0;
            color: #333;
            font-size: 22px;
            font-weight: 600;
        }
        .posts .post p {
            margin-top: 15px;
            color: #666;
            line-height: 1.7;
            font-size: 16px;
        }
        .posts .post .meta {
            color: #888;
            font-size: 14px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .pagination {
            margin-top: 30px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 15px;
            margin: 0 5px;
            background-color: #f8f9fa;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }
        .pagination a.active {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        .pagination a:hover {
            background-color: #e9ecef;
            text-decoration: none;
        }
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
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
    <h1 class="page-title">AppRoad 커뮤니티</h1>
    
    <div class="btn-group">
        <a href="post_form.php" class="btn">📝 게시글 작성하기</a>
        <a href="my_posts.php" class="btn">📋 내가 쓴 글 보기</a>
    </div>

    <div class="posts">
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <p class="meta">
                        <strong>작성자:</strong> <?php echo htmlspecialchars($row['userName']); ?> | 
                        <strong>작성일:</strong> <?php echo htmlspecialchars($row['created_at']); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-posts">
                <p>📭 아직 게시글이 없습니다.</p>
                <p>첫 번째 게시글을 작성해보세요!</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
    </div>
</div>

<footer>
    <div class="container" id="footer_container">
        <p>본 사이트는 데이터베이스 텀프로젝트를 위한 예시 사이트입니다.</p>
    </div>
</footer>
</body>
</html> 