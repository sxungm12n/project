<?php
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

// 로그인한 사용자의 정보를 가져옴
$username = $_SESSION['username'];

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 상품 등록 처리
if (isset($_POST['submit_product'])) {
    // 사용자가 로그인했는지 확인
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // 세션에서 사용자 이름 가져오기
        $product_name = $conn->real_escape_string($_POST['product_name']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);
        $seller_phone = $conn->real_escape_string($_POST['seller_phone']);
        $seller_address = $conn->real_escape_string($_POST['seller_address']);
        
        // 이미지 업로드 처리
        $product_image = '';
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['product_image']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $file_extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '.' . $file_extension;
                $upload_path = 'uploads/products/' . $file_name;
                
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
                    $product_image = $upload_path;
                } else {
                    echo "<div class='alert alert-danger'>이미지 업로드에 실패했습니다.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>지원하지 않는 이미지 형식입니다. (JPEG, PNG, GIF, WebP만 가능)</div>";
            }
        }

        // seller_id를 usertbl에서 가져오기
        $seller_id_query = "SELECT MemberID FROM usertbl WHERE userName = '$username'";
        $seller_result = $conn->query($seller_id_query);
        
        if ($seller_result->num_rows > 0) {
            $seller_row = $seller_result->fetch_assoc();
            $seller_id = $seller_row['MemberID'];
            
            // 상품 등록 쿼리
            $sql = "INSERT INTO products (seller_id, product_name, product_image, price, quantity, seller_name, seller_phone, seller_address) VALUES ('$seller_id', '$product_name', '$product_image', '$price', '$quantity', '$username', '$seller_phone', '$seller_address')";
        } else {
            echo "<div class='alert alert-danger'>사용자 정보를 찾을 수 없습니다. 다시 로그인해주세요.</div>";
            exit;
        }
        if ($conn->query($sql) === TRUE) {
            // echo "<div class='alert alert-success'>상품이 성공적으로 등록되었습니다.</div>";
            header("Location: seller.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>상품 등록에 실패했습니다: " . $conn->error . "</div>";
        }
    } else {
        // 로그인되지 않은 경우 처리
        echo "<div class='alert alert-warning'>로그인 후에 상품을 등록할 수 있습니다.</div>";
    }
}

// 페이지네이션 설정
$limit = 5; // 한 페이지에 표시할 상품 수
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <title>상품 등록</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/main/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/sunn-us/SUITE/fonts/static/woff2/SUITE.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .add-product-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .add-product-container h2 {
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 10px;
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
        footer p {
            margin: 0;
        }
        /* 헤더 고정 및 상단 여백 */
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
    </style>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('image_preview');
            const previewImg = document.getElementById('preview_img');
            const file = input.files[0];
            
            if (file) {
                // 파일 타입 검증
                if (!file.type.match('image.*')) {
                    alert('이미지 파일만 선택할 수 있습니다.');
                    input.value = '';
                    return;
                }
                
                // 파일 크기 검증 (5MB 제한)
                if (file.size > 5 * 1024 * 1024) {
                    alert('파일 크기는 5MB 이하여야 합니다.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
        
        function removeImage() {
            const input = document.getElementById('product_image');
            const preview = document.getElementById('image_preview');
            const previewImg = document.getElementById('preview_img');
            
            input.value = '';
            previewImg.src = '';
            preview.style.display = 'none';
        }
        
        // 가격 입력 필드 처리
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            
            // 숫자만 입력 가능
            priceInput.addEventListener('keypress', function(e) {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            // 입력 시 숫자가 아닌 문자 제거
            priceInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
            
            // 폼 제출 시 처리
            document.querySelector('form').addEventListener('submit', function(e) {
                // 가격이 비어있지 않은지 확인
                if (priceInput.value.trim() === '') {
                    e.preventDefault();
                    alert('가격을 입력해주세요.');
                    return;
                }
            });
        });
    </script>
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
            if (!empty($username)) {
                echo '<li><a href="./login/logout.php">로그아웃</a></li>';
            } else {
                // 로그인되어 있지 않은 경우, "로그인" 링크를 표시
                echo '<li><a href="./login/login.php">로그인</a></li>';
            }
            ?>
            <li><a href="sub.php">상점</a></li>
            <?php
            // 사용자가 로그인되어 있으면 사용자 이름을 표시
            if (!empty($username)) {
                echo '<li><a href="#">' . $username . ' 님</a></li>';
            }
            ?>
        </ul>
    </div>
</header>

<div class="main-content">
    <div class="container">
        <div class="add-product-container">
            <h2 class="text-center">상품 등록</h2>
        <form action="seller.php" method="post" class="form-group" enctype="multipart/form-data">
            <label for="product_name">상품명:</label>
            <input type="text" id="product_name" name="product_name" class="form-control" required>
            
            <label for="product_image">상품 이미지:</label>
            <input type="file" id="product_image" name="product_image" class="form-control" accept="image/*" onchange="previewImage(this)">
            <small class="form-text text-muted">JPEG, PNG, GIF, WebP 형식만 지원됩니다. (선택사항)</small>
            <div id="image_preview" class="mt-2" style="display: none;">
                <div style="background-color: #f8f9fa; border-radius: 8px; padding: 15px; text-align: center; width: 200px; height: 200px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                    <img id="preview_img" src="" alt="미리보기" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 4px;">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">제거</button>
            </div>
            
            <label for="price">가격:</label>
            <div class="input-group">
                <input type="number" id="price" name="price" class="form-control" min="0" step="1" required>
                <div class="input-group-append">
                    <span class="input-group-text">원</span>
                </div>
            </div>
            
            <label for="quantity">수량:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" class="form-control" required>
            
            <label for="seller_phone">전화번호:</label>
            <input type="text" id="seller_phone" name="seller_phone" class="form-control" required>
            
            <label for="seller_address">주소:</label>
            <textarea id="seller_address" name="seller_address" class="form-control" required></textarea>
            
            <input type="submit" name="submit_product" value="상품 등록" class="btn btn-primary">
        </form>

        <?php
        // 상품 목록 파일을 include하여 표시
        include("seller_products_paginated.php");
        ?>
    </div>
</div>
</div>

<nav>
    <ul class="pagination">
        <?php
        // 총 상품 수를 계산 (현재 사용자의 상품만)
        $sql_total = "SELECT COUNT(*) AS total FROM products WHERE seller_name = '$username'";
        $result_total = $conn->query($sql_total);
        $row_total = $result_total->fetch_assoc();
        $total_pages = ceil($row_total['total'] / $limit);

        // 이전 페이지 링크
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="seller.php?page=' . ($page - 1) . '">이전</a></li>';
        }

        // 페이지 번호 링크
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="seller.php?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // 다음 페이지 링크
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="seller.php?page=' . ($page + 1) . '">다음</a></li>';
        }
        ?>
    </ul>
</nav>

<footer>
        <div class="container" id="footer_container">
            <p>본 사이트는 데이터베이스 텀프로젝트를 위한 예시 사이트입니다.</p>
        </div>
    </footer>
</body>
</html>
