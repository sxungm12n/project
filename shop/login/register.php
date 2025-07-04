<?php
// 오류 메시지 표시
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 설정 파일 포함
require_once '../config.php';

// 데이터베이스 연결
$conn = getDatabaseConnection();

// 폼에서 전송된 데이터 확인
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nickname = $_POST['nickname'];
    $mobile1 = $_POST['mobile1'];
    $mobile2 = $_POST['mobile2'];

    // 비밀번호 암호화
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL 인젝션 방지를 위해 데이터 이스케이프
    $username = mysqli_real_escape_string($conn, $username);
    $nickname = mysqli_real_escape_string($conn, $nickname);
    $mobile1 = mysqli_real_escape_string($conn, $mobile1);
    $mobile2 = mysqli_real_escape_string($conn, $mobile2);

    // 사용자 테이블에 새로운 사용자 추가
    $sql = "INSERT INTO userTBL (userName, userPassword, Nickname, mobile1, mobile2) 
            VALUES ('$username', '$hashed_password', '$nickname', '$mobile1', '$mobile2')";

    if ($conn->query($sql) === TRUE) {
        // 회원가입 성공
        header("Location: signup__success.php");
        exit;
    } else {
        // 회원가입 실패
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
