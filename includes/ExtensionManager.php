<?php
// فئة لإدارة التحويلات (Extension Manager)
// Class to manage extensions

require_once __DIR__ . '/Database.php';

class ExtensionManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // الحصول على جميع التحويلات (Get All Extensions)
    public function getAll($search = '') {
        $sql = "SELECT extension, name FROM users";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE extension LIKE :search OR name LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY extension ASC";
        
        try {
            return $this->db->query($sql, $params)->fetchAll();
        } catch (Exception $e) {
            // في حالة عدم وجود الجدول (لأغراض التطوير)
            return [];
        }
    }

    // إضافة تحويلة جديدة (Add Extension)
    public function add($data) {
        // التحقق من البيانات
        if (empty($data['extension']) || empty($data['name'])) {
            throw new Exception("رقم التحويلة والاسم مطلوبان.");
        }

        // إعدادات افتراضية (Default Settings)
        $defaults = [
            'dtmfmode' => 'auto',
            'recording_in_external' => 'always',
            'recording_out_external' => 'always',
            'recording_in_internal' => 'always',
            'recording_out_internal' => 'always',
            'recording_ondemand' => 'enabled'
        ];

        // دمج البيانات
        $extension = $data['extension'];
        $name = $data['name'];
        // كلمة المرور (secret) يجب أن تضاف إلى جدول sip أو iax
        $secret = $data['secret'] ?? $extension . '123'; 

        try {
            $this->db->getConnection()->beginTransaction();

            // 1. إضافة إلى جدول users
            $sqlUsers = "INSERT INTO users (extension, name) VALUES (:extension, :name)";
            $this->db->execute($sqlUsers, [':extension' => $extension, ':name' => $name]);

            // 2. إضافة إعدادات SIP (افتراض بسيط)
            // في Issabel الحقيقي، قد تحتاج إلى جداول sip أو devices
            // سنقوم بمحاكاة ذلك أو استخدام جدول sip إذا وجد
            // $this->addSipSetting($extension, 'secret', $secret);
            // $this->addSipSetting($extension, 'dtmfmode', $defaults['dtmfmode']);
            
            // إضافة إعدادات التسجيل (قد تكون في جدول مستقل أو ضمن users حسب النسخة)
            // هنا سنفترض أنها تخزن كإعدادات نصية أو في جدول مرتبط
            
            $this->db->getConnection()->commit();
            Logger::log('Add Extension', "Added extension $extension ($name)", Auth::current_user()['id']);
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }

    // حذف تحويلة (Delete Extension)
    public function delete($extension) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            $sql = "DELETE FROM users WHERE extension = :extension";
            $this->db->execute($sql, [':extension' => $extension]);
            
            // حذف من جداول أخرى (sip, devices, etc)
            
            $this->db->getConnection()->commit();
            Logger::log('Delete Extension', "Deleted extension $extension", Auth::current_user()['id']);
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
}
