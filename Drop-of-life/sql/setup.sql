CREATE DATABASE blood_donation;

USE blood_donation;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    username VARCHAR(50) UNIQUE,
    location VARCHAR(100),
    bloodgroup VARCHAR(10),
    medical_issues TEXT,
    dob DATE,
    phone VARCHAR(15),
    password VARCHAR(100), 
    is_admin BOOLEAN DEFAULT 0
);

CREATE TABLE blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bloodgroup VARCHAR(10),
    location VARCHAR(100),
    urgency ENUM('urgent', 'not urgent'),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE messages (  
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);

