<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=feedback_db;charset=utf8;unix_socket=/tmp/mysql.sock', 'root', 'SaintBelkin');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Соединение установлено!";
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}
?>
