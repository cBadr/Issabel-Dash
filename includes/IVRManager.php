<?php
// فئة لإدارة الرد الآلي (IVR Manager)
// Class to manage IVR

class IVRManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // الحصول على جميع IVRs
    public function getAll() {
        $sql = "SELECT * FROM ivr_details";
        try {
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // إضافة IVR جديد
    public function add($data) {
        // التحقق من البيانات
        if (empty($data['name']) || empty($data['announcement'])) {
            throw new Exception("الاسم والرسالة الصوتية مطلوبان.");
        }

        try {
            $this->db->getConnection()->beginTransaction();

            $sql = "INSERT INTO ivr_details (name, announcement, directdial, timeout) VALUES (:name, :announcement, :directdial, :timeout)";
            $this->db->execute($sql, [
                ':name' => $data['name'],
                ':announcement' => $data['announcement'],
                ':directdial' => $data['directdial'] ?? 'disabled',
                ':timeout' => $data['timeout'] ?? 10
            ]);

            $ivr_id = $this->db->getConnection()->lastInsertId();

            // إضافة الخيارات (Options)
            if (isset($data['options']) && is_array($data['options'])) {
                foreach ($data['options'] as $option) {
                    if (!empty($option['selection']) && !empty($option['destination'])) {
                        $sqlOpt = "INSERT INTO ivr_entries (ivr_id, selection, destination) VALUES (:ivr_id, :selection, :destination)";
                        $this->db->execute($sqlOpt, [
                            ':ivr_id' => $ivr_id,
                            ':selection' => $option['selection'],
                            ':destination' => $option['destination']
                        ]);
                    }
                }
            }

            $this->db->getConnection()->commit();
            Logger::log('Add IVR', "Added IVR {$data['name']}", Auth::current_user()['id']);
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
}
