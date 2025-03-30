<?php
require_once "../config/db.php";

if(isset($_POST)) {
    $db = get_connection();
    $query = "INSERT INTO comments (file_id, user_id, comment_text) VALUE (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$_POST['file_id'], $_COOKIE['id'], $_POST['comment_text']]);
    header("Location: /");
    exit();
}
else{
    header("Location: /");
    exit();
}


?>