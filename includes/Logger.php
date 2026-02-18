<?php
// فئة لتسجيل الأحداث (Logging Class)
// Class for logging events to database

require_once __DIR__ . '/Database.php';

class Logger {
    // دالة لتسجيل حدث جديد (Log Event)
    public static function log($action, $details = '', $user_id = null) {
        try {
            $db = Database::getInstance();
            $sql = "INSERT INTO logs (action, details, user_id, created_at) VALUES (:action, :details, :user_id, NOW())";
            $db->execute($sql, [
                ':action' => $action,
                ':details' => $details,
                ':user_id' => $user_id
            ]);
        } catch (Exception $e) {
            // في حالة فشل التسجيل في قاعدة البيانات، نسجل في ملف النظام
            error_log("فشل تسجيل الحدث: " . $e->getMessage());
        }
    }

    // دالة للحصول على السجلات (Get Logs)
    public static function get_logs($limit = 100) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM logs ORDER BY created_at DESC LIMIT :limit";
        // ملاحظة: PDO bindParam يتطلب متغير وليس قيمة مباشرة
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
