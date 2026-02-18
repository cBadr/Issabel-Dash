<?php
// فئة لإدارة الطوابير (Queue Manager)
// Class to manage queues

require_once __DIR__ . '/Database.php';

class QueueManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // الحصول على جميع الطوابير (Get All Queues)
    public function getAll() {
        // في Issabel، قد تكون الطوابير في جدول queues_config أو queues_details
        // سنفترض جدول queues_details أو جدول بسيط للطوابير
        $sql = "SELECT extension, descr, strategy FROM queues_config ORDER BY extension";
        try {
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // إضافة طابور جديد (Add Queue)
    public function add($data) {
        $extension = $data['extension'];
        $descr = $data['descr'];
        $strategy = $data['strategy'];
        
        // التحقق من الأعضاء (Members Validation)
        $members = explode(',', $data['members']);
        foreach ($members as $member) {
            $member = trim($member);
            if (!empty($member) && !$this->isValidExtension($member)) {
                throw new Exception("التحويلة $member غير موجودة.");
            }
        }

        try {
            $this->db->getConnection()->beginTransaction();

            // إضافة الطابور الأساسي
            $sql = "INSERT INTO queues_config (extension, descr, strategy) VALUES (:extension, :descr, :strategy)";
            $this->db->execute($sql, [
                ':extension' => $extension,
                ':descr' => $descr,
                ':strategy' => $strategy
            ]);

            // إضافة تفاصيل الطابور (queues_details)
            $details = [
                'musicclass' => $data['musicclass'] ?? 'default',
                'joinannounce' => $data['joinannounce'] ?? '',
                'recording_mode' => $data['recording_mode'] ?? 'adloc',
                'skip_busy_agents' => $data['skip_busy_agents'] ?? 'yes'
            ];

            foreach ($details as $key => $value) {
                $sqlDetail = "INSERT INTO queues_details (id, keyword, data) VALUES (:id, :keyword, :data)";
                $this->db->execute($sqlDetail, [
                    ':id' => $extension,
                    ':keyword' => $key,
                    ':data' => $value
                ]);
            }

            // إضافة الأعضاء (queues_members) - افتراض وجود جدول للأعضاء
            // $this->addMembers($extension, $members);

            $this->db->getConnection()->commit();
            Logger::log('Add Queue', "Added queue $extension ($descr)", Auth::current_user()['id']);
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }

    // التحقق من وجود التحويلة (Validate Extension)
    private function isValidExtension($ext) {
        $sql = "SELECT COUNT(*) FROM users WHERE extension = :ext";
        $stmt = $this->db->query($sql, [':ext' => $ext]);
        return $stmt->fetchColumn() > 0;
    }
}
