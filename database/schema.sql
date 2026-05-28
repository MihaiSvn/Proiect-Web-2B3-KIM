CREATE TABLE USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'trainer', 'member') NOT NULL,
    phone VARCHAR(20),
    birth_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TRAINERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    specialization ENUM('fitness', 'physiotherapy', 'mixed') NOT NULL,
    bio VARCHAR(255),
    work_schedule VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE
);

CREATE TABLE SUBSCRIPTIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('fitness', 'physiotherapy', 'mixed') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    validity_days INT NOT NULL,
    description VARCHAR(255),
    max_suspending_days INT DEFAULT 0
);

CREATE TABLE USER_SUBSCRIPTIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subscription_id INT NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('active', 'expired', 'suspended') NOT NULL,
    suspending_days_left INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES SUBSCRIPTIONS(id) ON DELETE RESTRICT
);

CREATE TABLE ROOMS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    type ENUM('fitness', 'physiotherapy', 'mixed') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE EQUIPMENT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    name VARCHAR(255) NOT NULL,
    is_functional BOOLEAN NOT NULL,
    FOREIGN KEY (room_id) REFERENCES ROOMS(id) ON DELETE SET NULL
);

CREATE TABLE SESSIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT,
    room_id INT,
    title VARCHAR(255) NOT NULL,
    type ENUM('fitness', 'physiotherapy', 'mixed') NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    max_capacity INT NOT NULL,
    status ENUM('planned', 'ongoing', 'canceled', 'completed') NOT NULL,
    FOREIGN KEY (trainer_id) REFERENCES TRAINERS(id) ON DELETE SET NULL,
    FOREIGN KEY (room_id) REFERENCES ROOMS(id) ON DELETE SET NULL
);

CREATE TABLE BOOKINGS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id INT NOT NULL,
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'canceled', 'pending') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES SESSIONS(id) ON DELETE CASCADE
);