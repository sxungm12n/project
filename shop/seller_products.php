<?php
// seller_products.php

// 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 총액 계산
$total_amount = 0;
$sql = "SELECT product_name, price, quantity, seller_name FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2 class='text-center my-4'>상품 목록</h2>";
    echo "<table class='table table-striped'>";
    echo "<thead class='thead-dark'><tr><th>상품명</th><th>가격</th><th>수량</th><th>총액</th><th>등록자</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        $product_total = $row["price"] * $row["quantity"];
        $total_amount += $product_total;
        $is_sold_out = $row['quantity'] <= 0;
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
        echo "<td>$" . number_format($row["price"], 2) . "</td>";
        echo "<td>";
        if ($is_sold_out) {
            echo "<span style='color: #dc3545; font-weight: bold;'>품절</span>";
        } else {
            echo $row["quantity"];
        }
        echo "</td>";
        echo "<td>$" . number_format($product_total, 2) . "</td>";
        echo "<td>" . htmlspecialchars($row["seller_name"]) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p class='text-center'>등록된 상품이 없습니다.</p>";
}

$conn->close();
?>
