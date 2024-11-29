<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $author_name = $_POST['author_name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Проверка на существование отзыва с такими же данными
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM reviews WHERE author_name = ? AND email = ? AND message = ?");
            $stmt->execute([$author_name, $email, $message]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // Если такой отзыв уже существует, выводим ошибку
                echo 'Ошибка: такой отзыв уже был отправлен.';
                exit;
            }
        } catch (PDOException $e) {
            echo 'Ошибка при проверке существования отзыва: ' . $e->getMessage();
            exit; // Прерывание выполнения при ошибке
        }

        $image = null;

        // Если изображение прикреплено, проводим валидацию
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            $file_size = $_FILES['image']['size'];
            $file_type = $_FILES['image']['type'];

            if (in_array($file_type, $allowed) && $file_size <= 1048576) {
                // Генерация уникального имени файла и его сохранение
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], "uploads/temp/$image");
            } else {
                echo 'Ошибка: недопустимый формат изображения или размер файла слишком большой.';
                exit; // Прерывание выполнения, если файл не прошел валидацию
            }
        }

        // Вставка отзыва в базу данных с пометкой 'pending' для модерации
        try {
            $stmt = $db->prepare("INSERT INTO reviews (author_name, email, message, image, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->execute([$author_name, $email, $message, $image]);
            // Перенаправление на главную страницу с успешным сообщением
            header('Location: index.php?success=1');
            exit; // Прерывание выполнения после редиректа
        } catch (PDOException $e) {
            // echo 'Ошибка при добавлении отзыва: ' . $e->getMessage();
            // exit; // Прерывание выполнения при ошибке
        }
    }

    // Логика для редактирования и модерации отзывов
    if ($action === 'edit') {
        $id = $_POST['id'];
        $message = $_POST['message'];

        $stmt = $db->prepare("UPDATE reviews SET message = ?, edited = 1 WHERE id = ?");
        $stmt->execute([$message, $id]);

        header('Location: admin.php');
    }

    if ($action === 'moderate') {
        $id = $_POST['id'];
        $status = $_POST['status'];

        $stmt = $db->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        // Перемещение изображения после одобрения отзыва
        if ($status === 'approved' && $review['image']) {
            rename("uploads/temp/{$review['image']}", "uploads/{$review['image']}");
        }

        $stmt = $db->prepare("UPDATE reviews SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        header('Location: admin.php');
    }
}
