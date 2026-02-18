<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../config/config.php';

Auth::require_login();
$message = '';
$error = '';
$uploadDir = __DIR__ . '/../public/uploads/';

// معالجة الرفع
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio_file'])) {
    $file = $_FILES['audio_file'];
    $allowedTypes = ['audio/mpeg', 'audio/wav', 'audio/x-wav']; // gsm might need specialized handling
    
    if (in_array($file['type'], $allowedTypes)) {
        $filename = basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            $message = "تم رفع الملف بنجاح: $filename";
        } else {
            $error = "فشل رفع الملف.";
        }
    } else {
        $error = "نوع الملف غير مدعوم (فقط mp3, wav).";
    }
}

// معالجة الحذف
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']);
    if (file_exists($uploadDir . $fileToDelete)) {
        unlink($uploadDir . $fileToDelete);
        $message = "تم حذف الملف: $fileToDelete";
    }
}

// قائمة الملفات
$files = [];
if (is_dir($uploadDir)) {
    $scanned_files = @scandir($uploadDir);
    if ($scanned_files !== false) {
        $files = array_diff($scanned_files, array('.', '..'));
    } else {
        $error = "لا يمكن قراءة مجلد التسجيلات (مشكلة صلاحيات).";
    }
} else {
    // محاولة إنشاء المجلد إذا لم يكن موجوداً
    if (!@mkdir($uploadDir, 0755, true)) {
        $error = "مجلد التسجيلات غير موجود ولا يمكن إنشاؤه (مشكلة صلاحيات).";
    }
}

include __DIR__ . '/../templates/header.php';
include __DIR__ . '/../templates/navbar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">تسجيلات النظام (System Recordings)</h1>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- نموذج الرفع -->
    <div class="card mb-4">
        <div class="card-header">رفع ملف جديد</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-auto">
                    <input type="file" class="form-control" name="audio_file" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3">رفع</button>
                </div>
            </form>
        </div>
    </div>

    <!-- قائمة الملفات -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>اسم الملف</th>
                    <th>الحجم</th>
                    <th>تاريخ التعديل</th>
                    <th>معاينة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo $file; ?></td>
                    <td><?php echo round(filesize($uploadDir . $file) / 1024, 2) . ' KB'; ?></td>
                    <td><?php echo date("Y-m-d H:i:s", filemtime($uploadDir . $file)); ?></td>
                    <td>
                        <audio controls>
                            <source src="uploads/<?php echo $file; ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                    <td>
                        <a href="uploads/<?php echo $file; ?>" download class="btn btn-sm btn-success"><i class="fas fa-download"></i></a>
                        <a href="?delete=<?php echo $file; ?>" class="btn btn-sm btn-danger" onclick="return confirm('تأكيد الحذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
