<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=feedback_db;charset=utf8;unix_socket=/tmp/mysql.sock', 'ur_db_name', 'ur_password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Соединение установлено!";
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}
?>
