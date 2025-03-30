<?php
require_once "../config/db.php";
session_start();

if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
    $query = 'SELECT * FROM users WHERE id = ? LIMIT 1';
    $db = get_connection();
    $stmt = $db->prepare($query);
    $stmt->execute([$_COOKIE['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || (strval($result['id']) !== $_COOKIE['id']) || ($result['user_hash'] !== $_COOKIE['hash'])) {
        setcookie("id", "", time() - 3600, "/");
        setcookie("hash", "", time() - 3600, "/");
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        header("Location: ../index.php");
        exit;
    }
} else {
    echo "Включите куки!";
}
?>
