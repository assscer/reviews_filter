<?php
include 'db.php';

// Обработка сортировки
$order = $_GET['order'] ?? 'date_added';
$allowed = ['date_added', 'author_name', 'email'];
if (!in_array($order, $allowed)) $order = 'date_added';

// Запрос для получения утвержденных отзывов
$reviews = $db->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY $order DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отзывы</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</head>
<body>
    <h1>Отзывы</h1>

    <!-- Уведомление об успешном добавлении отзыва -->
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Ваш отзыв был успешно добавлен и отправлен на модерацию!</p>
    <?php endif; ?>

    <!-- Форма для отправки отзыва -->
    <form id="reviewForm" action="process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <input type="text" name="author_name" placeholder="Имя" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <textarea name="message" placeholder="Ваш отзыв" required></textarea>
        <input type="file" name="image" accept="image/jpeg, image/png, image/gif">
        <button type="submit">Отправить</button>
    </form>

    <h2>Список отзывов</h2>

    <!-- Форма сортировки отзывов -->
    <select id="sortOrder">
        <option value="date_added" <?= $order == 'date_added' ? 'selected' : '' ?>>По дате</option>
        <option value="author_name" <?= $order == 'author_name' ? 'selected' : '' ?>>По имени</option>
        <option value="email" <?= $order == 'email' ? 'selected' : '' ?>>По e-mail</option>
    </select>

    <!-- Вывод отзывов -->
    <div id="reviews">
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <h3><?= htmlspecialchars($review['author_name']) ?></h3>
                <p><?= htmlspecialchars($review['message']) ?></p>
                <?php if ($review['image']): ?>
                    <img src="uploads/<?= htmlspecialchars($review['image']) ?>" alt="Attached Image">
                <?php endif; ?>
                <?php if ($review['edited']): ?>
                    <p><em>Изменен администратором</em></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>

<script>
    // AJAX обработка формы отправки отзыва
    $(document).ready(function() {
        $('#reviewForm').submit(function(e) {
            e.preventDefault(); // предотвращаем перезагрузку страницы

            var formData = new FormData(this); // собираем данные формы
            formData.append('ajax', true); // Добавляем флаг для определения AJAX запроса

            $.ajax({
                url: 'process.php',  // путь к обработчику формы
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response === "success") {
                        $('#reviews').load('index.php #reviews'); // обновляем блок с отзывами
                        $('#reviewForm')[0].reset();  // очищаем форму
                    } else {
                        // alert("Ошибка при добавлении отзыва.");
                    }
                }
            });
        });

        // Сортировка отзывов
        $('#sortOrder').change(function() {
            window.location.href = 'index.php?order=' + $(this).val(); // обновляем страницу с выбранным параметром сортировки
        });
    });
</script>
</html>
