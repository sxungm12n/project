<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 설정 파일 포함
require_once '../config.php';

// 데이터베이스 연결
$conn = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM userTBL WHERE userName=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['userPassword'])) {
            $_SESSION['username'] = $username;
            $_SESSION['userID'] = $row['MemberID'];
            $_SESSION['isAdmin'] = $row['isAdmin'];

            if ($row['isAdmin']) {
                header("Location: admin_index.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            echo ("<script>alert('Invalid Password'); location='login.php';</script>");
        }
    } else {
        echo ("<script>alert('Invalid Username'); location='login.php';</script>");
    }
    $stmt->close();
}

$conn->close();
?>
