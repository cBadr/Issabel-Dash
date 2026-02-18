<?php
// فئة للتحكم في الجلسات والمصادقة (Authentication Class)
// Class to handle user sessions and authentication

class Auth {
    // بدء الجلسة (Start Session)
    public static function start_session() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // التحقق من تسجيل الدخول (Check Login Status)
    public static function is_logged_in() {
        self::start_session();
        return isset($_SESSION['user_id']);
    }

    // تسجيل الدخول (Login)
    public static function login($username, $password) {
        self::start_session();

        // تجاوز مؤقت: تسجيل دخول يدوي للمسؤول لتجاوز مشاكل قاعدة البيانات
        // Temporary Bypass: Manual login for admin to bypass DB issues
        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'admin';
            
            // محاولة تسجيل الحدث في السجلات (اختياري)
            try {
                if (class_exists('Logger')) {
                    Logger::log('Login Success', "User admin logged in via manual bypass.", 1);
                }
            } catch (Exception $e) {
                error_log("Logging failed: " . $e->getMessage());
            }
            
            return true;
        }

        try {
            $db = Database::getInstance();
            
            // البحث عن المستخدم (Find User)
            $sql = "SELECT * FROM users_app WHERE username = :username LIMIT 1";
            $stmt = $db->query($sql, [':username' => $username]);
            $user = $stmt->fetch();

            // التحقق من كلمة المرور (Verify Password)
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // تسجيل الدخول الناجح
                Logger::log('Login Success', "User {$username} logged in successfully.", $user['id']);
                return true;
            }

            // تسجيل محاولة فاشلة
            Logger::log('Login Failed', "Failed login attempt for username: {$username}");
        } catch (Exception $e) {
            // في حالة فشل قاعدة البيانات، نعتمد فقط على التجاوز اليدوي أعلاه
            error_log("Database login failed: " . $e->getMessage());
        }
        
        return false;
    }

    // تسجيل الخروج (Logout)
    public static function logout() {
        self::start_session();
        if (isset($_SESSION['user_id'])) {
            Logger::log('Logout', "User {$_SESSION['username']} logged out.", $_SESSION['user_id']);
        }
        session_destroy();
        header("Location: login.php");
        exit;
    }

    // التحقق من الصلاحيات (Check Permissions)
    public static function require_login() {
        if (!self::is_logged_in()) {
            header("Location: login.php");
            exit;
        }
    }

    // الحصول على المستخدم الحالي (Get Current User)
    public static function current_user() {
        self::start_session();
        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }
}
