# Система отзывов с модерацией

Это веб-приложение для отправки и управления отзывами с модерацией. Отзывы отправляются на модерацию администратору, который может утвердить или отклонить их. Проект использует PHP, MySQL и jQuery.

## Установка и запуск

1. Клонируйте репозиторий:

   ```bash
   git clone https://github.com/assscer/reviews_filter.git
2. Переход в проект:
    ```bash
    cd admin_tools
3. Старт mySQL:
   ```bash
    sudo service mysql start

    mysql -u ur_db_name -p

4. SQL команды уже в mySQL:
    ```bash
    CREATE DATABASE feedback_db;

    CREATE USER 'new_user'@'localhost' IDENTIFIED BY 'password';
    GRANT ALL PRIVILEGES ON feedback_db.* TO 'new_user'@'localhost';
    FLUSH PRIVILEGES;

5. Импортируйте структуру базы данных, если у вас есть SQL файл с таблицами. Если у вас есть файл, который содержит структуру таблиц для вашего проекта (например, feedback_db.sql), выполните команду:
    ```bash
    mysql -u new_user -p feedback_db < feedback_db.sql

6. Если у вас нет такого файла, создайте таблицы вручную через MySQL. Пример для таблицы reviews:
    ```bash
    CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    edited TINYINT(1) DEFAULT 0,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

7. Убедитесь, что папка uploads имеет правильные права доступа:

После того как проект будет клонирован и настроен, убедитесь, что у веб-сервера есть права на запись в папку uploads. Для этого выполните команду:
    ```bash
    chmod -R 755 uploads

8. Если папка используется для загрузки изображений, убедитесь, что ее владельцем является веб-сервер (например, www-data в Linux):
    ```bash
    chown -R www-data:www-data uploads


9. Запуск сервера проекта:
    ```bash
    mysql -u new_user -p




