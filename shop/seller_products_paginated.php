<?php
// 설정 파일 포함
require_once 'config.php';

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 총액 계산
$total_amount = 0;
$sql = "SELECT product_name, product_image, price, quantity, seller_name FROM products WHERE seller_name = '$username' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='d-flex justify-content-between align-items-center mb-4'>";
    echo "<h2 class='mb-0'>내 판매 상품 목록</h2>";
    echo "<a href='mypage.php' class='btn btn-success' style='background: #6db37f; border: none; padding: 10px 25px; border-radius: 20px; font-weight: 600;'>마이페이지로 가기</a>";
    echo "</div>";
    echo "<div class='row'>";
    while ($row = $result->fetch_assoc()) {
        $product_total = $row["price"] * $row["quantity"];
        $total_amount += $product_total;
        
        echo "<div class='col-md-6 col-lg-4 mb-4'>";
        echo "<div class='card h-100' style='border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s;'>";
        
        // 이미지 표시 - 확실한 정사각형 컨테이너
        if (!empty($row["product_image"])) {
            echo "<div class='card-img-top' style='background-color: #f8f9fa; border-radius: 8px 8px 0 0; padding: 10px; text-align: center; height: 0; padding-bottom: 100%; position: relative;'>";
            echo "<div style='position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px; display: flex; align-items: center; justify-content: center;'>";
            echo "<img src='" . $row["product_image"] . "' alt='" . $row["product_name"] . "' style='max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 4px;'>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='card-img-top bg-light d-flex align-items-center justify-content-center' style='height: 0; padding-bottom: 100%; position: relative; border-radius: 8px 8px 0 0;'>";
            echo "<div style='position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center;'>";
            echo "<span class='text-muted'><i class='fas fa-image' style='font-size: 2em;'></i><br>이미지 없음</span>";
            echo "</div>";
            echo "</div>";
        }
        
        echo "<div class='card-body' style='padding: 1.5rem;'>";
        echo "<h5 class='card-title' style='font-size: 1.2em; font-weight: 600; margin-bottom: 1rem; color: #333;'>" . $row["product_name"] . "</h5>";
        echo "<div class='card-text' style='line-height: 1.8;'>";
        echo "<div style='margin-bottom: 0.5rem;'><strong style='color: #666;'>가격:</strong> <span style='color: #28a745; font-weight: 600;'>" . number_format($row["price"]) . "원</span></div>";
        echo "<div style='margin-bottom: 0.5rem;'><strong style='color: #666;'>수량:</strong> ";
        if ($row["quantity"] <= 0) {
            echo "<span style='color: #dc3545; font-weight: bold;'>품절</span>";
        } else {
            echo $row["quantity"] . "개";
        }
        echo "</div>";
        echo "<div style='margin-bottom: 0.5rem;'><strong style='color: #666;'>총액:</strong> <span style='color: #dc3545; font-weight: 600;'>" . number_format($product_total) . "원</span></div>";
        echo "<div style='margin-bottom: 0.5rem;'><strong style='color: #666;'>판매자:</strong> " . $row["seller_name"] . "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
    echo "<div class='alert alert-info text-center'>내 판매 상품 총 금액: " . number_format($total_amount) . "원</div>";
} else {
    echo "<div class='alert alert-warning'>등록된 상품이 없습니다.</div>";
}
?> 