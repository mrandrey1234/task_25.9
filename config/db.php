<?php
function get_connection(){
    return new PDO('mysql:host=MySQL-8.2;dbname=galery', 'root', '');
}
function insert(array $data){
    $query = 'INSERT INTO users (name, email, password, created_at) VALUES(?, ?, ?, ?)';
    $db = get_connection();
    $stmt = $db->prepare($query);
    return $stmt->execute($data);
}
function getUserByEmail(string $email){
    $query = 'SELECT * FROM users WHERE email = ?';
    $db = get_connection();
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return $result;
    }
    return false;
}

function gallery(){
    $query = 'SELECT * FROM files';
    $db = get_connection();
    $stmt = $db->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function comments($id) {
    $query = 'SELECT comments.id ,name, comment_text, comments.user_id, comments.created_at FROM `users` 
                JOIN `comments` ON users.id = comments.user_id 
                JOIN `files` ON comments.file_id = files.id 
                WHERE comments.file_id = ?;';
    $db = get_connection();
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

?>