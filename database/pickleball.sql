CREATE DATABASE IF NOT EXISTS pickleball_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pickleball_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'COACH', 'PLAYER') NOT NULL DEFAULT 'PLAYER',
    avatar_url VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE coach_profiles (
    user_id INT PRIMARY KEY,
    bio TEXT,
    years_of_experience INT DEFAULT 0,
    hourly_rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    is_verified TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    rating_avg DECIMAL(3,2) DEFAULT 0.00,
    rating_count INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE player_profiles (
    user_id INT PRIMARY KEY,
    skill_level ENUM('BEGINNER', 'INTERMEDIATE', 'ADVANCED', 'PRO') DEFAULT 'BEGINNER',
    rating_score DECIMAL(3,2) DEFAULT 2.00,
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE courts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    price_per_hour DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coach_id INT NOT NULL,
    court_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('AVAILABLE', 'BOOKED', 'CANCELLED') DEFAULT 'AVAILABLE',
    FOREIGN KEY (coach_id) REFERENCES coach_profiles(user_id) ON DELETE CASCADE,
    FOREIGN KEY (court_id) REFERENCES courts(id) ON DELETE CASCADE
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL UNIQUE,
    player_id INT NOT NULL,
    booking_status ENUM('CONFIRMED', 'COMPLETED', 'CANCELLED') DEFAULT 'CONFIRMED',
    payment_status ENUM('UNPAID', 'PAID', 'REFUNDED') DEFAULT 'UNPAID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES player_profiles(user_id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL UNIQUE,
    coach_id INT NOT NULL,
    player_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (coach_id) REFERENCES coach_profiles(user_id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES player_profiles(user_id) ON DELETE CASCADE
);

-- Dữ liệu mẫu (password: 123456)
INSERT INTO users (full_name, email, phone, password_hash, role) VALUES
('Quản Trị Viên', 'admin@pickleball.vn', '0901000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN'),
('Nguyễn Văn Huấn', 'huan.coach@gmail.com', '0902000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'COACH'),
('Trần Thị Dung', 'dung.coach@gmail.com', '0902000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'COACH'),
('Lê Hoàng Long', 'long.player@gmail.com', '0903000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PLAYER'),
('Phạm Minh Tuấn', 'tuan.player@gmail.com', '0903000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PLAYER');

INSERT INTO coach_profiles (user_id, bio, years_of_experience, hourly_rate, is_verified, is_featured, rating_avg, rating_count) VALUES
(2, 'HLV chuyên nghiệp, nhiều năm kinh nghiệm.', 5, 400000.00, 1, 1, 5.00, 1),
(3, 'HLV dạy từ cơ bản đến nâng cao.', 3, 300000.00, 1, 0, 0.00, 0);

INSERT INTO player_profiles (user_id, skill_level, rating_score, bio) VALUES
(4, 'INTERMEDIATE', 3.50, 'Đã chơi 6 tháng'),
(5, 'BEGINNER', 2.50, 'Mới bắt đầu');

INSERT INTO courts (name, address, city, phone, price_per_hour) VALUES
('Sân Pickleball Cầu Giấy', '123 Dịch Vọng, Cầu Giấy', 'Hà Nội', '0243999888', 180000.00),
('Pickleball Club Tân Bình', '45 Cộng Hòa, Tân Bình', 'TP. Hồ Chí Minh', '02838111222', 200000.00);
