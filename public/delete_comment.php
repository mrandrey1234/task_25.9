<?php 

require "../config/db.php";

if (!isset($_COOKIE['id']) || empty($_POST['comment_id'])) {
    header("Location: /");
    exit();
}

$comment_id = $_POST['comment_id'];
$user_id = $_COOKIE['id'];

$db = get_connection();
$query = 'SELECT user_id FROM comments WHERE id = ?';
$stmt = $db->prepare($query);
$stmt->execute([$comment_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if ($comment && $comment['user_id'] == $user_id) {
    $query = 'DELETE FROM comments WHERE id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$comment_id]);
}

header("Location: /");
exit();
?>
