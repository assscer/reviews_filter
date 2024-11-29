CREATE DATABASE feedback_db;
USE feedback_db;

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

