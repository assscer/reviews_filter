<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Получение всех отзывов для администрирования
$reviews = $db->query("SELECT * FROM reviews ORDER BY date_added DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная панель</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Административная панель</h1>
    <a href="logout.php">Выйти</a>
    <h2>Отзывы</h2>
    <?php foreach ($reviews as $review): ?>
        <div class="review">
            <p><strong>Имя:</strong> <?= htmlspecialchars($review['author_name']) ?></p>
            <p><strong>E-mail:</strong> <?= htmlspecialchars($review['email']) ?></p>
            <p><strong>Сообщение:</strong> <?= htmlspecialchars($review['message']) ?></p>
            <?php if ($review['image']): ?>
                <p><strong>Картинка:</strong></p>
                <img src="uploads/<?= htmlspecialchars($review['image']) ?>" alt="Image" style="max-width: 100px;">
            <?php endif; ?>
            <p><strong>Статус:</strong> <?= $review['status'] === 'approved' ? 'Принят' : ($review['status'] === 'rejected' ? 'Отклонен' : 'На модерации') ?></p>
            <form action="process.php" method="POST">
                <input type="hidden" name="action" value="moderate">
                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                <?php if ($review['status'] !== 'approved'): ?>
                    <button type="submit" name="status" value="approved">Принять</button>
                <?php endif; ?>
                <?php if ($review['status'] !== 'rejected'): ?>
                    <button type="submit" name="status" value="rejected">Отклонить</button>
                <?php endif; ?>
            </form>
            <form action="process.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                <textarea name="message"><?= htmlspecialchars($review['message']) ?></textarea>
                <button type="submit">Изменить</button>
            </form>
            <hr>
        </div>
    <?php endforeach; ?>
</body>
</html>
