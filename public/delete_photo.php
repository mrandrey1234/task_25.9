<?php

require "../config/db.php";

if (!isset($_COOKIE['id']) || empty($_POST['photo_id'])) {
    header("Location: /");
    exit();
}

$photo_id = $_POST['photo_id'];
$user_id = $_COOKIE['id'];

$db = get_connection();

$query = 'SELECT stored_filename, user_id FROM files WHERE id = ?';
$stmt = $db->prepare($query);
$stmt->execute([$photo_id]);
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($photo && $photo['user_id'] == $user_id) {

    unlink("../assets/img/" . $photo['stored_filename']);
    
    $query = 'DELETE FROM files WHERE id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$photo_id]);
}

header("Location: /");
exit();
?>
