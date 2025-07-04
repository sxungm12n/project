<?php
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
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <title>상점</title>
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div class="container">
        <h2 class="text-center my-4">상점</h2>

        <!-- 상품 목록 -->
        <div class="list-container">
            <h3>상품 목록</h3>
            <?php
            // 상품 목록 조회 쿼리
            $sql = "SELECT id, product_name, price, quantity, seller_name, product_image FROM products ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='row'>";
                while($row = $result->fetch_assoc()) {
                    $image_path = $row['product_image'] ? $row['product_image'] : 'img/main/apple.jpg';
                    $is_sold_out = $row['quantity'] <= 0;
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="height: 200px; object-fit: contain; background-color: #f8f9fa;" onerror="this.src='img/main/apple.jpg';">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                                <p class="card-text">
                                    <strong>가격:</strong> $<?php echo number_format($row['price'], 2); ?><br>
                                    <strong>수량:</strong> 
                                    <?php if ($is_sold_out): ?>
                                        <span style="color: #dc3545; font-weight: bold;">품절</span>
                                    <?php else: ?>
                                        <?php echo $row['quantity']; ?>개
                                    <?php endif; ?>
                                    <br>
                                    <strong>판매자:</strong> <?php echo htmlspecialchars($row['seller_name']); ?>
                                </p>
                                <a href="store.php?action=detail&id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">상세 정보</a>
                                <?php if ($is_sold_out): ?>
                                    <button class="btn btn-secondary btn-sm" disabled>품절</button>
                                <?php else: ?>
                                    <a href="store.php?action=checkout&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">구매</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                echo "</div>";
            } else {
                echo "<p>등록된 상품이 없습니다.</p>";
            }
            ?>
        </div>

        <!-- 상품 상세 정보 -->
        <div class="detail-container">
            <h3>상품 상세 정보</h3>
            <?php
            // 상품 상세 정보 표시
            if(isset($_GET['action']) && $_GET['action'] == 'detail' && isset($_GET['id'])){
                $id = intval($_GET['id']);
                $sql = "SELECT * FROM products WHERE id=$id";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $image_path = $row['product_image'] ? $row['product_image'] : 'img/main/apple.jpg';
                    ?>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="max-height: 300px; object-fit: contain; background-color: #f8f9fa;" onerror="this.src='img/main/apple.jpg';">
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo htmlspecialchars($row['product_name']); ?></h4>
                            <p><strong>가격:</strong> $<?php echo number_format($row['price'], 2); ?></p>
                            <p><strong>수량:</strong> 
                                <?php if ($row['quantity'] <= 0): ?>
                                    <span style="color: #dc3545; font-weight: bold;">품절</span>
                                <?php else: ?>
                                    <?php echo $row['quantity']; ?>개
                                <?php endif; ?>
                            </p>
                            <p><strong>등록자:</strong> <?php echo htmlspecialchars($row['seller_name']); ?></p>
                            <p><strong>전화번호:</strong> <?php echo htmlspecialchars($row['seller_phone']); ?></p>
                            <p><strong>주소:</strong> <?php echo htmlspecialchars($row['seller_address']); ?></p>
                        </div>
                    </div>
                    <?php
                    echo "<script>document.querySelector('.detail-container').classList.add('visible');</script>";
                } else {
                    echo "<div class='alert alert-danger'>잘못된 상품 ID입니다.</div>";
                }
            }
            ?>
        </div>

        <!-- 구매 폼 -->
        <div class="checkout-container">
            <h3>구매</h3>
            <?php
            // 구매 처리
            if(isset($_GET['action']) && $_GET['action'] == 'checkout' && isset($_GET['id'])){
                $id = intval($_GET['id']);
                $sql = "SELECT * FROM products WHERE id=$id";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $image_path = $row['product_image'] ? $row['product_image'] : 'img/main/apple.jpg';
                    ?>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="max-height: 300px; object-fit: contain; background-color: #f8f9fa;" onerror="this.src='img/main/apple.jpg';">
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo htmlspecialchars($row['product_name']); ?></h4>
                            <p><strong>가격:</strong> $<?php echo number_format($row['price'], 2); ?></p>
                            <?php if ($row['quantity'] <= 0): ?>
                                <div class="alert alert-danger">
                                    <strong>품절</strong> - 현재 구매할 수 없습니다.
                                </div>
                            <?php else: ?>
                            <form action="checkout.php" method="post">
                                <div class="form-group">
                                    <label for="quantity">수량:</label>
                                    <input type="number" name="quantity" min="1" max="<?php echo $row['quantity']; ?>" value="1" class="form-control" onchange="calculateTotal(<?php echo $row['price']; ?>)" style="width: 100px;">
                                </div>
                                <div class="form-group">
                                    <label>총액: <span id="total_price">$<?php echo number_format($row['price'], 2); ?></span></label>
                                </div>
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <div class="form-group">
                                    <label for="payment_method">결제 방법:</label>
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="credit_card">신용카드</option>
                                        <option value="bank_transfer">은행 이체</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="buyer_phone">전화번호:</label>
                                    <input type="text" id="buyer_phone" name="buyer_phone" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="buyer_address">주소:</label>
                                    <textarea id="buyer_address" name="buyer_address" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">구매 완료</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                    echo "<script>document.querySelector('.checkout-container').classList.add('visible');</script>";
                } else {
                    echo "<div class='alert alert-danger'>잘못된 상품 ID입니다.</div>";
                }
            }
            ?>
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