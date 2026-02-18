<?php
// فئة للتعامل مع Asterisk Manager Interface (AMI)
// Class to handle Asterisk Manager Interface (AMI)

class AMI {
    private $socket;
    private $error;
    private $timeout;
    private $connected = false;

    // المُنشئ (Constructor) - إعدادات الاتصال
    public function __construct() {
        $this->timeout = Config::AMI_TIMEOUT;
    }

    // فتح الاتصال (Open Connection)
    public function connect() {
        $this->socket = fsockopen(Config::AMI_HOST, Config::AMI_PORT, $errno, $errstr, $this->timeout);
        if (!$this->socket) {
            $this->error = "فشل الاتصال بـ AMI: $errstr ($errno)";
            return false;
        }
        $this->connected = true;
        return $this->login();
    }

    // تسجيل الدخول (Login Action)
    private function login() {
        $response = $this->send_request('Login', [
            'Username' => Config::AMI_USER,
            'Secret'   => Config::AMI_PASS
        ]);

        if (strpos($response, 'Response: Success') !== false) {
            return true;
        } else {
            $this->error = "فشل تسجيل الدخول: " . $response;
            $this->disconnect();
            return false;
        }
    }

    // إرسال طلب (Send Request)
    public function send_request($action, $params = []) {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }

        $cmd = "Action: $action\r\n";
        foreach ($params as $key => $value) {
            $cmd .= "$key: $value\r\n";
        }
        $cmd .= "\r\n";

        fwrite($this->socket, $cmd);
        return $this->read_response();
    }

    // قراءة الرد (Read Response)
    private function read_response() {
        $response = "";
        while (!feof($this->socket)) {
            $line = fgets($this->socket, 4096);
            $response .= $line;
            if (trim($line) == "") {
                break;
            }
        }
        return $response;
    }

    // إغلاق الاتصال (Disconnect)
    public function disconnect() {
        if ($this->connected) {
            $this->send_request('Logoff');
            fclose($this->socket);
            $this->connected = false;
        }
    }

    // الحصول على الخطأ (Get Error)
    public function get_error() {
        return $this->error;
    }

    // دالة مساعدة لتنفيذ أمر Asterisk CLI (Execute CLI Command)
    public function command($command) {
        return $this->send_request('Command', ['Command' => $command]);
    }

    // الحصول على قائمة الامتدادات (Extensions List) - مثال لاستخدام AMI
    public function get_extensions_status() {
        // يمكن استخدام ExtensionStateList أو SIPpeers
        return $this->send_request('SIPpeers');
    }
}
