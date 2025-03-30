<?php

require_once "../config/db.php";

function auth(array $data) {
    $db = get_connection();

    $query = "SELECT id, name, password FROM users WHERE email = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute([$data['email']]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

function MyHash($id, $hash) {
    $db = get_connection();
    $query = "UPDATE users SET user_hash = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    
    return $stmt->execute(array($hash, $id));
}

?>