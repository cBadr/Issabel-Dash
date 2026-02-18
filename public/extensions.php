<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/ExtensionManager.php';
require_once __DIR__ . '/../config/config.php';

Auth::require_login();

$extManager = new ExtensionManager();
$message = '';
$error = '';

// معالجة الإضافة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    try {
        $data = [
            'extension' => $_POST['extension'],
            'name' => $_POST['name'],
            'secret' => $_POST['secret']
        ];
        $extManager->add($data);
        $message = "تم إضافة التحويلة بنجاح";
    } catch (Exception $e) {
        $error = "خطأ: " . $e->getMessage();
    }
}

// معالجة الحذف
if (isset($_GET['delete'])) {
    try {
        $extManager->delete($_GET['delete']);
        $message = "تم حذف التحويلة بنجاح";
    } catch (Exception $e) {
        $error = "خطأ في الحذف: " . $e->getMessage();
    }
}

$search = $_GET['search'] ?? '';
$extensions = $extManager->getAll($search);

include __DIR__ . '/../templates/header.php';
?>

<?php include __DIR__ . '/../templates/navbar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إدارة التحويلات (Extensions)</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExtensionModal">
            <i class="fas fa-plus"></i> إضافة تحويلة جديدة
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- بحث -->
    <form class="row mb-3" method="GET">
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="بحث برقم أو اسم..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="submit">بحث</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>رقم التحويلة</th>
                    <th>الاسم</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($extensions)): ?>
                    <tr>
                        <td colspan="4" class="text-center">لا توجد تحويلات (أو لم يتم الاتصال بقاعدة البيانات)</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($extensions as $ext): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ext['extension']); ?></td>
                            <td><?php echo htmlspecialchars($ext['name']); ?></td>
                            <td><span class="badge bg-success">نشط</span></td> <!-- يمكن جلب الحالة من AMI -->
                            <td>
                                <button class="btn btn-sm btn-info" title="تعديل"><i class="fas fa-edit"></i></button>
                                <a href="?delete=<?php echo $ext['extension']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')" title="حذف"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal إضافة تحويلة -->
<div class="modal fade" id="addExtensionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة تحويلة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">رقم التحويلة (Extension)</label>
                        <input type="number" class="form-control" name="extension" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم (Display Name)</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور (Secret)</label>
                        <input type="text" class="form-control" name="secret" placeholder="اتركه فارغاً للتوليد التلقائي">
                    </div>
                    <div class="alert alert-info">
                        سيتم تطبيق الإعدادات الافتراضية تلقائياً:
                        <ul>
                            <li>DTMF Mode: Auto</li>
                            <li>Recording: Always (In/Out)</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
