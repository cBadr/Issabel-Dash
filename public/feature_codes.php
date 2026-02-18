<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';

Auth::require_login();
$db = Database::getInstance();

// استعلام Feature Codes (غالباً في جدول featurecodes)
try {
    $sql = "SELECT * FROM featurecodes ORDER BY description";
    $features = $db->query($sql)->fetchAll();
} catch (Exception $e) {
    $features = [];
}

include __DIR__ . '/../templates/header.php';
include __DIR__ . '/../templates/navbar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">أكواد الميزات (Feature Codes)</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الوصف</th>
                    <th>الكود الافتراضي</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($features)): ?>
                    <tr>
                        <td colspan="3" class="text-center">لا توجد أكواد متاحة (أو لم يتم الاتصال)</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($features as $f): ?>
                        <tr>
                            <td><?php echo $f['description']; ?></td>
                            <td><?php echo $f['defaultcode']; ?></td>
                            <td>
                                <span class="badge bg-success">Enabled</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
