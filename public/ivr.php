<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/IVRManager.php';
require_once __DIR__ . '/../config/config.php';

Auth::require_login();
$ivrManager = new IVRManager();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ivrManager->add($_POST);
        $message = "تم إضافة IVR بنجاح";
    } catch (Exception $e) {
        $error = "خطأ: " . $e->getMessage();
    }
}

$ivrs = $ivrManager->getAll();

include __DIR__ . '/../templates/header.php';
include __DIR__ . '/../templates/navbar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">الرد الآلي (IVR)</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIVRModal">
            <i class="fas fa-plus"></i> إضافة IVR جديد
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الرسالة الصوتية</th>
                    <th>الطلب المباشر (Direct Dial)</th>
                    <th>المهلة (Timeout)</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ivrs as $ivr): ?>
                <tr>
                    <td><?php echo $ivr['name']; ?></td>
                    <td><?php echo $ivr['announcement']; ?></td>
                    <td><?php echo $ivr['directdial']; ?></td>
                    <td><?php echo $ivr['timeout']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="addIVRModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة IVR جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>اسم IVR</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>الرسالة الصوتية (Announcement ID)</label>
                        <input type="number" name="announcement" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>الطلب المباشر</label>
                            <select name="directdial" class="form-select">
                                <option value="enabled">مفعل</option>
                                <option value="disabled">معطل</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>المهلة (ثواني)</label>
                            <input type="number" name="timeout" class="form-control" value="10">
                        </div>
                    </div>
                    
                    <hr>
                    <h6>الخيارات (Options)</h6>
                    <div id="ivr-options">
                        <div class="row mb-2">
                            <div class="col-3">
                                <input type="text" name="options[0][selection]" class="form-control" placeholder="رقم (مثلاً 1)">
                            </div>
                            <div class="col-9">
                                <input type="text" name="options[0][destination]" class="form-control" placeholder="الوجهة (مثلاً Extensions: 100)">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">إضافة خيار</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addOption() {
    // كود JavaScript لإضافة حقول جديدة للخيارات
    // للتبسيط، لن نضيفه الآن، ولكن الهيكل موجود
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
