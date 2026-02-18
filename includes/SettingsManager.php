<?php
// فئة لإدارة الإعدادات (Settings Manager)
class SettingsManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // الحصول على جميع الإعدادات
    public function getAll() {
        $sql = "SELECT * FROM settings";
        try {
            $rows = $this->db->query($sql)->fetchAll();
            $settings = [];
            foreach ($rows as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        } catch (Exception $e) {
            return [];
        }
    }

    // تحديث إعداد
    public function update($key, $value) {
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value";
        return $this->db->execute($sql, [':key' => $key, ':value' => $value]);
    }

    // اختبار اتصال AMI
    public function testAMI($host, $port, $user, $secret) {
        $timeout = 5;
        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$socket) {
            return ["success" => false, "message" => "فشل الاتصال: $errstr ($errno)"];
        }

        $auth = "Action: Login\r\nUsername: $user\r\nSecret: $secret\r\n\r\n";
        fwrite($socket, $auth);
        
        $response = "";
        while (!feof($socket)) {
            $line = fgets($socket, 4096);
            $response .= $line;
            if (trim($line) == "") break;
        }
        
        fclose($socket);

        if (strpos($response, 'Response: Success') !== false) {
            return ["success" => true, "message" => "تم الاتصال بنجاح!"];
        } else {
            return ["success" => false, "message" => "فشل تسجيل الدخول: " . $response];
        }
    }
}
