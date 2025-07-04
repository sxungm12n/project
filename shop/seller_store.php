<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 상품 목록 조회 쿼리
$sql = "SELECT id, product_name, price, quantity, seller_name FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead class='thead-dark'><tr><th>상품명</th><th>가격</th><th>수량</th><th>등록자</th><th>액션</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
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
        echo "<td>" . htmlspecialchars($row["seller_name"]) . "</td>";
        echo "<td><a href='store.php?action=detail&id=" . $row["id"] . "' class='btn btn-info btn-sm'>상세 정보</a>";
        if ($is_sold_out) {
            echo " | <button class='btn btn-secondary btn-sm' disabled>품절</button>";
        } else {
            echo " | <a href='store.php?action=checkout&id=" . $row["id"] . "' class='btn btn-success btn-sm'>구매</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "등록된 상품이 없습니다.";
}
?>
