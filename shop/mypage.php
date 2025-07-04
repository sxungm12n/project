<?php
// mypage.php

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

$username = $_SESSION['username'];

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 주문 상태 업데이트 처리
if (isset($_POST['update_order_status'])) {
    $purchase_id = intval($_POST['purchase_id']);
    $new_status = $conn->real_escape_string($_POST['new_status']);
    
    // 먼저 해당 주문이 현재 사용자의 것인지 확인
    $check_sql = "SELECT p.*, pr.seller_name as product_seller 
                  FROM purchases p 
                  JOIN products pr ON p.product_id = pr.id 
                  WHERE p.id = $purchase_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result && $check_result->num_rows > 0) {
        $order = $check_result->fetch_assoc();
        
        // 판매자 확인 (purchases.seller_name 또는 products.seller_name)
        $is_seller = ($order['seller_name'] == $username || $order['product_seller'] == $username);
        
        if ($is_seller) {
            // seller_name이 비어있으면 업데이트
            if (empty($order['seller_name'])) {
                $update_seller_sql = "UPDATE purchases SET seller_name = '$username' WHERE id = $purchase_id";
                $conn->query($update_seller_sql);
            }
            
            // 상태 업데이트
            $sql = "UPDATE purchases SET status='$new_status' WHERE id=$purchase_id";
            if ($conn->query($sql) === TRUE) {
                header("Location: mypage.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>주문 상태 업데이트에 실패했습니다: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>이 주문을 수정할 권한이 없습니다.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>주문을 찾을 수 없습니다.</div>";
    }
}

// 상품 수정 처리
if (isset($_POST['edit_product'])) {
    $product_id = intval($_POST['product_id']);
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $seller_phone = $conn->real_escape_string($_POST['seller_phone']);
    $seller_address = $conn->real_escape_string($_POST['seller_address']);

    $sql = "UPDATE products SET product_name='$product_name', price=$price, quantity=$quantity, seller_phone='$seller_phone', seller_address='$seller_address' WHERE id=$product_id AND seller_name='$username'";
    if ($conn->query($sql) === TRUE) {
        // echo "<div class='alert alert-success'>상품이 성공적으로 수정되었습니다.</div>";
        header("Location: mypage.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>상품 수정에 실패했습니다: " . $conn->error . "</div>";
    }
}

// 상품 삭제 처리
if (isset($_POST['delete_product'])) {
    $product_id = intval($_POST['product_id']);
    $sql = "DELETE FROM products WHERE id=$product_id AND seller_name='$username'";
    if ($conn->query($sql) === TRUE) {
        // echo "<div class='alert alert-success'>상품이 성공적으로 삭제되었습니다.</div>";
        header("Location: mypage.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>상품 삭제에 실패했습니다: " . $conn->error . "</div>";
    }
}

// 페이지네이션 설정
$items_per_page = 5;

// 구매 목록 페이지네이션
$purchase_page = isset($_GET['purchase_page']) ? intval($_GET['purchase_page']) : 1;
$purchase_offset = ($purchase_page - 1) * $items_per_page;

// 판매 목록 페이지네이션
$sale_page = isset($_GET['sale_page']) ? intval($_GET['sale_page']) : 1;
$sale_offset = ($sale_page - 1) * $items_per_page;

// 주문 관리 페이지네이션
$order_page = isset($_GET['order_page']) ? intval($_GET['order_page']) : 1;
$order_offset = ($order_page - 1) * $items_per_page;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
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
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .list {
            margin-bottom: 40px;
        }
        .list h3 {
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .list table {
            width: 100%;
            border-collapse: collapse;
        }
        .list th, .list td {
            border-bottom: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .list th {
            background-color: #4CAF50;
            color: white;
        }
        .list tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .list tr:hover {
            background-color: #f1f1f1;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 8px 16px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-warning {
            background-color: #f0ad4e;
            color: white;
        }
        .btn-danger {
            background-color: #d9534f;
            color: white;
        }
        .btn-success {
            background-color: #5cb85c;
            color: white;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3c763d;
        }
        .alert-danger {
            background-color: #f2dede;
            border-color: #ebccd1;
            color: #a94442;
        }
        .alert-info {
            background-color: #d9edf7;
            border-color: #bce8f1;
            color: #31708f;
        }
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #337ab7;
            text-decoration: none;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .pagination .active {
            background-color: #337ab7;
            color: white;
            border: 1px solid #337ab7;
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
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
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
                    <p id="logo_text">AppRoad 마이페이지</p>
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
    <div class="container">
        <h2>마이페이지</h2>

        <!-- 구매 목록 -->
        <div class="list purchase-list">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">구매 목록</h3>
                <a href="store.php" class="btn btn-success" style="background: #6db37f; border: none; padding: 8px 20px; border-radius: 20px; font-weight: 600;">구매하기</a>
            </div>
            <?php
            $sql = "SELECT COUNT(*) as count FROM purchases WHERE buyer_name='$username'";
            $result = $conn->query($sql);
            $total_purchases = $result->fetch_assoc()['count'];
            $total_purchase_pages = ceil($total_purchases / $items_per_page);

            $sql = "SELECT * FROM purchases WHERE buyer_name='$username' ORDER BY purchase_date DESC LIMIT $items_per_page OFFSET $purchase_offset";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<tr><th>상품명</th><th>가격</th><th>수량</th><th>구매일</th><th>상태</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_sql = "SELECT product_name, price FROM products WHERE id=$product_id";
                    $product_result = $conn->query($product_sql);
                    $product = $product_result->fetch_assoc();

                    // 상태에 따른 배지 클래스 결정
                    $status = $row['status'] ? $row['status'] : 'pending';
                    $status_class = 'status-' . $status;
                    $status_text = '';
                    switch($status) {
                        case 'pending': $status_text = '주문접수'; break;
                        case 'approved': $status_text = '승인됨'; break;
                        case 'rejected': $status_text = '거절됨'; break;
                        default: $status_text = '주문접수'; break;
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($product['product_name']) . "</td>";
                    echo "<td>₩" . number_format($product['price']) . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['purchase_date'] . "</td>";
                    echo "<td><span class='status-badge $status_class'>$status_text</span></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='alert alert-info'>구매한 상품이 없습니다.</div>";
            }

            // 구매 목록 페이지네이션
            echo '<div class="pagination">';
            for ($i = 1; $i <= $total_purchase_pages; $i++) {
                $active = ($i == $purchase_page) ? 'class="active"' : '';
                echo "<a href='mypage.php?purchase_page=$i' $active>$i</a>";
            }
            echo '</div>';
            ?>
        </div>

        <!-- 주문 관리 (판매자용) -->
        <div class="list order-management">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">주문 관리</h3>
            </div>
            <?php
            $sql = "SELECT COUNT(*) as count FROM purchases p 
                    JOIN products pr ON p.product_id = pr.id 
                    WHERE p.seller_name='$username' OR pr.seller_name='$username'";
            $result = $conn->query($sql);
            $total_orders = $result->fetch_assoc()['count'];
            $total_order_pages = ceil($total_orders / $items_per_page);

            $sql = "SELECT p.*, pr.product_name, pr.price, pr.seller_name as product_seller 
                    FROM purchases p 
                    JOIN products pr ON p.product_id = pr.id 
                    WHERE p.seller_name='$username' OR pr.seller_name='$username' 
                    ORDER BY p.purchase_date DESC 
                    LIMIT $items_per_page OFFSET $order_offset";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<tr><th>상품명</th><th>구매자</th><th>수량</th><th>구매일</th><th>현재 상태</th><th>상태 변경</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $status = $row['status'] ? $row['status'] : 'pending';
                    $status_class = 'status-' . $status;
                    $status_text = '';
                    switch($status) {
                        case 'pending': $status_text = '주문접수'; break;
                        case 'approved': $status_text = '승인됨'; break;
                        case 'rejected': $status_text = '거절됨'; break;
                        default: $status_text = '주문접수'; break;
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['buyer_name']) . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['purchase_date'] . "</td>";
                    echo "<td><span class='status-badge $status_class'>$status_text</span></td>";
                    echo "<td>";
                    echo "<form action='mypage.php' method='post' style='display: inline;'>";
                    echo "<input type='hidden' name='purchase_id' value='" . $row['id'] . "'>";
                    echo "<select name='new_status' class='status-select'>";
                    echo "<option value='pending'" . ($status == 'pending' ? ' selected' : '') . ">주문접수</option>";
                    echo "<option value='approved'" . ($status == 'approved' ? ' selected' : '') . ">승인됨</option>";
                    echo "<option value='rejected'" . ($status == 'rejected' ? ' selected' : '') . ">거절됨</option>";
                    echo "</select>";
                    echo "<input type='submit' name='update_order_status' value='업데이트' class='btn btn-success' style='margin-left: 5px;'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='alert alert-info'>관리할 주문이 없습니다.</div>";
            }

            // 주문 관리 페이지네이션
            echo '<div class="pagination">';
            for ($i = 1; $i <= $total_order_pages; $i++) {
                $active = ($i == $order_page) ? 'class="active"' : '';
                echo "<a href='mypage.php?order_page=$i' $active>$i</a>";
            }
            echo '</div>';
            ?>
        </div>

        <!-- 판매 목록 -->
        <div class="list sale-list">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">판매 목록</h3>
                <a href="seller.php" class="btn btn-success" style="background: #6db37f; border: none; padding: 8px 20px; border-radius: 20px; font-weight: 600;">판매하기</a>
            </div>
            <?php
            $sql = "SELECT COUNT(*) as count FROM products WHERE seller_name='$username'";
            $result = $conn->query($sql);
            $total_sales = $result->fetch_assoc()['count'];
            $total_sale_pages = ceil($total_sales / $items_per_page);

            $sql = "SELECT * FROM products WHERE seller_name='$username' LIMIT $items_per_page OFFSET $sale_offset";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<div class='row'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-6 col-lg-4 mb-4'>";
                    echo "<div class='card h-100' style='border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>";
                    
                    // 이미지 표시
                    if (!empty($row["product_image"])) {
                        echo "<div class='card-img-top' style='background-color: #f8f9fa; border-radius: 8px 8px 0 0; padding: 10px; text-align: center; height: 200px; display: flex; align-items: center; justify-content: center;'>";
                        echo "<img src='" . $row["product_image"] . "' alt='" . $row["product_name"] . "' style='max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 4px;'>";
                        echo "</div>";
                    } else {
                        echo "<div class='card-img-top bg-light d-flex align-items-center justify-content-center' style='height: 200px; border-radius: 8px 8px 0 0;'>";
                        echo "<span class='text-muted'>이미지 없음</span>";
                        echo "</div>";
                    }
                    
                    echo "<div class='card-body'>";
                    echo "<form action='mypage.php' method='post'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['product_name']) . "</h5>";
                    echo "<div class='form-group'>";
                    echo "<label>가격:</label>";
                    echo "<input type='number' name='price' value='" . $row['price'] . "' class='form-control' min='0' step='1'>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label>수량:</label>";
                    echo "<input type='number' name='quantity' value='" . $row['quantity'] . "' class='form-control' min='1'>";
                    if ($row['quantity'] <= 0) {
                        echo "<small class='text-danger'>현재 품절 상태입니다.</small>";
                    }
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label>전화번호:</label>";
                    echo "<input type='text' name='seller_phone' value='" . htmlspecialchars($row['seller_phone']) . "' class='form-control'>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label>주소:</label>";
                    echo "<textarea name='seller_address' class='form-control' rows='2'>" . htmlspecialchars($row['seller_address']) . "</textarea>";
                    echo "</div>";
                    echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                    echo "<div class='d-flex justify-content-between'>";
                    echo "<input type='submit' name='edit_product' value='수정' class='btn btn-warning'>";
                    echo "<input type='submit' name='delete_product' value='삭제' class='btn btn-danger'>";
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<div class='alert alert-info'>판매한 상품이 없습니다.</div>";
            }

            // 판매 목록 페이지네이션
            echo '<div class="pagination">';
            for ($i = 1; $i <= $total_sale_pages; $i++) {
                $active = ($i == $sale_page) ? 'class="active"' : '';
                echo "<a href='mypage.php?sale_page=$i' $active>$i</a>";
            }
            echo '</div>';
            ?>
        </div>
    </div>
    <footer>
        <div class="container">
            <p>&copy; 2024 AppRoad | 충주사과 | info@approad.com</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
