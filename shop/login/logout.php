<?php
// 세션이 이미 시작되어 있는지 확인
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 세션 시작
}

// 세션을 비워서 로그아웃 상태로 만듭니다.
$_SESSION = array();

// 쿠키를 삭제합니다. (선택사항)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 세션을 삭제합니다.
session_destroy();

// 로그아웃 후 로그인 페이지로 리디렉션합니다.
header("Location: ./login.php");
exit();
?>
