<?php
// إعدادات الاتصال بقاعدة البيانات وواجهة AMI
// Configuration for Database and AMI connection

class Config {
    // إعدادات قاعدة البيانات (Database Settings)
    const DB_HOST = 'localhost';
    const DB_NAME = 'asterisk'; // قاعدة بيانات Issabel الافتراضية
    const DB_USER = 'root'; // مستخدم قاعدة البيانات (يجب تغييره في البيئة الحقيقية)
    const DB_PASS = 'Medoza120a'; // كلمة مرور Issabel الافتراضية (مثال)
    const DB_CHARSET = 'utf8mb4';

    // إعدادات AMI (Asterisk Manager Interface Settings)
    const AMI_HOST = '127.0.0.1';
    const AMI_PORT = 5038;
    const AMI_USER = 'admin'; // مستخدم AMI
    const AMI_PASS = 'Medoza120a'; // كلمة مرور AMI (مثال)
    const AMI_TIMEOUT = 10; // مهلة الاتصال بالثواني

    // إعدادات التطبيق (App Settings)
    const APP_NAME = 'Issabel Web Manager';
    const APP_LANG = 'ar'; // اللغة الافتراضية: العربية
    const DEBUG_MODE = true; // وضع التصحيح
}
