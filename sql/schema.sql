-- جدول المستخدمين للتطبيق (App Users Table)
CREATE TABLE IF NOT EXISTS users_app (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Hashed password
    role VARCHAR(20) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إدخال مستخدم افتراضي (Default User: admin / admin123)
-- يرجى تغيير كلمة المرور بعد أول تسجيل دخول
INSERT INTO users_app (username, password, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- جدول السجلات (Logs Table)
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users_app(id) ON DELETE SET NULL
);

-- جدول الإعدادات (Settings Table)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- إعدادات افتراضية (Default Settings)
INSERT INTO settings (setting_key, setting_value) VALUES 
('app_name', 'Issabel Web Manager'),
('theme', 'light'),
('language', 'ar')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
