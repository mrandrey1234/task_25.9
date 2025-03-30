<?php
require "../config/db.php";
require "../config/config.php";
session_start();


 
$errors = [];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['file']['name'][0])) {
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $fileName = $_FILES['file']['name'][$i];
 
            if ($_FILES['file']['size'][$i] > UPLOAD_MAX_SIZE) {
                $errors[] = 'Недопустимый размер файла ' . $fileName;
                continue;
            }
 
            if (!in_array($_FILES['file']['type'][$i], ALLOWED_TYPES)) {
                $errors[] = 'Недопустимый формат файла ' . $fileName;
                continue;
            }
 
            $filePath = UPLOAD_DIR . '/' . basename($fileName);
 
            if (!move_uploaded_file($_FILES['file']['tmp_name'][$i], $filePath)) {
                $errors[] = 'Ошибка загрузки файла ' . $fileName;
                continue;
            }
            else{
                $db = get_connection();
                $text = $_POST['text'] ?? '';
                $user_id = $_COOKIE['id'];
                $orig_name = $_FILES['file']['name'][$i];
                $stored_name = basename($fileName);
                $file_size = $_FILES['file']['size'][$i];
                $file_type = $_FILES['file']['type'][$i];
                
                $query = "INSERT INTO files (user_id, original_filename, stored_filename, file_size, file_type, description) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$user_id, $orig_name, $stored_name, $file_size, $file_type, $text]);

            }
        }
    } else {
        $errors[] = 'Файл не выбран';
    }
    
}
?>

<html>
    <head>
        <title>Загрузить фото</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><h2 class="text-center mb-4">Галерея изображений</h2></a>
                <div class="d-flex ms-auto">
                    <div id="authButtons">
                            <p class="navbar-brand"><?= $_SESSION["name"] ?></p>
                            <a class="navbar-brand" href="../index.php">Назад</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_FILES) && empty($errors)): ?>
                        <div class="alert alert-success">Файлы успешно загружены</div>
                    <?php endif; ?>
                    <form id="registration" method="POST" action="photo.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file">Загрузите изображение</label>
                            <input type="file" class="form-control" id="file" name="file[]">
                            <div class="form-control-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="text">Описание</label>
                            <input type="text" class="form-control" id="text" name="text">
                            <div class="form-control-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>