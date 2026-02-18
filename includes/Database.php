<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    private $pdo;
    private static $instance = null;

    // المُنشئ (Constructor) - خاص لمنع إنشاء كائنات متعددة
    private function __construct() {
        try {
            $dsn = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=" . Config::DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS, $options);
        } catch (PDOException $e) {
            // تسجيل الخطأ بدلاً من عرضه للمستخدم مباشرة في الإنتاج
            error_log("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
            throw new Exception("خطأ في الاتصال بقاعدة البيانات.");
        }
    }

    // الحصول على نسخة وحيدة من الاتصال (Singleton Pattern)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // الحصول على كائن PDO
    public function getConnection() {
        return $this->pdo;
    }

    // تنفيذ استعلام وإرجاع النتائج (Select)
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("خطأ في الاستعلام: " . $e->getMessage());
            throw new Exception("حدث خطأ أثناء تنفيذ الاستعلام.");
        }
    }

    // تنفيذ أمر (Insert, Update, Delete)
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("خطأ في التنفيذ: " . $e->getMessage());
            throw new Exception("حدث خطأ أثناء تنفيذ الأمر.");
        }
    }
}
