<?php
// checkout.php

// 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// 로그인 정보가 없으면 로그인 페이지로 이동
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 데이터베이스 연결
$conn = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['quantity'], $_POST['payment_method'], $_POST['buyer_phone'], $_POST['buyer_address'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        $payment_method = $_POST['payment_method'];
        $buyer_phone = $conn->real_escape_string($_POST['buyer_phone']);
        $buyer_address = $conn->real_escape_string($_POST['buyer_address']);
        $buyer_name = $_SESSION['username'];

        // 상품 정보 가져오기
        $product_sql = "SELECT * FROM products WHERE id=$product_id";
        $product_result = $conn->query($product_sql);
        if ($product_result->num_rows > 0) {
            $product_row = $product_result->fetch_assoc();

            // 재고 확인
            if ($product_row['quantity'] >= $quantity) {
                // 결제 처리 로직을 여기에 추가
                // 예: 데이터베이스 업데이트, 결제 API 호출 등

                // 상품 재고 갱신
                $new_quantity = $product_row['quantity'] - $quantity;
                $update_sql = "UPDATE products SET quantity=$new_quantity WHERE id=$product_id";
                if ($conn->query($update_sql) === TRUE) {
                    // 구매 기록 추가 (주문 상태 포함)
                    $purchase_sql = "INSERT INTO purchases (product_id, buyer_name, buyer_phone, buyer_address, quantity, seller_name, status) VALUES ($product_id, '$buyer_name', '$buyer_phone', '$buyer_address', $quantity, '{$product_row['seller_name']}', 'pending')";
                    if ($conn->query($purchase_sql) === TRUE) {
                        // echo "<div class='alert alert-success'>결제가 완료되었습니다.</div>";
                        header("Location: mypage.php");
                        exit;
                    } else {
                        echo "<div class='alert alert-danger'>구매 과정에서 문제가 발생했습니다: " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>상품 재고 갱신에 실패했습니다: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>선택한 상품의 재고가 부족합니다.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>상품 정보를 가져오는 데 실패했습니다.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>필수 정보가 누락되었습니다.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>잘못된 접근입니다.</div>";
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="icon" href="img/main/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }
        .checkout-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .alert {
            margin-top: 20px;
        }
        .my-page-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }
        .my-page-btn:hover {
            background-color: #0056b3;
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
                <p id="logo_text">AppRoad</p>
            </div>
        </a>
        <ul id="nav_ul">
            <li><a href="./index.php">홈</a></li>
            <li><a href="./community.php">커뮤니티</a></li>
            <?php
            // 사용자가 로그인되어 있으면 "로그아웃" 링크를 표시
            if(!empty($_SESSION['username'])) {
                echo '<li><a href="./login/logout.php">로그아웃</a></li>';
            } else {    
                // 로그인되어 있지 않은 경우, "로그인" 링크를 표시
                echo '<li><a href="./login/login.php">로그인</a></li>';
            }
            ?>
            <li><a href="sub.php">상점</a></li>
            
        </ul>
    </div>
</header>

<br><br><br><br>

<div class="container">
    <h2>결제 페이지</h2>
    <?php
    // 사용자가 로그인되어 있으면 사용자 이름을 표시
    if(!empty($_SESSION['username'])) {
        echo '<p>안녕하세요, ' . htmlspecialchars($_SESSION['username']) . ' 님!</p>';
    }
    ?>

    <div class="checkout-container">
        <?php
        // POST 요청이 아닌 경우에만 메시지 표시
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<div class='alert alert-info'>결제 페이지입니다.</div>";
        }
        ?>
        <a href="mypage.php" class="my-page-btn">마이페이지로 가기</a>
    </div>
</div>

<script>
    function calculateTotal(price) {
        var quantity = document.querySelector('input[name="quantity"]').value;
        var total_price = price * quantity;
        document.getElementById('total_price').innerText = "$" + total_price.toFixed(2);
    }
</script>
</body>
</html>
