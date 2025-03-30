<?php
session_start();
require "config/db.php";
$gallery = gallery();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея изображений</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Галерея изображений</a>
        <div class="d-flex ms-auto">
            <div id="authButtons">
                <?php if(empty($_SESSION)) : ?>
                    <a class="btn btn-outline-primary me-2" href="public/registr.php">Регистрация</a>
                    <a class="btn btn-primary" href="public/login.php">Войти</a>
                <?php else: ?>
                    <span class="navbar-text me-3"><?= $_SESSION["name"] ?></span>
                    <a class="btn btn-success me-2" href="public/photo.php">Загрузить фото</a>
                    <a class="btn btn-danger" href="public/logout.php">Выйти</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="row">
        <?php foreach($gallery as $im): ?>
            <div class="col-md-4 mb-4" data-id="<?= $im['id'] ?>">
                <div class="card">
                    <img src="assets/img/<?= $im['stored_filename'] ?>" class="card-img-top" alt="<?= $im['original_filename'] ?>">
                    <div class="card-body">
                        <p class="card-text"><strong>Описание:</strong> <?= $im['description'] ?></p>
                        <?php if (!empty($_COOKIE['id']) && $_COOKIE['id'] == $im['user_id']) : ?>
                            <form action="public/delete_photo.php" method="post" style="display:inline;">
                                <input type="hidden" name="photo_id" value="<?= $im['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Удалить фото</button>
                            </form>
                        <?php endif; ?>
                        <h5 class="card-title">Комментарии:</h5>
                        <ul class="list-group list-group-flush">
                            <?php $data = comments($im['id']); ?>
                            <?php foreach($data as $id) : ?>
                                <li class="list-group-item">
                                    <strong><?= $id['name'] ?>:</strong> <?= $id['comment_text'] ?>
                                    <br><small class="text-muted">Дата: <?= $id['created_at'] ?></small>
                                    <?php if (!empty($_COOKIE['id']) && $_COOKIE['id'] == $id['user_id']) : ?>
                                        <form action="public/delete_comment.php" method="post" style="display:inline;">
                                            <input type="hidden" name="comment_id" value="<?= $id['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if(!empty($_SESSION)) : ?>
                            <form action="public/coments.php" method="post" class="mt-2">
                                <input type="text" name="comment_text" class="form-control" placeholder="Оставьте комментарий">
                                <input type="hidden" name="file_id" value="<?= $im['id'] ?>"> 
                                <button type="submit" class="btn btn-primary mt-2">Добавить</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>