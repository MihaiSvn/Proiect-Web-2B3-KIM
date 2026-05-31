CREATE DATABASE IF NOT EXISTS kim_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE kim_db;

CREATE TABLE IF NOT EXISTS USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'trainer', 'member') NOT NULL DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    profile_picture VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS TRAINERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    specialization ENUM('fitness', 'physiotherapy', 'strength') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS SUBSCRIPTIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    name VARCHAR(255) NOT NULL,
    type ENUM('fitness', 'physiotherapy', 'strength', 'all') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    validity_days INT NOT NULL,
    sessions INT NOT NULL,
    description VARCHAR(255),
    max_suspending_days INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS USER_SUBSCRIPTIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subscription_id INT NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('active', 'expired', 'suspended') NOT NULL,
    suspending_days_left INT DEFAULT 0,
    sessions_left INT DEFAULT 0,
    suspended_until DATETIME,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES SUBSCRIPTIONS(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS ROOMS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    type ENUM('fitness', 'physiotherapy', 'strength') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS EQUIPMENT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    name VARCHAR(255) NOT NULL,
    is_functional BOOLEAN NOT NULL,
    FOREIGN KEY (room_id) REFERENCES ROOMS(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS SESSIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT NOT NULL,
    room_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('fitness', 'physiotherapy', 'strength') NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    max_capacity INT NOT NULL,
    status ENUM('planned', 'ongoing', 'canceled', 'completed') NOT NULL,
    FOREIGN KEY (trainer_id) REFERENCES TRAINERS(id) ON DELETE RESTRICT,
    FOREIGN KEY (room_id) REFERENCES ROOMS(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS BOOKINGS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id INT NOT NULL,
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES SESSIONS(id) ON DELETE CASCADE
);